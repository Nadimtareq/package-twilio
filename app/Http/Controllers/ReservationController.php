<?php

namespace App\Http\Controllers;

use Alert;
use App;
use App\Http\Controllers\Controller;
use App\Http\Requests\ReservationOneRequest;
use App\Http\Requests\ReservationTwoRequest;
use App\Http\Requests\RegisterReservationRequest;
use App\Http\Requests\ReservationEditRequest;
use App\Models\Company;
use App\Models\Page;
use App\Models\Reservation;
use App\Models\TempReservation;
use App\Models\ReservationOption;
use App\Models\Guest;
use App\Models\TemporaryAuth;
use App\Models\Preference;
use App\Models\CompanyReservation;
use App\Models\MailTemplate;
use App\Helpers\MoneyHelper;
use App\Helpers\SmsHelper;
use App\Models\Table;
use App\Helpers\CalendarHelper;
use Activation;
use Sentinel;
use Reminder;
use Redirect;
use Mail;
use Config;
use Carbon\Carbon;
use DB;
use URL;
use Illuminate\Http\Request;
use DateTime;

class ReservationController extends Controller {

    public function __construct() {
        setlocale(LC_ALL, 'nl_NL.ISO8859-1');
        setlocale(LC_TIME, 'nl_NL.ISO8859-1');
        setlocale(LC_TIME, 'Dutch');
    }

    public function reservationStepOne(ReservationOneRequest $request, $slug) {
        $this->validate($request, []);

        $data = array(
            'date' => date('Ymd', strtotime($request->input('date'))),
            'time' => date('Hi', strtotime($request->input('time'))),
            'persons' => $request->input('persons'),
            'deal' => $request->input('deal'),
            'iframe' => $request->input('iframe'),
        );
        return Redirect::to('restaurant/reservation/' . $slug . '?' . http_build_query($data));
    }

    public function reservationStepTwo(Request $request, $slug) {

        $time = date('H:i', strtotime($request->input('time')));
        $date = date('Y-m-d', strtotime($request->input('date')));
        $deal = NULL;
        $company = Company::with('media')->select(
                        'id', 'slug', 'name', 'kitchens', 'days', 'discount',
                        'preferences', 'allergies',
                        DB::raw('(SELECT count(id) FROM reservations WHERE user_id = ' . (Sentinel::check()
                                            ? Sentinel::getUser()->id : 'NULL') . ' AND company_id = companies.id AND newsletter_company = 1) as newsletter'),
                        DB::raw('(SELECT comment FROM reservations WHERE user_id = ' . (Sentinel::check()
                                            ? Sentinel::getUser()->id : 'NULL') . ' AND company_id = companies.id ORDER BY created_at desc LIMIT 1) as lastComment')
                )
                ->where('slug', '=', $slug)
                ->where('no_show', '=', 0)
                ->first()
        ;

        if ($company) {
            $mediaItems = $company->getMedia('default');
            // get the available reservation times
            $reservationTimes = CompanyReservation::getReservationTimesArray(
                            array(
                                'company_id' => array($company->id),
                                'date' => $date,
                                'selectPersons' => $request->input('persons')
                            )
            );

            // get the available reservation options
            $reservationsOptions = Company::getReservationOption(
                            $company->id, $request->input('date'),
                            $request->input('time')
            );
            if ($request->input('deal')) {
                $deal = ReservationOption::where('id', $request->input('deal'))->first();
                if (!$deal) {
                    alert()->error('',
                            'Het is niet mogelijk om op dit tijdstip te reserveren of er zijn geen plaatsen beschikbaar.')->html()->persistent('Sluiten');

                    return Redirect::to('/');
                }
            }

            if (isset($reservationTimes[$time])) {

                return view('pages/reservation',
                        [
                    'discountMessage' => Company::getDiscountMessage($company->days,
                            $company->discount, $company->discount_comment),
                    'company' => $company,
                    'deal' => $deal,
                    'mediaItems' => $mediaItems,
                    'reservationOptions' => $reservationsOptions,
                    'user' => Sentinel::getUser(),
                    'iframe' => $request->input('iframe')
                ]);
            } else {
                alert()->error('',
                        'Het is niet mogelijk om op dit tijdstip te reserveren of er zijn geen plaatsen beschikbaar.')->html()->persistent('Sluiten');

                return Redirect::to($request->input('iframe') == 1 ? 'widget/calendar/restaurant/' . $slug
                                            : 'restaurant/' . $slug);
            }
        } else {
            alert()->error('',
                    'Dit bedrijf bestaat niet of is tijdelijk niet beschikbaar.')->html()->persistent('Sluiten');

            return Redirect::to($request->input('iframe') == 1 ? 'widget/calendar/restaurant/' . $slug
                                        : '/');
        }
    }

    public function reservationAction(ReservationTwoRequest $request, $slug) {
       

        setlocale(LC_ALL, 'nl_NL', 'Dutch');
        $this->validate($request, []);
        $enough_balance = true;
        $rest_amount = 0;
        $company = Company::where('slug', $slug)
                ->where('no_show', '=', 0)
                ->first();

        if ($company) {
            $time = date('H:i', strtotime($request->input('time')));
            $date = date('Y-m-d', strtotime($request->input('date')));

            $reservationTimes = CompanyReservation::getReservationTimesArray(
                            array(
                                'company_id' => array($company->id),
                                'date' => $date,
                                'selectPersons' => $request->input('persons'),
                                'groupReservations' => ($request->has('group_reservation')
                                            ? 1 : NULL)
                            )
            );

            

            if (isset($reservationTimes[$time])) {



                // Create only a new user when the user is not logged in, if you use an other email address
                if (Sentinel::check()) {
                    $user = Sentinel::getUser();
                } else {
                    $user = Sentinel::findByCredentials(array(
                                'login' => $request->input('email')
                    ));
                }
                $loginAfter = 0;
                $deal_saldo = (float) MoneyHelper::getAmount($request->input('saldo'));

                if (!$user) {
                    $loginAfter = 1;

                    $randomPassword = str_random(20);

                    $credentials = array(
                        'email' => $request->input('email'),
                        'password' => $randomPassword
                    );

                    $user = Sentinel::registerAndActivate($credentials);
                    $user->name = $request->input('name');
                    $user->expire_code = str_random(64);
                    $user->source = app('request')->cookie('source');
                    $user->saldo = 0;
                    $enough_balance = false;
                    $rest_amount = $deal_saldo;
                } else {
//                    if ($request->has('saldo')) {
                    $user_saldo = (float) MoneyHelper::getAmount($user->saldo);
                    if ($deal_saldo > $user_saldo) {
                        $enough_balance = false;
                        $rest_amount = $deal_saldo - $user_saldo;
                    } else {
                        $user->saldo = $user_saldo - $deal_saldo;
                    }
//                    }
                }
                $allergies = json_decode($user->allergies, true);
                $preferences = json_decode($user->preferences, true);

                if ($request->has('preferences')) {
                    $preferences = is_array($preferences) ? $preferences : array();
                    foreach ($request->input('preferences') as $key =>
                                $preference) {
                        array_push($preferences, $preference);
                    }
                }

                if ($request->has('allergies')) {
                    $allergies = is_array($allergies) ? $allergies : array();

                    foreach ($request->input('allergies') as $key => $allergy) {
                        array_push($allergies, $allergy);
                    }
                }

                $user->newsletter = $request->input('newsletter') == 1 ? 1 : 0;

                if ($allergies != null) {
                    $user->allergies = json_encode(array_values(array_unique(array_filter($allergies))));
                }

                if ($preferences != null) {
                    $user->preferences = json_encode(array_values(array_unique(array_filter($preferences))));
                }

                $user->terms_active = 1;
                $user->phone = $request->input('phone');
                $user->save();

                // Add as guest
                $guest = new Guest();
                $guest->addGuest(array(
                    'user_id' => $user->id,
                    'company_id' => $company->id
                ));


                $persons = $request->input('persons');

                $tblNo = Table::where('comp_id', $company->id)->where('seating',
                                $persons)->where('status', '0')->orderBy('priority',
                                'asc')->take(1)->first();


                if ($tblNo) {
                    $tableNumber = $tblNo['id'];
                } else {
                    $tableNumber = 0;
                }

                $resTime = date('H:i', strtotime($request->input('time'))) . ':00';
                $resDate = date('Y-m-d', strtotime($request->input('date')));

                // Add a reservation
                if ($enough_balance) {
                    $data = new Reservation;
                } else {
                    $data = new TempReservation;
                    $data->rest_pay = $rest_amount;
                }

                $data->date = $resDate;
                $data->time = $resTime;
                $data->persons = $persons;
                $data->company_id = $company->id;
                $data->user_id = $user->id;
                $data->reservation_id = $reservationTimes[$time][$company->id]['reservationId'];
                $data->name = $request->input('name');
                $data->email = $request->input('email');
                $data->phone = $request->input('phone');

                $data->table_nr = $tableNumber;
                
                if ($request->has('reservations_options')) {
                    $data->option_id = $request->input('reservations_options');
                }

                $data->comment = $request->input('comment');
                $data->saldo = $request->has('saldo') ? MoneyHelper::getAmount($request->input('saldo'))
                            : 0;
                $data->newsletter_company = $request->input('newsletter') == '' ? 0
                            : 1;
                $data->allergies = json_encode($request->input('allergies'));
                $data->preferences = json_encode($request->input('preferences'));
                $data->status = $request->input('iframe') == 1 ? ($reservationTimes[$time][$company->id]['isManual'] ==
                        1 ? 'iframe-pending' : 'iframe') : ($reservationTimes[$time][$company->id]['isManual'] ==
                        1 ? 'reserved-pending' : 'reserved');
                $data->save();

                $checkInTime = strtotime($resDate . " " . $resTime);

                $checkOutTime = $checkInTime + ($tblNo['duration'] * 60);
                $release_time = date("Y-m-d H:i:s", $checkOutTime);
                
                if ($tblNo) {
                    $table = Table::find($tblNo['id']);
                    $table->status = '1';
                    $table->release_time = $release_time;
                    $table->save();
                }

                if (!$enough_balance && $rest_amount) {
                    return view('pages/discount/extra-pay',
                            array(
                        'amount' => $rest_amount,
                        'temp_reservation_id' => $data->id
                    ));
                }
                $date = Carbon::create(
                                date('Y', strtotime($request->input('date'))),
                                date('m', strtotime($request->input('date'))),
                                date('d', strtotime($request->input('date'))),
                                0, 0, 0
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

                $mailtemplate = new MailTemplate();

                // Send mail to company owner
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
                        '%allergies%' => ($request->has('allergies') ? implode(",",
                                        json_decode($data->allergies)) : ''),
                        '%preferences%' => ($request->has('preferences') ? implode(",",
                                        json_decode($data->preferences)) : '')
                    )
                ));

                $calendarHelper = new CalendarHelper();
                $calendar = $calendarHelper->displayCalendars(
                        1, 'Reservering bij ' . $company->name,
                        'Reservering voor ' . $company->name . ' op ' . $date->formatLocalized('%A %d %B %Y') . ' om ' . date('H:i',
                                strtotime($request->input('time'))) . ' met ' . $request->input('persons') . ' ' . ($request->input('persons') ==
                        1 ? 'persoon' : 'personen'),
                        ($company->address . ', ' . $company->zipcode . ', ' . $company->city),
                        date('Y-m-d', strtotime($data->date)) . ' ' . date('H:i:s',
                                strtotime($data->time))
                );

                if (Sentinel::check() == FALSE) {
                    $loginAs = Sentinel::findById($user->id);
                    Sentinel::login($user);
                }

                if ($reservationTimes[$time][$company->id]['isManual'] == 1) {
                    Alert::warning(
                            'Uw reservering voor ' . $company->name . ' op ' . $date->formatLocalized('%A %d %B %Y') . ' om ' . date('H:i',
                                    strtotime($request->input('time'))) . ' met ' . $request->input('persons') . ' ' . ($request->input('persons') ==
                            1 ? 'persoon' : 'personen') . ' wordt doorgegeven aan het restaurant, welke contact met u opneemt.<br /><br /> U heeft aangegeven &euro;' . $request->input('saldo') . ' korting op de rekening te willen. Klopt dit niet? <a href=\'' . URL::to('account/reservations') . '\' target=\'_blank\'>Klik hier</a><br /><br /> ' . $calendar . '<br /><br /> <span class=\'addthis_sharing_toolbox\'></span>',
                            'Let op, ' . $request->input('name') . '!'
                    )->html()->persistent('Sluiten');

                    // Send to client
                    $mailtemplate->sendMail(array(
                        'email' => $request->input('email'),
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
                            '%allergies%' => ($request->has('allergies') ? implode(",",
                                            json_decode($data->allergies)) : ''),
                            '%preferences%' => ($request->has('preferences') ? implode(",",
                                            json_decode($data->preferences)) : '')
                        )
                    ));
                } elseif ($request->has('iframe')) {
                    Alert::success(
                        'U heeft succesvol '. $request->input('persons') . ' ' . ($request->input('persons') ==
                            1 ? 'persoon' : 'personen') . ' x de deal: '.$company->name .' in uw account <br><br>
                        * Deze zijn pas geldig na reservering vanuit uw account.','Bedankt ' . $request->input('name') . '!'
                    )->html()->persistent('Sluiten');
                    // Alert::success(
                    //         'Uw reservering voor ' . $company->name . ' op ' . $date->formatLocalized('%A %d %B %Y') . ' om ' . date('H:i',
                    //                 strtotime($request->input('time'))) . ' met ' . $request->input('persons') . ' ' . ($request->input('persons') ==
                    //         1 ? 'persoon' : 'personen') . ' is succesvol geplaatst. <br /><br />' . $calendar . '<br /><br /> <span class=\'addthis_sharing_toolbox\'></span>',
                    //         'Bedankt ' . $request->input('name') . '!'
                    // )->html()->persistent("Sluit");
                    // Send mail to user
                    $mailtemplate->sendMail(array(
                        'email' => $request->input('email'),
                        'reservation_id' => $data->id,
                        'template_id' => 'new-reservation-client',
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
                            '%discount_comment%' => $company->discount_comment,
                            '%discount%' => isset($discount[0]) ? $discount[0] : '',
                            '%days%' => isset($day) ? $day : '',
                            '%allergies%' => ($request->has('allergies') ? implode(",",
                                            json_decode($data->allergies)) : ''),
                            '%preferences%' => ($request->has('preferences') ? implode(",",
                                            json_decode($data->preferences)) : '')
                        )
                    ));
                } else {
                    Alert::success(
                        'U heeft succesvol '. $request->input('persons') . ' ' . ($request->input('persons') ==
                            1 ? 'persoon' : 'personen') . ' x de deal: '.$company->name .' in uw account <br><br>
                        * Deze zijn pas geldig na reservering vanuit uw account.','Bedankt ' . $request->input('name') . '!'
                    )->html()->persistent('Sluiten');

                    // Alert::success(
                    //         'Uw reservering voor ' . $company->name . ' op ' . $date->formatLocalized('%A %d %B %Y') . ' om ' . date('H:i',
                    //                 strtotime($request->input('time'))) . ' met ' . $request->input('persons') . ' ' . ($request->input('persons') ==
                    //         1 ? 'persoon' : 'personen') . ' is succesvol geplaatst. <br /><br /> U heeft aangegeven &euro;' . $request->input('saldo') . ' korting op de rekening te willen. Klopt dit niet? <a href=\'' . URL::to('account/reservations') . '\' target=\'_blank\'>Klik hier</a><br /><br />' . $calendar . '<br /><br /> <span class=\'addthis_sharing_toolbox\'></span>',
                    //         'Bedankt ' . $request->input('name') . '!'
                    // )->html()->persistent('Sluiten');

                    // Send mail to user
                    $mailtemplate->sendMail(array(
                        'email' => $request->input('email'),
                        'template_id' => 'new-reservation-client',
                        'company_id' => $company->id,
                        'reservation_id' => $data->id,
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
                            '%allergies%' => ($request->has('allergies') ? implode(",",
                                            json_decode($data->allergies)) : ''),
                            '%preferences%' => ($request->has('preferences') ? implode(",",
                                            json_decode($data->preferences)) : '')
                        )
                    ));
                }

                return Redirect::to('restaurant/' . $slug . ($request->has('iframe')
                                            ? '?iframe=1' : ''));
            } else {
                alert()->error('',
                        'Het is niet mogelijk om op dit tijdstip te reserveren of er zijn geen plaatsen beschikbaar.')->html()->persistent('Sluiten');

                return Redirect::to($request->input('iframe') == 1 ? 'widget/calendar/restaurant/' . $slug
                                            : 'restaurant/' . $slug);
            }
        } else {
            alert()->error('',
                    'Dit bedrijf bestaat niet of is tijdelijk niet beschikbaar.')->html()->persistent('Sluiten');

            return Redirect::to($request->input('iframe') == 1 ? 'widget/calendar/restaurant/' . $slug
                                        : '/');
        }
    }

    public function reservationEdit(Request $request, $id) {
        $reservation = Reservation::select(
                        DB::raw(
                                'DATE_SUB(CONCAT(reservations.date, " ", reservations.time), INTERVAL company_reservations.update_before_time MINUTE) as updateBeforeTime'
                        ), 'companies.name as companyName',
                        'companies.allergies as companyAllergies',
                        'companies.preferences as companyPreferences',
                        'companies.user_id as companyOwner', 'reservations.*'
                )
                ->leftJoin('companies', 'reservations.company_id', '=',
                        'companies.id')
                ->leftJoin('company_reservations', 'company_reservations.id',
                        '=', 'reservations.reservation_id')
                ->find($id)
        ;

        if ($reservation) {
            $saldo = $reservation->getMeta('saldo_update');
            if (
                    $request->has('saldo_update') && $saldo != NULL && $saldo->code ==
                    $request->input('saldo_update')
            ) {
                $oldSaldo = $saldo->old_saldo;
                $newSaldo = $saldo->new_saldo;

                if ($oldSaldo > $newSaldo) {
                    // More saldo
                    $amount = $oldSaldo - $newSaldo;

                    $user = Sentinel::findById($reservation->user_id);
                    $user->saldo = $user->saldo + $amount;
                    $user->save();
                } else {
                    // Less saldo
                    $amount = $newSaldo - $oldSaldo;

                    $user = Sentinel::findById($reservation->user_id);
                    $user->saldo = $user->saldo - $amount;
                    $user->save();
                }

                $reservation->saldo = $newSaldo;
                $reservation->save();

                $reservation->deleteMeta('saldo_update');

                alert()->success('', 'Uw spaartegoed is succesvol aangepast.')->html()->persistent('Sluiten');

                return Redirect::to('/');
            } else {
                if (
                        $reservation->user_id == Sentinel::getUser()->id
                        OR $reservation->companyOwner == Sentinel::getUser()->id
                        OR Sentinel::inRole('admin')
                ) {
                    if (date('Y-m-d H:i') < date('Y-m-d H:i',
                                    strtotime($reservation->date . ' ' . $reservation->time))) {
                        if (
                                new DateTime() < new DateTime($reservation->updateBeforeTime)
                                OR $reservation->companyOwner == Sentinel::getUser()->id
                                OR Sentinel::inRole('admin')
                        ) {
                            return view('pages/reservation/edit',
                                    [
                                'reservation' => $reservation,
                                'user' => Sentinel::getUser()
                            ]);
                        } else {
                            Alert::error('Het is niet meer mogelijk om deze reservering te wijzigen.')->html()->persistent('Sluiten');
                            return Redirect::to('account/reservations/edit/' . $id);
                        }
                    } else {
                        Alert::error('Het is niet meer mogelijk om deze reservering te wijzigen.')->html()->persistent('Sluiten');

                        return Redirect::to('account/reservations/edit/' . $id);
                    }
                }
            }
        } else {
            alert()->error('', 'Deze reservering bestaat niet.')->html()->persistent('Sluiten');
            return Redirect::to('account');
        }
    }

    public function reservationEditAction(ReservationTwoRequest $request, $id) {
        setlocale(LC_TIME, 'Dutch');

        $company = Company::where('id', '=', $request->input('company_id'))->first();

        if ($company) {
            $oldTime = date('H:i', strtotime($request->input('old_time')));
            $time = date('H:i', strtotime($request->input('time')));
            $date = date('Y-m-d', strtotime($request->input('date')));

            $reservationTimes = CompanyReservation::getReservationTimesArray(
                            array(
                                'company_id' => array($company->id),
                                'date' => $date,
                                'selectPersons' => $request->input('persons')
                            )
            );

            $reservationCheck = $oldTime != $time ? isset($reservationTimes[$time])
                        : 1;

            if ($reservationCheck == 1) {
                $reservation = Reservation::select(
                                DB::raw('DATE_SUB(CONCAT(reservations.date, " ", reservations.time), INTERVAL company_reservations.update_before_time MINUTE) as updateBeforeTime'),
                                DB::raw('DATE_SUB(CONCAT(reservations.date, " ", reservations.time), INTERVAL company_reservations.cancel_before_time MINUTE) as cancelBeforeTime'),
                                'companies.name as companyName',
                                'company_reservations.cancel_before_time',
                                'companies.allergies as companyAllergies',
                                'companies.preferences as companyPreferences',
                                'companies.user_id as companyOwner',
                                'reservations.*', 'reservations.id as resId'
                        )
                        ->leftJoin('companies', 'reservations.company_id', '=',
                                'companies.id')
                        ->leftJoin('company_reservations',
                                'company_reservations.id', '=',
                                'reservations.reservation_id')
                        ->find($id)
                ;

                if (count($reservation) == 1) {
                    switch ($request->input('type')) {
                        case 'cancel':
                            if (
                                    $reservation->user_id == Sentinel::getUser()->id
                                    OR $reservation->companyOwner == Sentinel::getUser()->id
                                    OR Sentinel::inRole('admin')
                            ) {
                                if (
                                        new DateTime() < new DateTime($reservation->date . '' . $reservation->time) &&
                                        new DateTime() < new DateTime($reservation->cancelBeforeTime) &&
                                        $reservation->is_cancelled == 0
                                ) {
                                    Reservation::cancel($reservation);

                                    Alert::success('Uw reservering is succesvol geannuleerd.')->html()->persistent('Sluiten');
                                } else {
                                    alert()->error('',
                                            'Deze reservering kunt u niet meer annuleren.')->html()->persistent('Sluiten');
                                }

                                if ($request->has('companyPage')) {
                                    return Redirect::to('admin/reservations/clients/' . $company->id . '/' . date('Ymd',
                                                            strtotime($reservation->date)));
                                } else {
                                    return Redirect::to('account/reservations');
                                }
                            } else {
                                alert()->error('',
                                        'Wij konden de opgegeven reservering niet vinden.')->html()->persistent('Sluiten');

                                return Redirect::to($request->has('companyPage')
                                                            ? 'admin/reservations/clients/' . $company->id
                                                            : 'account/reservations');
                            }
                            break;

                        case 'edit':
                            $this->validate($request, []);

                            if (
                                    $reservation->user_id == Sentinel::getUser()->id
                                    OR $reservation->companyOwner == Sentinel::getUser()->id
                                    OR Sentinel::inRole('admin')
                            ) {
                                if (
                                        new DateTime() < new DateTime($reservation->updateBeforeTime)
                                        OR $reservation->companyOwner == Sentinel::getUser()->id
                                        OR Sentinel::inRole('admin')
                                ) {

                                    $userSaldo = Sentinel::findById($reservation->user_id)->saldo;
                                    $oldSaldo = $reservation->saldo;
                                    $newSaldo = $request->input('saldo');

                                    if ($newSaldo > $oldSaldo) {
                                        // More saldo
                                        $amount = $newSaldo - $oldSaldo;

                                        if ($amount > $userSaldo) {
                                            alert()->error('',
                                                    'Het spaartegoed van deze gebruiker is te laag om meer spaartegoed er op te zetten.')->html()->persistent('Sluiten');
                                            return Redirect::to('reservation/edit/' . $reservation->id . '?company_page=1');
                                        }
                                    }

                                    $reservation->date = date('Y-m-d',
                                            strtotime($request->input('date')));
                                    $reservation->time = date('H:i',
                                                    strtotime($request->input('time'))) . ':00';
                                    $reservation->persons = $request->input('persons');
                                    $reservation->name = $request->input('name');
                                    $reservation->email = $request->input('email');
                                    $reservation->phone = $request->input('phone');
                                    $reservation->comment = $request->input('comment');

                                    //Saldo change
                                    if ($reservation->user_id == Sentinel::getUser()->id) {
                                        $reservation->saldo = $request->input('saldo');

                                        $user = Sentinel::findById($reservation->user_id);

                                        if ($request->has('saldo') && $request->input('saldo') >
                                                0 && $request->input('saldo') > $request->input('old_saldo')) {
                                            $user->saldo = MoneyHelper::getAmount($user->saldo) -
                                                    MoneyHelper::getAmount($request->input('saldo'));
                                        }

                                        $user->save();
                                    }

                                    $reservation->newsletter_company = ($request->input('newsletter') ==
                                            '' ? 0 : 1);
                                    $reservation->allergies = json_encode($request->input('allergies'));
                                    $reservation->preferences = json_encode($request->input('preferences'));
                                    $reservation->save();

                                    $mailtemplate = new MailTemplate();
                                    $temporaryAuth = new TemporaryAuth();

                                    if (
                                            $reservation->companyOwner == Sentinel::getUser()->id
                                            OR Sentinel::inRole('admin') && $reservation->user_id !=
                                            Sentinel::getUser()->id && $request->has('saldo') &&
                                            $request->input('saldo') > 0
                                    ) {
                                        $code = str_random(64);
                                        $authLinkUpdate = $temporaryAuth->createCode($reservation->user_id,
                                                'reservation/edit/' . $reservation->id . '?saldo_update=' . $code);

                                        $reservation->deleteMeta('saldo_update');
                                        $reservation->addMeta('saldo_update',
                                                array(
                                            'code' => $code,
                                            'new_saldo' => $request->input('saldo'),
                                            'old_saldo' => $reservation->saldo
                                        ));

                                        // Send to client
                                        $mailtemplate->sendMail(array(
                                            'email' => $reservation->email,
                                            'template_id' => 'saldo-update',
                                            'company_id' => $company->id,
                                            'replacements' => array(
                                                '%url%' => url('auth/set/' . $authLinkUpdate),
                                                '%name%' => $reservation->name,
                                                '%saldo%' => $reservation->saldo,
                                                '%old_saldo%' => $reservation->saldo,
                                                '%new_saldo%' => $request->input('saldo'),
                                                '%phone%' => $reservation->phone,
                                                '%email%' => $reservation->email,
                                                '%date%' => date('d-m-Y',
                                                        strtotime($reservation->date)),
                                                '%time%' => date('H:i',
                                                        strtotime($reservation->time)),
                                                '%persons%' => $reservation->persons,
                                                '%comment%' => $reservation->comment,
                                            )
                                        ));
                                    }

                                    if ($request->has('companyPage')) {
                                        // Send to client
                                        $mailtemplate->sendMail(array(
                                            'email' => $reservation->email,
                                            'template_id' => 'updated-reservation-company-client',
                                            'company_id' => $company->id,
                                            'replacements' => array(
                                                '%name%' => $reservation->name,
                                                '%saldo%' => $reservation->saldo,
                                                '%phone%' => $reservation->phone,
                                                '%email%' => $reservation->email,
                                                '%date%' => date('d-m-Y',
                                                        strtotime($reservation->date)),
                                                '%time%' => date('H:i',
                                                        strtotime($reservation->time)),
                                                '%persons%' => $reservation->persons,
                                                '%comment%' => $reservation->comment,
                                                '%allergies%' => ($request->has('allergies')
                                                            ? implode(",",
                                                                json_decode($reservation->allergies))
                                                            : ''),
                                                '%preferences%' => ($request->has('preferences')
                                                            ? implode(",",
                                                                json_decode($reservation->preferences))
                                                            : '')
                                            )
                                        ));
                                    } else {
                                        // Send to client
                                        $mailtemplate->sendMail(array(
                                            'email' => $reservation->email,
                                            'template_id' => 'updated-reservation-client',
                                            'company_id' => $company->id,
                                            'replacements' => array(
                                                '%name%' => $reservation->name,
                                                '%cname%' => $company->contact_name,
                                                '%saldo%' => $reservation->saldo,
                                                '%phone%' => $reservation->phone,
                                                '%email%' => $reservation->email,
                                                '%date%' => date('d-m-Y',
                                                        strtotime($reservation->date)),
                                                '%time%' => date('H:i',
                                                        strtotime($reservation->time)),
                                                '%persons%' => $reservation->persons,
                                                '%comment%' => $reservation->comment,
                                                '%allergies%' => ($request->has('allergies')
                                                            ? implode(",",
                                                                json_decode($reservation->allergies))
                                                            : ''),
                                                '%preferences%' => ($request->has('preferences')
                                                            ? implode(",",
                                                                json_decode($reservation->preferences))
                                                            : '')
                                            )
                                        ));

                                        // Send to owner
                                        $mailtemplate->sendMail(array(
                                            'email' => $company->email,
                                            'template_id' => 'updated-reservation-client-company',
                                            'company_id' => $company->id,
                                            'replacements' => array(
                                                '%name%' => $reservation->name,
                                                '%cname%' => $company->contact_name,
                                                '%saldo%' => $reservation->saldo,
                                                '%phone%' => $reservation->phone,
                                                '%email%' => $reservation->email,
                                                '%date%' => date('d-m-Y',
                                                        strtotime($reservation->date)),
                                                '%time%' => date('H:i',
                                                        strtotime($reservation->time)),
                                                '%persons%' => $reservation->persons,
                                                '%comment%' => $reservation->comment,
                                                '%allergies%' => ($request->has('allergies')
                                                            ? implode(",",
                                                                json_decode($reservation->allergies))
                                                            : ''),
                                                '%preferences%' => ($request->has('preferences')
                                                            ? implode(",",
                                                                json_decode($reservation->preferences))
                                                            : '')
                                            )
                                        ));
                                    }

                                    Alert::success('Uw reservering is succesvol gewijzigd.')->html()->persistent('Sluiten');
                                } else {
                                    alert()->error('',
                                            'Deze reservering kunt u niet meer wijzigen.')->html()->persistent('Sluiten');
                                }

                                if ($request->has('companyPage')) {
                                    return Redirect::to('admin/reservations/clients/' . $company->id . '/' . date('Ymd',
                                                            strtotime($reservation->date)));
                                } else {
                                    return Redirect::to('reservation/edit/' . $reservation->id);
                                }
                            } else {
                                alert()->error('',
                                        'Wij konden de opgegeven reservering niet vinden.')->html()->persistent('Sluiten');

                                return Redirect::to($request->has('companyPage')
                                                            ? 'admin/reservations/clients/' . $company->id
                                                            : 'account/reservations');
                            }
                            break;
                    }
                } else {
                    alert()->error('',
                            'Wij konden de opgegeven reservering niet vinden.')->html()->persistent('Sluiten');

                    return Redirect::to($request->has('companyPage') ? 'admin/reservations/clients/' . $company->id
                                                : 'account/reservations');
                }
            } else {
                alert()->error('',
                        'Het is niet mogelijk om op dit tijdstip te reserveren of er zijn geen plaatsen beschikbaar.')->html()->persistent('Sluiten');

                return Redirect::to($request->has('companyPage') ? 'admin/reservations/clients/' . $company->id
                                            : 'account/reservations');
            }
        } else {
            App::abort(404);
        }
    }

}
