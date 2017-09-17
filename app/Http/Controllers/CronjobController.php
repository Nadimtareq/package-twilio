<?php

namespace App\Http\Controllers;

use App;
use App\Models\Barcode;
use App\Models\BarcodeUser;
use App\Models\Reservation;
use App\Models\Category;
use App\Models\Affiliate;
use App\Models\AffiliateCategory;
use App\Models\Transaction;
use App\Models\MailTemplate;
use App\Models\Company;
use App\Models\User;
use Intervention\Image\ImageManagerStatic as Image;
use Carbon\Carbon;
use Config;
use Illuminate\Http\Request;
use Mail;
use Redirect;
use Parser;
use File;
use URL;
use PDF;
use App\Models\Table;

class CronjobController extends Controller {

    public function releaseTables() {
       return DB::update('update dinning_tables set status = 0 , release_time=NULL where release_time < ?',
                [date('Y-m-d H:i:s')]);
    }

    public function processTradetrackerTransactions() {
        set_time_limit(0);

        $client = new \SoapClient('http://ws.tradetracker.com/soap/affiliate?wsdl',
                array(
            'compression' => SOAP_COMPRESSION_ACCEPT | SOAP_COMPRESSION_GZIP
        ));

        $client->authenticate(127774, '810a0b6ecd147f0d5a73c0bb5e0b868b982997b0');

        $affiliateSiteID = 232507;

        $options = array(
            'registrationDateFrom' => date("Y-m-d", strtotime("-2 weeks")),
            'registrationDateTo' => date("Y-m-d"),
        );

        foreach ($client->getConversionTransactions($affiliateSiteID, $options) as
                    $transaction) {
            $insert = new Transaction;
            $insert->external_id = $transaction->ID;
            $insert->program_id = $transaction->campaign->ID;
            $insert->user_id = $transaction->reference; // Is this the subId? @todo Check!
            $insert->amount = $transaction->commission;
            $insert->ip = $transaction->IP;
            $insert->processed = date("Y-m-d H:i:s",
                    strtotime($transaction->originatingClickDate));
            $insert->status = $transaction->transactionStatus;
            $insert->save();

            $saldo = User::where('id', $transaction->reference)->pluck('saldo');

            User::where('id', $transaction->reference)->update(['saldo' => ($saldo +
                $transaction->Commission)]);
        }
    }

    public function processAffilinetTransactions() {
        error_reporting(E_ALL);
        ini_set('display_errors', '1');
        set_time_limit(0);
        define("WSDL_LOGON", "https://api.affili.net/V2.0/Logon.svc?wsdl");
        define("WSDL_PROGRAM",
                "https://api.affili.net/V2.0/PublisherProgram.svc?wsdl");

        $username = '763801';
        $password = 'a5sCyaQYicmkjI36brLT';

        $soapLogon = new \SoapClient(WSDL_LOGON);
        $token = $soapLogon->Logon(array(
            'Username' => $username,
            'Password' => $password,
            'WebServiceType' => 'Publisher'
        ));
        $pageSettings = array(
            'CurrentPage' => 1,
            'PageSize' => 100,
        );
        $rateFilter = array(
            'RateMode' => 'PayPerSale',
            'RateNumber' => 1
        );
        $transactionQuery = array(
            'StartDate' => strtotime("-2 weeks"),
            'EndDate' => strtotime("today"),
            'RateFilter' => $rateFilter,
            'TransactionStatus' => 'All',
            'ValuationType' => 'DateOfRegistration'
        );

        $soapRequest = new SoapClient(WSDL_STATS);
        $response = $soapRequest->GetTransactions(array(
            'CredentialToken' => $token,
            'PageSettings' => $pageSettings,
            'TransactionQuery' => $transactionQuery
        ));

        if (isset($response->SalesLeadsStatisticsRecords->SalesLeadsStatisticsRecords) &&
                sizeof($response->SalesLeadsStatisticsRecords->SalesLeadsStatisticsRecords) >
                0) {
            foreach ($response->SalesLeadsStatisticsRecords->SalesLeadsStatisticsRecords as
                        $transaction) {
                $insert = new Transaction;
                $insert->external_id = $transaction->ID;
                $insert->program_id = $transaction->ProgramIds;
                $insert->user_id = $transaction->SubID; // Is this the subId? @todo Check!
                $insert->amount = $transaction->Commission;
                $insert->processed = date("Y-m-d H:i:s",
                        strtotime($transaction->Date));
                $insert->status = $transaction->Status;
                $insert->save();

                $saldo = User::where('id', $transaction->SubID)->pluck('saldo');

                User::where('id', $transaction->SubID)->update(['saldo' => ($saldo +
                    $transaction->Commission)]);
            }
        }
    }

    /* Payments */

    public function generateInvoices() {
        $reservations = Reservation::whereRaw('date >= DATE_SUB(NOW(), INTERVAL 2 WEEK)')
                ->whereRaw('date <= NOW()')
                ->groupBy('company_id')
                ->get();

        $invoices = array();

        foreach ($reservations as $reservation) {
            //generate invoice
            $invoiceReservations = Reservation::where('company_id', '=',
                            $reservation->company_id)->whereRaw('date >= DATE_SUB(NOW(), INTERVAL 2 WEEK)')
                    ->whereRaw('date <= NOW()')
                    ->get();

            $oCompany = Company::find($reservation->company_id);

            $totalPersons = 0;

            foreach ($invoiceReservations as $invoiceReservation) {
                $totalPersons += $invoiceReservation->persons;
            }

            $invoiceSeq = 1;

            if (date('d') >= 14) {
                $invoiceSeq = 2;
            }

            $invoiceNumber = date('Y') . date('m') . '0' . $invoiceSeq . $reservation->company_id;
            $contentBlock = Content::getBlocks();

            $oInvoice = new Invoice();

            $oInvoice->invoicenumber = $invoiceNumber;
            $oInvoice->amount = $totalPersons;

            $oInvoice->company_id = $reservation->company_id;

            $oInvoice->save();

            $invoices[] = $oInvoice;

            $html = view('template.invoice',
                    array(
                'invoiceNumber' => $invoiceNumber,
                'company' => $oCompany,
                'footer' => (isset($contentBlock[17]) ? $contentBlock[17] : ''),
                'footer_2' => (isset($contentBlock[18]) ? $contentBlock[18] : ''),
                'saldo' => $totalPersons,
                'totalPersons' => $totalPersons
                    ))->render();


            $invoicePdf = PDF::load($html);

            //send email
            Mail::send('emails.incasso', null,
                    function($message) use ($oInvoice, $oCompany, $invoicePdf) {
                //$message->attach($tmpfname);
                $message->attachData($invoicePdf->output(),
                        'Factuur_' . $oInvoice->invoicenumber);
                //$message->to($oCompany->email)->subject('Factuur met factuurnummer: '.$oInvoice->invoicenumber);

                $message->to('gjdbrink@gmail.com')->subject('Factuur met factuurnummer: ' . $oInvoice->invoicenumber);
            });
        }

        $this->generateDirectDebitXml($invoices);
    }

    public function initDirectDebitXml() {
        require_once(app_path('Libs/SEPASDD.php'));

        $config = array("name" => "Uwvoordeelpas",
            "IBAN" => "NL10INGB0007136712",
            "BIC" => "INGBNL2A",
            "batch" => true,
            "creditor_id" => "NL",
            "currency" => "EUR",
            "version" => "2"
        );

        try {
            $SEPASDD = new \SEPASDD($config);
        } catch (Exception $e) {
            echo $e->getMessage();
        }

        return $SEPASDD;
    }

    public function generateDirectDebitXml($invoices) {
        $SEPASDD = $this->initDirectDebitXml();

        foreach ($invoices as $oInvoice) {
            $oCompany = Company::find($oInvoice->company_id);

            $date = new \DateTime(date('Y-m-d'));

            $date->modify('-1 day');

            $iban = $oCompany->financial_iban;

            if ($oCompany->financial_iban != '') {
                $bankCode = strtoupper(substr($iban, 4, 4));
                if (array_key_exists($bankCode,
                                Config::get('preferences.iban2bicMap'))) {
                    $bic = Config::get('preferences.iban2bicMap')[$bankCode];
                }

                $payment = array("name" => $oCompany->financial_iban_tnv,
                    "IBAN" => $oCompany->financial_iban,
                    "BIC" => $bic,
                    "amount" => $oInvoice->amount * 100,
                    "type" => "FRST",
                    "collection_date" => date('Y-m-d'),
                    "mandate_id" => $oInvoice->invoicenumber,
                    "mandate_date" => $date->format('Y-m-d'),
                    "description" => "Euroincasso uwvoordeelpas.nl " . $oInvoice->invoicenumber,
                );

                try {
                    $endToEndId = $SEPASDD->addPayment($payment);
                } catch (Exception $e) {
                    echo $e->getMessage();
                }

                try {
                    $xml = $SEPASDD->save();
                } catch (Exception $e) {
                    echo $e->getMessage();
                }
            }
        }

        $oIncasso = new Incasso();

        $oIncasso->no_of_invoices = count($invoices);
        $oIncasso->xml = $xml;

        $oIncasso->save();

        $tmpfname = tempnam('/tmp', 'Incasso' . date('Ymd') . '.xml');

        $handle = fopen($tmpfname, 'w');

        fwrite($handle, $xml);

        fclose($handle);

        //send xml
        Mail::send('emails.incasso', ['data' => $tmpfname],
                function($message) use ($tmpfname) {
            $message->attach($tmpfname);
            $message->to('sandra@uwvoordeelpas.nl')->subject('Er zijn nieuwe incasso xml\'s gegenereerd.');
            //$message->to('gjdbrink@gmail.com')->subject('Er zijn nieuwe incasso xml\'s gegenereerd.');
        });

        return;
    }

}
