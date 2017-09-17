<?php

namespace App\Http\Controllers;

use Alert;
use App;
use App\Http\Controllers\Controller;
use App\Http\Requests\RestaurantRequest;
use App\Http\Requests\ReviewRequest;
use App\Models\Company;
use App\Models\CompanyReservation;
use App\Models\MailTemplate;
use App\Models\Preference;
use App\Models\ReservationOption;
use App\Models\Review;
use App\Models\News;
use App\Helpers\CalendarHelper;
use Sentinel;
use Redirect;
use Carbon\Carbon;
use Mail;
use URL;
use Illuminate\Http\Request;
use Setting;
use Config;
use Validator;
use App\Helpers\MoneyHelper;
use App\Models\FutureDeal;

class RestaurantController extends Controller {

    public function index($slug, Request $request) {
        $company = Company::with('media')
                ->where('no_show', 0)
                ->where('slug', $slug)
                ->first();

        if ($company) {
            // Add Click
            $companyClick = new Company;
            $companyClick->addClick($request->getClientIp(), $company->id);

            $mediaItems = $company->getMedia('default');

            $news = News::with('media')
                    ->where('company_id', $company->id)
                    ->where('is_published', 1)
                    ->paginate(15);

            $reviews = Review::select('reviews.*', 'users.name')
                    ->leftJoin('users', 'users.id', '=', 'reviews.user_id')
                    ->where('reviews.company_id', $company->id)
                    ->where('reviews.is_approved', 1)
                    ->get();

            $companyRegioArray = json_decode($company->regio);

            $companies = Company::where('name', '!=', $company->name)
                    ->where(function ($query) use($company, $companyRegioArray) {
                        if (is_array($companyRegioArray)) {
                            foreach ($companyRegioArray as $key => $regio) {
                                if ($key == 0) {
                                    $query->where(function ($subQuery) use($regio) {
                                        $subQuery
                                        ->where('regio', 'REGEXP',
                                                '"[[:<:]]' . $regio . '[[:>:]]"')
                                        ->orWhere('regio', '=', $regio)
                                        ;
                                    });
                                } else {
                                    $query->orWhere(function ($subQuery) use($regio) {
                                        $subQuery
                                        ->where('regio', 'REGEXP',
                                                '"[[:<:]]' . $regio . '[[:>:]]"')
                                        ->orWhere('regio', '=', $regio)
                                        ;
                                    });
                                }
                            }
                        } else {
                            $query
                            ->where('regio', 'REGEXP',
                                    '"[[:<:]]' . $company->regio . '[[:>:]]"')
                            ->orWhere('regio', '=', $company->regio)
                            ;
                        }
                    })
                    ->where('no_show', '=', 0)
                    ->with('media')
                    ->take(20)
                    ->get();

            $companyId = array();

            foreach ($companies as $key => $companyFetch) {
                $companyId[] = $companyFetch->id;
            }

            $reservationTimesArray = CompanyReservation::getReservationTimesArray(
                            array(
                                'company_id' => $companyId,
                                'date' => date('Y-m-d'),
                                'selectPersons' => NULL
                            )
            );

            $tomorrowArray = CompanyReservation::getReservationTimesArray(
                            array(
                                'company_id' => $companyId,
                                'date' => date('Y-m-d', strtotime('+1 days')),
                                'selectPersons' => NULL
                            )
            );
            $deals = ReservationOption::where('company_id', $company->id)
                    ->where('date_from', '<=', date('Y-m-d H:i:s'))
                    ->where('date_to', '>=', date('Y-m-d H:i:s'))
                    ->get();
            $disabled = array();

            $preferences = Preference::getPreferences();

            $attributes = [
                'data-theme' => 'light',
                'data-type' => 'audio',
            ];

            return view('pages/restaurant',
                    [
                'attributes' => $attributes,
                'companies' => $companies,
                'preferences' => $preferences,
                'company' => $company,
                'media' => $mediaItems,
                'deals' => $deals,
                'news' => $news,
                'iframe' => $request->has('iframe'),
                'reservationTimesArray' => (isset($reservationTimesArray) ? $reservationTimesArray
                            : array()),
                'tomorrowArray' => (isset($tomorrowArray) ? $tomorrowArray : array()),
                'reviews' => $reviews,
                'reviewModel' => new Review,
                'times' => CompanyReservation::getAllTimes(),
                'paginationQueryString' => $request->query(),
                'disabled' => $disabled,
                'user' => Sentinel::getUser()
            ]);
        } else {

            App::abort(404);
        }
    }

    public function landingpage($slug) {
        $company = Company::where('no_show', 0)
                ->where('slug', $slug)
                ->first()
        ;

        if ($company) {
            $websiteSettings = json_decode(json_encode(Setting::get('website')),
                    true);

            return view('pages/restaurant/landingpage',
                    [
                'websiteSettings' => $websiteSettings,
                'company' => $company,
                'restaurantUrl' => URL::to('restaurant/' . $company['slug'] . '?open_popup_res=1'),
            ]);
        } else {
            App::abort(404);
        }
    }

    public function contact($slug, RestaurantRequest $request) {
        $company = Company::where('slug', $slug)
                ->where('no_show', 0)
                ->first()
        ;

        if ($company) {
            $this->validate($request, []);

            $request->session()->flash('contact', 1);
            $request->session()->flash('success_message',
                    'Uw bericht is succesvol verzonden.  Wij hopen u zo snel mogelijk antwoord te kunnen geven.');

            $data = array(
                'request' => $request,
                'company' => $company
            );

            Mail::send('emails.contact', $data,
                    function ($message) use ($company, $request) {
                $message->to((trim($company->contact_email) == '' ? $company->email
                                    : $company->contact_email))->subject($request->input('subject'));
            });

            return Redirect::to('restaurant/' . $slug);
        } else {
            App::abort(404);
        }
    }

    public function reviewsAction(ReviewRequest $request, $slug) {

        $deal_id = $request->input('dealId');

        $company = Company::where('slug', $slug)
                ->where('no_show', 0)
                ->first();
        if ($company) {
            $this->validate($request, []);

            $data = new Review;
            $data->content = $request->input('content');
            $data->food = $request->input('food');
            $data->service = $request->input('service');
            $data->decor = $request->input('decor');
            $data->company_id = $company->id;
            $data->deal_id = $deal_id;
            $data->user_id = Sentinel::getUser()->id;
            $data->save();

            $successMessage = 'Voor het plaatsen van uw feedback. Wij waarderen uw mening. U heeft ' . $company->name . ' beoordeeld met <br /><br />
                                 <strong>Eten</strong><br />
                                 <span class=\'ui star disabled no-rating rating\' data-rating=\'' . $request->input('food') . '\'></span><br /><br />
                                 <strong>Service</strong><br />
                                 <span class=\'ui star disabled no-rating rating\' data-rating=\'' . $request->input('service') . '\'></span><br /><br />
                                 <strong>Decor</strong><br />
                                 <span class=\'ui star disabled no-rating rating\' data-rating=\'' . $request->input('decor') . '\'></span><br /><br />
                                 Klopt dit niet? <a href=\'' . url('account/reviews/edit/' . $data->id) . '\'>Klik hier om uw recensie aan te passen.</a>';

            Alert::success(preg_replace('/[\n\r]/', '', $successMessage),
                    'Bedankt')->html()->persistent('Sluiten');

            $mailtemplate = new MailTemplate();

            $mailtemplate->sendMail(array(
                'email' => Sentinel::getUser()->email,
                'template_id' => 'new-review-client',
                'company_id' => $company->id,
                'replacements' => array(
                    '%name%' => Sentinel::getUser()->name,
                    '%saldo%' => '',
                    '%phone%' => Sentinel::getUser()->phone,
                    '%email%' => Sentinel::getUser()->email,
                    '%date%' => date('d-m-Y', strtotime($data->date)),
                    '%time%' => date('H:i', strtotime($data->time)),
                    '%persons%' => '',
                    '%comment%' => '',
                    '%allergies%' => '',
                    '%preferences%' => ''
                )
            ));

            $mailtemplate->sendMail(array(
                'email' => $company->email,
                'template_id' => 'new-review-company',
                'company_id' => $company->id,
                'replacements' => array(
                    '%name%' => Sentinel::getUser()->name,
                    '%cname%' => $company->contact_name,
                    '%saldo%' => '',
                    '%phone%' => Sentinel::getUser()->phone,
                    '%email%' => Sentinel::getUser()->email,
                    '%date%' => date('d-m-Y', strtotime($data->date)),
                    '%time%' => date('H:i', strtotime($data->time)),
                    '%persons%' => '',
                    '%comment%' => '',
                    '%allergies%' => '',
                    '%preferences%' => ''
                )
            ));
            return redirect("restaurant/$slug?deal=$deal_id");
        } else {
            App::abort(404);
        }
    }

    public function widgetCalendar($slug) {
        $company = Company::where('slug', $slug)
                ->where('no_show', 0)
                ->first()
        ;

        if ($company) {
            $reservationTimesArray = CompanyReservation::getReservationTimesArray(
                            array(
                                'company_id' => array($company->id),
                                'date' => date('Y-m-d'),
                                'selectPersons' => NULL
                            )
            );

            return view('pages/restaurant/widgets/calendar',
                    [
                'company' => $company,
                'reservationTimesArray' => $reservationTimesArray,
            ]);
        } else {
            if (Sentinel::check() && (Sentinel::inRole('admin') OR Sentinel::inRole('bedrijf'))) {
                return view('pages/restaurant/widgets/error',
                        [
                    'id' => $company->id,
                    'slug' => $slug
                ]);
            }
        }
    }





    public function widgetCalendar2($slug) {
        $company = Company::where('slug', $slug)
            ->where('no_show', 0)
            ->first()
        ;

        if ($company) {
            $reservationTimesArray = CompanyReservation::getReservationDealsArray(
                array(
                    'company_id' => array($company->id),
                    'date' => date('Y-m-d'),
                    'selectPersons' => NULL
                )
            );

            return view('pages/restaurant/widgets/calendar2',
                [
                    'company' => $company,
                    'reservationTimesArray' => $reservationTimesArray,
                ]);
        } else {
            if (Sentinel::check() && (Sentinel::inRole('admin') OR Sentinel::inRole('bedrijf'))) {
                return view('pages/restaurant/widgets/error',
                    [
                        'id' => $company->id,
                        'slug' => $slug
                    ]);
            }
        }
    }

    public function futureDeal($slug, Request $request) {
        setlocale(LC_ALL, 'nl_NL', 'Dutch');
        $preferences = new Preference();
        $regio = $preferences->getRegio();
        
        $rest_amount = 0;

        $mediaItems = NULL;
        $company = Company::with('media')->select(
                        'id', 'slug', 'name', 'kitchens', 'days', 'discount',
                        'preferences', 'allergies','city'
                )
                ->where('slug', '=', $slug)
                ->where('no_show', '=', 0)
                ->first();
        
        if ($company) {
            
            if ($request->input('deal')) {
                $deal = ReservationOption::where('id', $request->input('deal'))->first();
                if ($deal) {
                    $mediaItems = $company->getMedia('default');
                } else {
                    alert()->error('',
                            'Het is niet mogelijk om op dit tijdstip te reserveren of er zijn geen plaatsen beschikbaar.')->html()->persistent('Sluiten');
                    return Redirect::to('/');
                }
            } else {
                alert()->error('',
                        'Het is niet mogelijk om op dit tijdstip te reserveren of er zijn geen plaatsen beschikbaar.')->html()->persistent('Sluiten');
                return Redirect::to('/');
            }
            if ($request->isMethod('post')) {
                if (Sentinel::check()) {
                    $user = Sentinel::getUser();
                } else {
                    $user = Sentinel::findByCredentials(array(
                                'login' => $request->input('email')
                    ));
                }

                $deal_saldo = (float) MoneyHelper::getAmount($request->input('saldo'));
                if (!$user) {

                    $randomPassword = str_random(20);

                    $credentials = array(
                        'email' => $request->input('email'),
                        'password' => $randomPassword
                    );

                    $user = Sentinel::registerAndActivate($credentials);
                    $user->name = $request->input('name');
                    $user->expire_code = str_random(64);
                    $user->saldo = 0;
                    $enough_balance = false;
                    $rest_amount = $deal_saldo;
                } else {
                    $user_saldo = (float) MoneyHelper::getAmount($user->saldo);
                    if ($deal_saldo > $user_saldo) {
                        $enough_balance = false;
                        $rest_amount = $deal_saldo - $user_saldo;
                    } else {
                        $user->saldo = $user_saldo - $deal_saldo;
                    }
                }
            }
            
            return view('pages/restaurant/future-deal',
                    [
                'company' => $company,
                'deal' => $deal,
                'mediaItems' => $mediaItems,
                'userAuth' => Sentinel::check(),
                'userInfo' => Sentinel::getUser(),
                'regio' => $company->city
                
            ]);
        } else {
            App::abort(404);
        }
    }

    public function processFutureDeal($slug, Request $request) {
        $current_date = date("Y-m-d"); // current date
        $future_expire_days = 90;
        setlocale(LC_ALL, 'nl_NL', 'Dutch');
        $deal_id = $request->input('deal');
        $rest_amount = $user_saldo = 0;
        $enough_balance = false;
        $mediaItems = NULL;
        $company = Company::query()
            ->where('slug', '=', $slug)
            ->where('no_show', '=', 0)->first();
        
        

        if ($company) {            
            if ($deal_id) {
                $deal = ReservationOption::where([['id', '=', $deal_id], ['company_id',
                                '=', $company->id]])->first();
                /* echo "<pre>";
                  print_r($deal);exit; */                
                if (!$deal) {
                    alert()->error('',
                            'Het is niet mogelijk om op dit tijdstip te reserveren of er zijn geen plaatsen beschikbaar.')->html()->persistent('Sluiten');
                    return Redirect::to('/');
                }                
            } else {
                alert()->error('',
                        'Het is niet mogelijk om op dit tijdstip te reserveren of er zijn geen plaatsen beschikbaar.')->html()->persistent('Sluiten');
                return Redirect::to('/');
            }       
            
               

            if ($request->isMethod('post')) {
                if (Sentinel::check()) {
                    $user = Sentinel::getUser();
                    $validator = Validator::make($request->all(),
                        [
                            'persons' => 'required',
                            'av' => 'accepted'
                        ], [
                            'persons.required' => 'Het aantal personen moet minimaal 1 persoon zijn',
                            'persons.numeric' => 'Het aantal personen moet numeriek zijn.',
                            'persons.min' => 'Het aantal personen moet minimaal 1 persoon zijn.',
                            'av.accepted' => 'HELAAS, U bent vergeten om de algemene voorwaarden te accepteren',
                        ]);
                } else {
                    $validator = Validator::make($request->all(),
                        [
                            'persons' => 'required',
                            'email' => 'required|email',
                            'name' => 'required',
                            'phone' => 'required|min:10',
                            'av' => 'accepted'
                        ], [
                            'email.required' => 'U bent vergeten om een e-mailadres in te vullen.',
                            'email.email' => 'Uw opgegeven e-mailadres is ongeldig.',
                            'phone.required' => 'U bent vergeten om een telefoonnummer in te vullen.',
                            'phone.min' => 'Uw telefoonnummer is te kort (minimaal 10 cijfers).',
                            'name.required' => 'U bent vergeten om een naam in te vullen.',
                            'persons.required' => 'Het aantal personen moet minimaal 1 persoon zijn',
                            'persons.numeric' => 'Het aantal personen moet numeriek zijn.',
                            'persons.min' => 'Het aantal personen moet minimaal 1 persoon zijn.',
                            'av.accepted' => 'U bent vergeten om de Algemene Voorwaarden te accepteren.',
                        ]);
                    if ($request->input('email')) {
                        $user = Sentinel::findByCredentials(array(
                                    'login' => $request->input('email')
                        ));
                    }
                }
                if ($validator->fails()) {
                    $errors = $validator->errors();
                    $message_str = '';
                    foreach ($errors->all() as $message) {
                        $message_str .= $message;
                        $message_str .= '<br />';
                    }
                    $request->flash();
                    alert()->error($message_str, '&nbsp;')->html()->persistent('Sluiten');
                    return redirect('future-deal/' . $slug . '?deal=' . $deal_id);
                }

                

                $persons = $request->input('persons');

                //$deal_saldo = (float) MoneyHelper::getAmount($request->input('saldo'));
                $deal_saldo = (float) MoneyHelper::getAmount($persons * $deal->price);
                if ($user) {
                    $user_saldo = (float) MoneyHelper::getAmount($user->saldo);
                    if ($deal_saldo > $user_saldo) {
                        $enough_balance = false;
                        $rest_amount = $deal_saldo - $user_saldo;
                    } else {
                        $enough_balance = true;
                        $user->saldo = $user_saldo - $deal_saldo;
                    }
                } elseif ($request->input('email')) {
                    $randomPassword = str_random(20);

                    $credentials = array(
                        'email' => $request->input('email'),
                        'password' => $randomPassword
                    );

                    $user = Sentinel::registerAndActivate($credentials);
                    $user->name = $request->input('name');
                    $user->expire_code = str_random(64);
                    $user->saldo = 0;
                    $enough_balance = false;
                    $rest_amount = $deal_saldo;
                }
                $user->terms_active = 1;
                $user->phone = $request->input('phone');
                if ($request->input('newsletter') == 1) {
                    $user->newsletter = 1;
                }
                // $user->reference_code = session('reference');
                $user->save();

                if (Sentinel::check() == FALSE) {
                    Sentinel::login($user);
                }

                

                $referrals = App\Models\Referral::pendingReferrals(Sentinel::getUser()->id);
                $referral = $referrals->first();
                if($referral){
                    $referral->deals = $referral->deals + $persons;

                    if($referral->deals >= 2){
                        $reference =  $referral->referrer_id;
                        $referral->due = Carbon::now()->addDays(14);
                        $referral->status ="Complete";
                    
                    }elseif($referral->deals ==1){
                        $reference =  $referral->referrer_id;
                        $referral->status ="Partial";
                    
                    }
                    $referral->save();
                }else{
                     $reference = Null;
                }
                 
                 
               // return $referral;

                // $referred = FutureDeal::where('reference_id', session('referer'))->first();
                // $ref_id = !is_null($referred) ? NULL : session('referer');
                // $reference = ($persons >= 2) ? session('referer') != Sentinel::getUser()->id ? $ref_id : NULL : NULL;
                // $reference = ($persons >= 2 || !is_null($referred)) ? session('referer'): NULL;
                if ($enough_balance && !$rest_amount) {

                    $fd_exists = FutureDeal::where('deal_id', $deal_id)->where('status', 'purchased')->where('user_id', $user->id)->first();
                    if ($fd_exists) {
                        $future_deal = FutureDeal::find($fd_exists->id);
                        $future_deal->persons = $persons + $fd_exists->persons;
                        $future_deal->persons_remain = $fd_exists->persons_remain - $persons;
                        $future_deal->reference_id = $reference;
                        $future_deal->save();
                    } else {
                        $future_deal = new FutureDeal();
                        $future_deal->deal_id = $deal_id;
                        $future_deal->user_id = $user->id;
                        $future_deal->persons = $persons;
                        $future_deal->persons_remain = $persons;
                        $future_deal->deal_price = $deal_saldo;
                        $future_deal->deal_base_price = $deal->price;
                        $future_deal->user_discount = $user_saldo;
                        $future_deal->extra_pay = $rest_amount;
                        $future_deal->purchased_date = $current_date;
                        $future_deal->expired_at = date('Y-m-d', strtotime($current_date . ' + ' . $future_expire_days . ' days'));
                        $future_deal->reference_id = $reference;
                        if (!$enough_balance && $rest_amount) {
                            $future_deal->status = "pending";
                        } else {
                            $future_deal->status = "purchased";
                        }

                        $future_deal->save();
                        $request->session()->pull('reference');
                        $request->session()->pull('referer');
                    }
                }else {
                    $future_deal = new FutureDeal();
                    $future_deal->deal_id = $deal_id;
                    $future_deal->user_id = $user->id;
                    $future_deal->persons = $persons;
                    $future_deal->persons_remain = $persons;
                    $future_deal->deal_price = $deal_saldo;
                    $future_deal->deal_base_price = $deal->price;
                    $future_deal->user_discount = $user_saldo;
                    $future_deal->extra_pay = $rest_amount;
                    $future_deal->purchased_date = $current_date;
                    $future_deal->expired_at = date('Y-m-d', strtotime($current_date . ' + ' . $future_expire_days . ' days'));
                    $future_deal->reference_id = $reference;
                    if (!$enough_balance && $rest_amount) {
                        $future_deal->status = "pending";
                    } else {
                        $future_deal->status = "purchased";
                    }

                    $future_deal->save();
                }

                // Update Referral Table
                




                if (!$enough_balance && $rest_amount) {
                    return view('pages/discount/extra-pay',
                            array(
                        'amount' => $rest_amount,
                        'future_deal_id' => $future_deal->id
                    ));
                } else {
                    $mailtemplate = new MailTemplate();
                    $mailtemplate->sendMailSite(array(
                        'email' => Sentinel::getUser()->email,
                        'template_id' => 'reserving',
                        'replacements' => array(
                            '%name%' => Sentinel::getUser()->name,
                            '%email%' => Sentinel::getUser()->email
                        )
                    ));
//                     $mailtemplate->sendMail(array(
//                      'email' => Sentinel::getUser()->email,
//                      'template_id' => 'new-reservation-client',
//                      'company_id' => $company->id,
//                      'persons' => $persons,
//                      'date' => $current_date,   
//                      'replacements' => array(
//                      '%name%' => Sentinel::getUser()->name,
//                      '%saldo%' => '',
//                      '%phone%' => Sentinel::getUser()->phone,
//                      '%email%' => Sentinel::getUser()->email,
//                      '%date%' => date('d-m-Y', strtotime($current_date)),
//                      '%time%' => date('H:i', strtotime($current_date)),
//                      '%persons%' => $persons,
//                      '%comment%' => '',
//                      '%allergies%' => '',
//                      '%preferences%' => '',
//                      )
//                      )); 
                    $deal = ReservationOption::find($future_deal->deal_id);

                    $url = URL::to('/account/future-deals');
                    Alert::success(
                        'U heeft succesvol '.$request->get('persons').'x de deal: ' . $deal->name . ' in uw account. 
                        <br /><br /><span class="text-center"><small>* Deze zijn pas geldig na reservering vanuit uw account. </small></span> <br /><br />'.'<span class=\'addthis_sharing_toolbox\'></span>',
                        // 'U heeft succesvol '.$request->get('persons').'x de deal: ' . $deal->name . ' in uw account. 
                        // <br /><br /><span class="text-center"><small>* Deze zijn pas geldig na reservering vanuit uw account.</small></span> <br /><br /> 
                        // <a href="' . $url . '">Klik hier als u direct een reservering wilt maken. </a> <br /><br />' 
                        // . '<span class=\'addthis_sharing_toolbox\'></span>', 

                        'Bedankt ' . $user->name
                        
                        )->html()->persistent('Sluiten');
                    $company = Company::find($deal->company_id);
                    return Redirect::to('restaurant/' . $company->slug);
                }
            }

            /* return view('pages/restaurant/future-deal', [
              'company' => $company,
              'deal' => $deal,
              'mediaItems' => $mediaItems,
              'userAuth' => Sentinel::check(),
              'userInfo' => Sentinel::getUser(),
              'regio' => $regio['regio']
              ]); */
        } else {
            App::abort(404);
        }
    }


    public function getUnwantedWords(Request $request){
        
        $unwanted = array();
        $words = explode(" ",$request->content);
        if($request->content != ""){
            foreach ($words as $word) {
                $result = CompanyReservation::getUnwantedWords($word);
                if($result){
                    $unwanted[] = $result;
                }
            }
    
        }

//        $unwanted = CompanyReservation::getWords();
//        $result=array_intersect($unwanted,$request->value);
        echo json_encode($unwanted);
//        if(!empty($unwanted)) {
//            return $unwanted;
//        }else{
//            return '';
//        }
    }

}
