<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Invoice;
use App\Models\MailTemplate;
use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Models\TempReservation;
use App\Models\Company;
use App\Models\CompanyReservation;
use App\Helpers\CalendarHelper;
use App\Models\Giftcard;
use App\Models\GiftcardUse;
use App\Models\FutureDeal;
use Carbon\Carbon;
use Cartalyst\Sentinel\Checkpoints\ThrottlingException;
use Cartalyst\Sentinel\Checkpoints\NotActivatedException;
use Sentinel;
use Reminder;
use Activation;
use Alert;
use Redirect;
use App\User;
use Mail;
use App;
use DB;
use Config;
use URL;
use Mollie_API_Client;

class PaymentController extends Controller {

    private $mollie;

    public function __construct() {
        $this->mollie = new Mollie_API_Client;
        $this->mollie->setApiKey(App::environment('production') ? getenv('MOLLIE_PRODKEY') : getenv('MOLLIE_TESTKEY'));
    }

    public function updateDirectory() {
        $coreCommunicator = new \CoreCommunicator(\Configuration::getDefault());
        $diRes = $coreCommunicator->Directory();

        if ($diRes->IsError) {
            dd($diRes->Error);
        } else {
            // handle response: display list of issuing banks
        }
    }

    public function initiateIdealPayment(Request $request) {
        if($request->session()->has('amount')){
            $amount=$request->session()->get('amount');
        }else{
            $this->validate($request, [
                'amount' => 'required'
            ]);
            $amount=$request->amount;
        }


        if ($amount <= 0.1) {
            alert()->error('', 'Het bedrag is te laag om verder te gaan')->persistent('Sluiten');
            return Redirect::to('payment/charge');
        }

        if (!is_numeric($amount)) {
            if (preg_match('/[0-9]{1,3},[0-9]{1,2}/', $amount) || preg_match('/[0-9]{1,3}.[0-9]{1,2}/', $amount)
            ) {
                if (preg_match('/[0-9]{1,3},[0-9]{1,2}/', $amount)) {
                    $amount = preg_replace('/,/', '.', $amount);
                }
            } else {
                return view('payments/charge')->with('error', 'Graag een geldig bedrag invoeren');
            }
        }

        $redirection_url = URL::to('payment/success');
        $payment_user_id = 0;
        if (Sentinel::check()) {
            $payment_user_id = Sentinel::getUser()->id;
        }
        if ($request->has('buy') && $request->input('buy') == 'voordeelpas') {
            $redirection_url = URL::to('payment/success?voordeelpas=1');
        } elseif ($request->has('buy') && $request->input('buy') == 'pay_extra_for_deal' && $request->input('temp_reservation_id')) {
            $temp_reservation_id = $request->input('temp_reservation_id');
            $temp_reservation = DB::table('temp_reservations')->where('id', '=', $temp_reservation_id)->first();
            if ($temp_reservation) {
                $payment_user_id = $temp_reservation->user_id;
            }
            $redirection_url = URL::to('payment/success?trid=' . base64_encode($temp_reservation_id) . '&pay_extra_for_deal=1');
        } elseif ($request->has('buy') && $request->input('buy') == 'future_deal' && $request->input('future_deal_id')) {
            $future_deal_id = $request->input('future_deal_id');
            $future_deal = DB::table('future_deals')->where('id', '=', $future_deal_id)->first();
            if ($future_deal) {
                $payment_user_id = $future_deal->user_id;
            }
            $redirection_url = URL::to('payment/success?future_deal_id=' . base64_encode($future_deal_id) . '&future_deal=1');
        }
        $payment = $this->mollie->payments->create(array(
            'amount' => $amount,
            'description' => 'Saldo ophogen uwvoordeelpas met ' . $amount,
            'redirectUrl' => $redirection_url
        ));

        $oPayment = new Payment();
        $oPayment->type = 'opwaardering';
        $oPayment->mollie_id = $payment->id;
        $oPayment->user_id = $payment_user_id;
        $oPayment->status = $payment->status;
        $oPayment->amount = $amount;
        $oPayment->save();

        return Redirect::to($payment->links->paymentUrl);
    }

    public function validatePaymentInvoice(Request $request) {
        $userPayments = Payment::where('user_id', Sentinel::getUser()->id)
                ->where('mollie_id', '!=', '')
                ->where('type', 'LIKE', '%invoice_%')
                ->where('status', 'open')
                ->orderBy('created_at', 'desc')
                ->first()
        ;

        if ($userPayments == null) {
            alert()->error('', 'Er is een fout opgetreden, probeert u het alstublieft opnieuw')->persistent('Sluiten');

            return Redirect::to('payments/charge');
        }

        $payment = $this->mollie->payments->get($userPayments['mollie_id']);

        $userPayments->payment_type = $payment->method;
        $userPayments->status = $payment->status;
        $userPayments->save();

        if ($payment->status == 'paid') {
            if (count($userPayments) >= 1) {
                preg_match('/(\d+)/', $userPayments->type, $matches, PREG_OFFSET_CAPTURE);

                $invoice = Invoice::select(
                                'invoices.id', 'invoices.invoice_number', 'invoices.start_date', 'invoices.products', 'invoices.type', 'invoices.debit_credit', 'invoices.total_persons as totalPersons', 'invoices.total_saldo as totalSaldo', 'companies.slug as companySlug', 'companies.name as companyName', 'companies.kvk as companyKVK', 'companies.address as companyAddress', 'companies.city as companyCity', 'companies.email as companyEmail', 'companies.btw as companyBTW', 'companies.financial_iban as companyFinancialIban', 'companies.financial_iban_tnv as companyFinancialIbantnv', 'companies.zipcode as companyZipcode'
                        )
                        ->where('paid', '=', 0)
                        ->leftJoin('companies', 'companies.id', '=', 'invoices.company_id')
                ;

                if (Sentinel::inRole('admin') == FALSE) {
                    $invoice = $invoice->where('companies.user_id', '=', Sentinel::getUser()->id);
                }

                $invoice = $invoice->where('invoices.invoice_number', $matches[0][0])->first();
                $invoice->paid = 1;
                $invoice->save();
            }

            alert()->success('', 'Uw factuur is succesvol betaald.')->persistent('Sluiten');
            return Redirect::to('admin/invoices/overview/' . $invoice->companySlug);
        } elseif ($payment->status == 'cancelled') {
            alert()->error('', 'U heeft de transactie geannuleerd')->persistent('Sluiten');

            return Redirect::to('payments/charge');
        } else {
            alert()->error('', 'Er is een fout opgetreden, probeert u het alstublieft opnieuw')->persistent('Sluiten');

            return Redirect::to('payments/charge');
        }
    }

    public function validatePayment(Request $request) {

        
        setlocale(LC_ALL, 'nl_NL', 'Dutch');
        $payment_user_id = $temp_transaction_id = 0;
        $obj_tr = $deal = $future_deal = NULL;
        if (Sentinel::check()) {
            $payment_user_id = Sentinel::getUser()->id;
        }
        if ($request->has('pay_extra_for_deal') && $request->get('pay_extra_for_deal') == '1' && $request->get('trid')) {
            $temp_transaction_id = base64_decode($request->get('trid'));
            $obj_tr = DB::table('temp_reservations')->where('id', '=', $temp_transaction_id)->first();
            if ($obj_tr) {
                $payment_user_id = $obj_tr->user_id;
            }
        }
        if ($request->has('future_deal') && $request->get('future_deal') == '1' && $request->get('future_deal_id')) {
            $future_deal_id = base64_decode($request->get('future_deal_id'));
            $future_deal = FutureDeal::find($future_deal_id);
            if ($future_deal) {
                $payment_user_id = $future_deal->user_id;
            }
        }

        $userPayments = Payment::where(
                        'user_id', $payment_user_id
                )
                ->where('mollie_id', '!=', '')
                ->where('type', '=', 'opwaardering')
                ->where('status', 'open')
                ->orderBy('created_at', 'desc')
                ->first()
        ;

       // return "Yes";

       

        if ($userPayments == null) {
            Alert::error(
                            'Er is een fout opgetreden, probeert u het alstublieft opnieuw'
                    )
                    ->persistent('Sluiten')
            ;

            return Redirect::to('payments/charge');
        }

        $payment = $this->mollie->payments->get($userPayments['mollie_id']);

        $userPayments->payment_type = $payment->method;
        $userPayments->status = $payment->status;
        $userPayments->save();

        if ($payment->status == 'paid') {
            if (count($userPayments) >= 1) {
                $oUser = Sentinel::getUserRepository()->findById($userPayments->user_id);
                if ($obj_tr) {
                    $data = new Reservation;
                    $data->date = $obj_tr->date;
                    $data->time = $obj_tr->time;
                    $data->persons = $obj_tr->persons;
                    $data->company_id = $obj_tr->company_id;
                    $data->user_id = $obj_tr->user_id;
                    $data->reservation_id = $obj_tr->reservation_id;
                    $data->name = $obj_tr->name;
                    $data->email = $obj_tr->email;
                    $data->phone = $obj_tr->phone;

                    if ($obj_tr->option_id) {
                        $data->option_id = $obj_tr->option_id;
                    }

                    $data->comment = $obj_tr->comment;
                    $data->saldo = $obj_tr->saldo;
                    $data->newsletter_company = $obj_tr->newsletter_company;
                    $data->allergies = $obj_tr->allergies;
                    $data->preferences = $obj_tr->preferences;
                    $data->status = $obj_tr->status;
                    if ($data->save()) {
                        $oUser->saldo = 0;
                        $oUser->save();

                        DB::table('temp_reservations')->where('id', '=', $temp_transaction_id)->delete();

                        $carbon_date = Carbon::create(
                                        date('Y', strtotime($data->date)), date('m', strtotime($data->date)), date('d', strtotime($data->date)), 0, 0, 0
                        );

                        $calendarHelper = new CalendarHelper();
                        if ($data->option_id) {
                            $deal = DB::table('reservations_options')->where('id', '=', $data->option_id)->first();
                        }
                        $company = Company::where('id', $data->company_id)->where('no_show', '=', 0)->first();
                        if ($company) {
                            $calendar = $calendarHelper->displayCalendars(
                                    1, 'Reservering bij ' . $company->name, 'Reservering voor ' . $company->name . ' op ' . $carbon_date->formatLocalized('%A %d %B %Y') . ' om ' . date('H:i', strtotime($data->time)) . ' met ' . $data->persons . ' ' . ($data->persons == 1 ? 'persoon' : 'personen'), ($company->address . ', ' . $company->zipcode . ', ' . $company->city), date('Y-m-d', strtotime($data->date)) . ' ' . date('H:i:s', strtotime($data->time))
                            );
                            // Send mail to company owner
                            $time = date('H:i', strtotime($data->time));
                            $date = date('Y-m-d', strtotime($data->date));

                            $reservationTimes = CompanyReservation::getReservationTimesArray(
                                            array(
                                                'company_id' => array($company->id),
                                                'date' => $date,
                                                'selectPersons' => $data->persons,
                                                'groupReservations' => NULL
                                            )
                            );
                            $discount = json_decode($company->discount);
                            $discountDays = json_decode($company->days);
                            $daysArray = Config::get('preferences.days');

                            if (is_array($discountDays)) {
                                foreach ($discountDays as $discountDay) {
                                    $day[] = lcfirst($daysArray[$discountDay]);
                                }

                                $day = implode(',', $day);
                            }
                            $allergies = json_decode($data->allergies);
                            $preferences = json_decode($data->preferences);

                            $mailtemplate = new MailTemplate();
                            $mailtemplate->sendMail(array(
                                'email' => $company->email,
                                'reservation_id' => $data->id,
                                'template_id' => 'new-reservation-company',
                                'company_id' => $company->id,
                                'manual' => $reservationTimes[$time][$company->id]['isManual'],
                                'replacements' => array(
                                    '%name%' => $data->name,
                                    '%cname%' => $company->contact_name,
                                    '%saldo%' => $data->saldo,
                                    '%phone%' => $data->phone,
                                    '%email%' => $data->email,
                                    '%date%' => date('d-m-Y', strtotime($data->date)),
                                    '%time%' => date('H:i', strtotime($data->time)),
                                    '%persons%' => $data->persons,
                                    '%comment%' => $data->comment,
                                    '%discount%' => isset($discount[0]) ? $discount[0] : '',
                                    '%discount_comment%' => $company->discount_comment,
                                    '%days%' => isset($day) ? $day : '',
                                    '%allergies%' => ($allergies === null) ? '' : implode(",", $allergies),
                                    '%preferences%' => ($preferences === null) ? '' : implode(",", $preferences),
                                )
                            ));


                            // Send to client
                            $mailtemplate->sendMail(array(
                                'email' => $data->email,
                                'reservation_id' => $data->id,
                                'template_id' => 'reservation-pending-client',
                                'company_id' => $company->id,
                                'fromEmail' => $company->email,
                                'replacements' => array(
                                    '%name%' => $data->name,
                                    '%cname%' => $company->contact_name,
                                    '%saldo%' => $data->saldo,
                                    '%phone%' => $data->phone,
                                    '%email%' => $data->email,
                                    '%date%' => date('d-m-Y', strtotime($data->date)),
                                    '%time%' => date('H:i', strtotime($data->time)),
                                    '%persons%' => $data->persons,
                                    '%comment%' => $data->comment,
                                    '%discount%' => isset($discount[0]) ? $discount[0] : '',
                                    '%discount_comment%' => $company->discount_comment,
                                    '%days%' => isset($day) ? $day : '',
                                    '%allergies%' => ($allergies === null) ? '' : implode(",", $allergies),
                                    '%preferences%' => ($preferences === null) ? '' : implode(",", $preferences),
                                )
                            ));

                            if ($deal) {
                                Alert::success(
                                        'Uw reservering voor ' . $deal->name . ' bij ' . $company->name . ' op ' . $carbon_date->formatLocalized('%A %d %B %Y') . ' om ' . date('H:i', strtotime($data->time)) . ' met ' . $data->persons . ' ' . ($data->persons == 1 ? 'persoon' : 'personen') . ' wordt doorgegeven aan het restaurant, welke contact met u opneemt. <br /><br /> ' . $calendar . '<br /> <br /><span class=\'addthis_sharing_toolbox\'></span>', 'Bedankt ' . $oUser->name
                                )->html()->persistent('Sluiten');
                            } else {
                                Alert::success(
                                        'U heeft succesvol '. ($data->persons == 1 ? 'persoon' : 'personen').'x de deal: ' . $deal->name . ' in uw account. 
                        <br /><br /><span class="text-center"><small>* Deze zijn pas geldig na reservering vanuit uw account. </small></span> 
                        ','Bedankt ' . $user->name
                        
                                        // 'Uw reservering bij ' . $company->name . ' op ' . $carbon_date->formatLocalized('%A %d %B %Y') . ' om ' . date('H:i', strtotime($data->time)) . ' met ' . $data->persons . ' ' . ($data->persons == 1 ? 'persoon' : 'personen') . ' wordt doorgegeven aan het restaurant, welke contact met u opneemt.<br /><br /> U heeft aangegeven &euro;' . $data->saldo . ' korting op de rekening te willen. Klopt dit niet? <a href=\'' . URL::to('account/reservations') . '\' target=\'_blank\'>Klik hier</a><br /><br /> ' . $calendar . '<br /><br /> <span class=\'addthis_sharing_toolbox\'></span>', 'Bedankt ' . $oUser->name
                                )->html()->persistent('Sluiten');
                            }
                            return Redirect::to('restaurant/' . $company->slug);
                        }
                    }
                } elseif ($future_deal) {

                    if ($future_deal->user_discount) {
                        $oUser->saldo = (float) $oUser->saldo - (float) $future_deal->user_discount;
                        $oUser->save();
                    }

                    $fd_exists = FutureDeal::where('deal_id', $future_deal->deal_id)->where('status', 'purchased')->where('user_id', $future_deal->user_id)->first();

                    if ($fd_exists) {

                        $person = $future_deal->persons;

                        $future_deal->delete();

                        $future_deal = FutureDeal::find($fd_exists->id);
                        $future_deal->persons = $person + $fd_exists->persons;
                        $future_deal->persons_remain = $person + $fd_exists->persons;
                        $future_deal->save();
                    } else {
                        $future_deal->status = 'purchased';
                        $future_deal->save();
                    }

                    if ($future_deal->deal_id) {
                        $deal = DB::table('reservations_options')->where('id', '=', $future_deal->deal_id)->first();
                    }
                    $persons = $future_deal->persons;
                    $link = '<a href = "' . URL::to('account/future-deals') . '" target="_blank">Klik hier</a>';
                     Alert::success('U heeft succesvol '. $persons.'x de deal: ' . $deal->name . ' in uw account. 
                        <br /><br /><span class="text-center"><small>* Deze zijn pas geldig na reservering vanuit uw account. </small></span> 
                        <br /><br /> ' . $link . ' als u direct een reservering wilt maken. <br /><br />
                        ' . '<span class=\'addthis_sharing_toolbox\'></span>', 
                        'Bedankt ' . $oUser->name . $oUser->name)->html()->persistent('Sluiten');
                    // $link = '<a href = "' . URL::to('account/future-deals') . '" target="_blank">Klik hier</a>';
                    // Alert::success('U heeft succesvol ' . $persons . 'x de deal: ' . $deal->name . ' gekocht voor een prijs van &euro;' . $future_deal->deal_price . ' <br /><br /> ' . $link . ' als u direct een reservering wilt maken. <br /><br />' . '<span class=\'addthis_sharing_toolbox\'></span>', 'Bedankt ' . $oUser->name
                    // )->html()->persistent('Sluiten');
                    $company = Company::find($deal->company_id);
                    return Redirect::to('restaurant/' . $company->slug);
                } else {
                    $oUser->saldo += $userPayments['amount'];
                    $oUser->save();

                    $mailtemplate = new MailTemplate();
                    $mailtemplate->sendMailSite(array(
                        'email' => $oUser->email,
                        'template_id' => 'saldo_charge',
                        'replacements' => array(
                            '%name%' => $oUser->name,
                            '%email%' => $oUser->email,
                            '%euro%' => $userPayments['amount']
                        )
                    ));
                }
            }

            if ($request->has('voordeelpas')) {
                return Redirect::to('voordeelpas/buy/direct');
            } else {
                Alert::success('U heeft succesvol uw saldo opgewaardeerd.')->persistent('Sluiten');
                return Redirect::to('account/reservations/saldo');
            }
        } elseif ($payment->status == 'cancelled') {
            Alert::error(
                            'U heeft de transactie geannuleerd'
                    )
                    ->persistent('Sluiten')
            ;

            return Redirect::to('payments/charge');
        } else {
            Alert::error(
                            'Er is een fout opgetreden, probeert u het alstublieft opnieuw'
                    )
                    ->persistent('Sluiten')
            ;

            return Redirect::to('payments/charge');
        }
    }

    public function charge(Request $request) {
        if ($request->input('buy') == 'voordeelpas') {
            $error = 'Uw saldo is te laag om een voordeelpas te kopen. Waardeer uw saldo op om verder te gaan met het aanschaffen van een voordeelpas.';
            if ($request->input('pay')) {
                $restAmount = (Sentinel::getUser()->saldo < $request->input('pay') ? ($request->input('pay') - Sentinel::getUser()->saldo) : $request->input('pay'));
            }else{
                $restAmount = (Sentinel::getUser()->saldo < '14.95' ? (14.95 - Sentinel::getUser()->saldo) : 14.95);
            }
        }

        return view('pages/payments/charge', array(
            'error' => isset($error) ? $error : '',
            'restAmount' => (isset($restAmount) ? $restAmount : ($request->has('min') ? $request->input('min') : ''))
        ));
    }

    public function invoiceToPayment($invoicenumber) {
        $invoice = Invoice::select(
                        'invoices.id', 'invoices.invoice_number', 'invoices.start_date', 'invoices.products', 'invoices.type', 'invoices.debit_credit', 'invoices.total_persons as totalPersons', 'invoices.total_saldo as totalSaldo', 'companies.name as companyName', 'companies.kvk as companyKVK', 'companies.address as companyAddress', 'companies.city as companyCity', 'companies.email as companyEmail', 'companies.btw as companyBTW', 'companies.financial_iban as companyFinancialIban', 'companies.financial_iban_tnv as companyFinancialIbantnv', 'companies.zipcode as companyZipcode'
                )
                ->where('paid', '=', 0)
                ->leftJoin('companies', 'companies.id', '=', 'invoices.company_id')
        ;

        if (Sentinel::inRole('admin') == FALSE) {
            $invoice = $invoice->where('companies.user_id', '=', Sentinel::getUser()->id);
        }

        $invoice = $invoice->where('invoices.invoice_number', $invoicenumber)->first();

        if (count($invoice) == 1) {
            // Products
            $productsArray = array();

            if (isset(json_decode($invoice->products, true)[0])) {
                $productsArray = json_decode($invoice->products);
            } else {
                array_push($productsArray, (object) json_decode($invoice->products));
            }

            $totalTax = 0;
            $totalPriceExTax = 0;
            $totalPrice = 0;

            foreach ($productsArray as $product) {
                if (isset($product->amount, $product->price, $product->tax)) {
                    $totalTax = $product->tax;
                    $totalPriceExTax += $product->amount * $product->price;
                    $totalPrice += $product->amount * $product->price * ($product->tax / 100 + 1);
                }
            }

            return view('pages/payments/invoice', array(
                'totalPriceProducts' => $totalPrice,
                'invoice' => $invoice
            ));
        } else {
            alert()->error('', 'Deze factuur met factuurnummer #' . $invoicenumber . ' is al betaald of bestaat niet')->persistent('Sluiten');
            return Redirect::to('/');
        }
    }

    public function directInvoiceToPayment(Request $request) {
        $invoice = Invoice::select(
                        'invoices.id', 'invoices.invoice_number', 'invoices.start_date', 'invoices.products', 'invoices.type', 'invoices.debit_credit', 'invoices.total_persons as totalPersons', 'invoices.total_saldo as totalSaldo', 'companies.name as companyName', 'companies.kvk as companyKVK', 'companies.address as companyAddress', 'companies.city as companyCity', 'companies.email as companyEmail', 'companies.btw as companyBTW', 'companies.financial_iban as companyFinancialIban', 'companies.financial_iban_tnv as companyFinancialIbantnv', 'companies.zipcode as companyZipcode'
                )
                ->where('paid', '=', 0)
                ->leftJoin('companies', 'companies.id', '=', 'invoices.company_id')
        ;

        if (Sentinel::inRole('admin') == FALSE) {
            $invoice = $invoice->where('companies.user_id', '=', Sentinel::getUser()->id);
        }

        $invoice = $invoice->where('invoices.invoice_number', $request->input('invoicenumber'))->first();

        if (count($invoice) == 1) {
            switch ($invoice->type) {
                case 'products':
                    $productsArray = array();

                    if (isset(json_decode($invoice->products, true)[0])) {
                        $productsArray = json_decode($invoice->products);
                    } else {
                        array_push($productsArray, (object) json_decode($invoice->products));
                    }

                    $totalTax = 0;
                    $totalPriceExTax = 0;
                    $totalPrice = 0;

                    foreach ($productsArray as $product) {
                        if (isset($product->amount, $product->price, $product->tax)) {
                            $totalTax = $product->tax;
                            $totalPriceExTax += $product->amount * $product->price;
                            $totalPrice += $product->amount * $product->price * ($product->tax / 100 + 1);
                        }
                    }
                    break;

                case 'reservation':
                    $totalPrice = ($invoice->totalPersons * 1 * 1.21);
                    break;
            }

            $payment = $this->mollie->payments->create(array(
                'amount' => $totalPrice,
                'description' => 'Factuurnummer: ' . $invoice->invoice_number,
                'redirectUrl' => URL::to('payment/success/invoice')
            ));

            $oPayment = new Payment();
            $oPayment->mollie_id = $payment->id;
            $oPayment->user_id = Sentinel::getUser()->id;
            $oPayment->status = $payment->status;
            $oPayment->amount = $totalPrice;
            $oPayment->type = 'invoice_' . $invoice->invoice_number;
            $oPayment->payment_type = 'ideal';
            $oPayment->save();

            return Redirect::to($payment->links->paymentUrl);
        } else {
            return Redirect::to('/');
        }
    }
    
    public function giftcode(){
        return view('pages/payments/giftcard');
    }
    public function paygiftcard(Request $request){
        $this->validate($request, [
            'giftcard' => 'required'
        ]);
        $code = Giftcard::where(['code' => $request->giftcard])->where('is_active', 1)->whereRaw('used_no < max_usage')->first();
        if ($code) {
            $oPayment = new Payment();
            $oPayment->mollie_id = "";
            $oPayment->user_id = Sentinel::getUser()->id;
            $oPayment->status = "received";
            $oPayment->amount = $code->amount;
            $oPayment->type = 'Cadeaubon voordeel';
            $oPayment->payment_type = 'giftcard';
            if($oPayment->save()){
                /*update user balance*/
                $balance = Sentinel::getUser()->saldo + $code->amount;
                $oUser = Sentinel::getUserRepository()->findById(Sentinel::getUser()->id);
                $oUser->saldo = $balance;
                $oUser->save();
                /*end update user balance*/
                /*update used count*/
                $code->used_no = $code->used_no + 1;
                $code->save();
                /*update used count*/
                /*entry in giftcard_use*/
                $use = new GiftcardUse();
                $use->giftcard_id  = $code->id;
                $use->user_id  = Sentinel::getUser()->id;
                $use->save();
                /*entry in giftcard_use*/
                Alert::success('U heeft succesvol uw saldo opgewaardeerd.')->persistent('Sluiten');
                return Redirect::to('account/reservations/saldo');
            }else{
                Alert::error('Er is een fout opgetreden, probeert u het alstublieft opnieuw')
                    ->persistent('Sluiten');
                return Redirect::to('payment/giftcode');
            }
            
        }else{
            Alert::error('Ongeldig giftcard nummer')
                    ->persistent('Sluiten');
                return Redirect::to('payment/giftcode');
        }
    }

}
