<?php

namespace App\Models;

use App\Helpers\SepaHelper;
use App\Models\Invoce;
use Config;
use Exception;
use DateTime;
use Illuminate\Database\Eloquent\Model;

class Incasso extends Model
{
    
	protected $table = 'incassos';

	protected $config = array(
		'name' => 'UWvoordeelpas',
		'IBAN' => 'NL10INGB0007136712',
		'BIC' => 'INGBNL2A',
		'batch' => TRUE,
		'creditor_id' => 'NL',
		'currency' => 'EUR',
		'version' => '2'
	);

    public function __construct()
    {
        parent::__construct();
    }

    public function amountIncasso($company)
    {
        $invoices = Invoice::where('invoices.paid', 0)
            ->where('company_id', $company)
            ->get()
        ;  

        $i = 0;
        foreach ($invoices as $key => $invoice) {
            if ($invoice->getMeta('invoice_direct_debit')) {
                $i++;
            }
        }

        return $i;
    }

    public function generateDirectDebitXml($invoices, $key)
    {
		$SEPASDD = new SepaHelper($this->config);

        $invoiceModel = new Invoice();
		$ibanArray = Config::get('preferences.iban2bicMap');

        $i = 0;
		foreach ($invoices as $invoice) {
            $company = Company::find($invoice->company_id);

            if (trim($company->financial_iban) != '') {
                $bankCode = strtoupper(substr($company->financial_iban, 4, 4));

                $date = new DateTime(date('Y-m-d'));
				$date->modify('-1 day');

                if (array_key_exists($bankCode, $ibanArray)) {
                    $bic = $ibanArray[$bankCode];
                }

                switch ($key) {
                    case 'debit':
                        if ($invoice->total_persons > 0) {
                            $amount = ($invoice->total_persons * 1 * 1.21) * 100;
                        }

                        if ($invoice->type == 'products') {
                            $amount = $invoiceModel->getTotalProductsSaldo($invoice->products, 1) * 100;
                        }
                        break;
                    
                    case 'payments':
                        if ($invoice->total_saldo > 0) {
                             $amount = $invoice->total_saldo * 100;
                        }
                        break;  
                }

                # Add Payment
                $payment = array(
                	'name' => $company->financial_iban_tnv,
                    'IBAN' => $company->financial_iban,
                    'BIC' => isset($bic) ? $bic : '',
                    'amount' => $amount,
                    'type' => $this->amountIncasso($invoice->company_id) + $i != 1 ? 'RCUR' : 'FRST',
                    'collection_date' => date('Y-m-d'),
                    'mandate_id' => $invoice->invoice_number,
                    'mandate_date' => $date->format('Y-m-d'),
                    'description' => ($key == 'payments' ? 'Uitbetaling' : 'Incasso').' '.$invoice->invoice_number,
                    'end_to_end_id' => 'uvp'.$invoice->company_id
                );

                $SEPASDD->addPayment($payment);
			}
        }

		try {
			$xml = $SEPASDD->save();

			return $xml;
		} catch(Exception $e){
			return $e->getMessage();
		}
    }
}
