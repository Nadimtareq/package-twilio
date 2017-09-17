<?php
namespace App\Http\Controllers;

use Alert;
use App;
use App\Http\Controllers\Controller;
use App\Models\Company;
use App\models\Contact;
use App\Models\Review;
use App\Models\Page;
use App\Models\Reservation;
use App\Models\Affiliate;
use App\Models\Faq;
use App\Models\FaqCategory;
use App\Models\SearchHistory;
use App\Models\Preference;
use App\Models\AffiliateCategory;
use App\Helpers\AffiliateHelper;
use App\Models\CompanyReservation;
use App\Models\MailTemplate;
use App\Models\Barcode;
use App\Models\News;
use App\Helpers\SmsHelper;
use App\Helpers\BrowerHelper;
use Sentinel;
use Reminder;
use Redirect;
use Mail;
use DB;
use URL;
use Illuminate\Http\Request;
use Carbon\Carbon;
use League\Csv\Reader;
use Session;
use Setting;
use Auth;
use App\SmsTemplate;
require_once '../Twilio/autoload.php';
use Twilio\Rest\Client;
use Twilio\Twiml;

class HomeController extends Controller
{
    public function call_no(){

    $sid = 'ACefca5b03d9b63fab47891206984cd7a6';
    $token = '678178d623cb44a3cb21596bb434df35';
    $client = new Client($sid, $token);

    $to_phno=Input::get('phone');
    $country_code = Input::get('c_code');
    $from_phno='+12568264404';

    $call = $client->calls->create(
    $country_code.$to_phno, "+12568264404",
      array("url" => "http://vps137395.vps.ovh.ca/laratech/process.php")//voice message
    );

    // return a JSON response
    return array('message' => 'Call incoming!');

    }
    public function __construct()
    {

       $browser = new BrowerHelper();
       Session::set('browser',$browser->detect()->getInfo());
       $affiliateHelper = new AffiliateHelper();

        $this->user = Sentinel::getUser();

        $this->news  = News::select(
            'news.slug',
            'news.title',
            'news.created_at'
        )
            ->join('companies', 'news.company_id', '=', 'companies.id')
        ;

        if (
            Sentinel::check()
            && trim($this->user->city) != ''
            && $this->user->city != NULL
            && $this->user->city != 'null'
        ) {
            if (is_array(json_decode($this->user->city))) {
                foreach (json_decode($this->user->city) as $option) {
                    $this->news = $this->news
                        ->orWhereRaw('companies.regio REGEXP "[[:<:]]'.$option.'[[:>:]]"')
                        ->orWhere('companies.regio', '=', $option)
                    ;
                }
            }
        }

        $this->news = $this->news->orderBy(
            'news.created_at', 'asc'
        )
            ->where('is_published', 1)
            ->limit(10)
            ->get()
        ;

        # Affiliates
        $this->affiliates = Affiliate::select(
            '*'
        )
            ->where('no_show', 0)
            ->orderBy('clicks', 'desc')
            ->limit(18)
            ->get()
        ;

        foreach ($this->affiliates as $i => $affiliatesFetch) {
            $this->affiliates[$i]['comissions'] = $affiliateHelper->commissionMaxValue($affiliatesFetch->compensations);
        }

    }

    //twillo
    
         private function createMessage($phone, $message)
    {
        $subscriber = SmsTemplate::where('+12568264404', $phone)->first();
        if ($subscriber) {
            return $this->generateOutputMessage($subscriber, $message);
        }

        $subscriber = new SmsTemplate;
        $subscriber->phoneNumber = $phone;
        $subscriber->subscribed  = false;

        $subscriber->save();

        return trans('subscription.thanks');
    }



    public function send_sms(){

    $sid = 'ACefca5b03d9b63fab47891206984cd7a6';
    $token = '678178d623cb44a3cb21596bb434df35';
    $client = new Client($sid, $token);

    $message = Input::get('msg');
    $to_phno=Input::get('phone');
    $country_code = Input::get('c_code');
    $from_phno='+12568264404';

    $client->messages->create($country_code.$to_phno,array('from' => $from_phno,'body' => $message));
    echo $message;

    $subscriber = SmsTemplate::where('phone', $to_phno)->first();
        if ($subscriber) {
            return $this->generateOutputMessage($subscriber, $message);
        }

        $subscriber = new SmsTemplate;
        $subscriber->subject = 'sdss';
        $subscriber->phone  = $to_phno;
        $subscriber->content  = "hh";
        $subscriber->category  = "jj";
        $subscriber->company_id  = '1';
        $subscriber->explanation  = "jj";
        $subscriber->type  = "sms";        
        $subscriber->is_active  = 1;

        $subscriber->save();

        return trans('subscription.thanks');

    }


    public function push_sms(){

    $sid = 'ACefca5b03d9b63fab47891206984cd7a6';
    $token = '678178d623cb44a3cb21596bb434df35';
    $serviceSid = "ISc0854ed64921065bd4ee915045df9de0";

    $to_phno='+91'.Input::get('phone');
    // Initialize the client
    $client = new Client($sid, $token);
    
        // Create a binding
        $binding = $client->notify->v1->services($serviceSid)
            ->bindings->create(
                '00000001', // We recommend using a GUID or other anonymized identifier for Identity.
                'sms',
                $to_phno
            );
        // Create a notification
       $notification = $client->notify->v1->services($serviceSid)
            ->notifications->create([
                "toBinding" => '{"binding_type":"sms", "address":"'.$to_phno.'"}',
                'body' => 'Knok-Knok! This is your first Notify SMS'
            ]);


       echo 'Notification Successfully send'; //

    }
    

    public function multi_sms()
    {

    $sid = 'ACefca5b03d9b63fab47891206984cd7a6';
    $token = '678178d623cb44a3cb21596bb434df35';
    $client = new Client($sid, $token);

    $message = Input::get('msg');
    $multinumber=Input::get('multinumber');
    $from_phno='+12568264404';
    $count_number=count($multinumber);
    $i=0;
    for($i; $i<$count_number; $i++)
    {
        //echo $multinumber[$i];
        $client->messages->create($multinumber[$i],array('from' => $from_phno,'body' => $message));
    
        
    }
    echo $message;


    }


    //twillo end


    public function test()
    {
        return view("test");
    }

    public function deals(Request $request)
    {
        $deals = App\Models\ReservationOption::all();

        // Preferences cities
        $cities = Preference::where('category_id', 9)
            ->where('no_frontpage', 0)
            ->with('media')
        ;

        if ($request->cookie('user_off_regio') != null) {
            $cities = $cities->orderByRaw('id = "'. $request->cookie('user_off_regio').'" desc');
        } else {
            $cities = $cities->orderByRaw('name desc');
        }

        $cities = $cities->get();


        return view('pages/deals', [
            'user' => $this->user,
            'cities' => $cities,
            'deals' => $deals,
        ]);
    }

    public function index(Request $request)
    {
//        $deals = App\Models\ReservationOption::all();
        // Preferences cities
        $cities = Preference::where('category_id', 9)
            ->where('no_frontpage', 0)
            ->with('media')
        ;

        if ($request->cookie('user_off_regio') != null) {
            $cities = $cities->orderByRaw('id = "'. $request->cookie('user_off_regio').'" asc');
        } else {
            $cities = $cities->orderByRaw('id');
        }


        $cities = $cities->get();
        // Companies
        $companies = Company::select(
            'companies.id',
            'companies.name',
            'companies.slug',
            'companies.description',
            'companies.discount',
            'companies.days',
            'companies.kitchens',
            'companies.city'
        );

        if (
            Sentinel::check()
            && $this->user->city != 'null'
            && $this->user->city != NULL
            && $this->user->city != '[""]'
        ) {
            $userCities = json_decode($this->user->city);
            if (is_array($userCities)) {
                foreach ($userCities as $userCity) {
                    //$companies = $companies->orderByRaw('companies.regio REGEXP "[[:<:]]'.$userCity.'[[:>:]]" desc, companies.clicks asc');
                }
            } else {
                $companies = $companies->orderBy('companies.clicks', 'desc');
            }
        } else {
            $companies = $companies->orderBy('companies.clicks', 'desc');
        }

        if ($request->has('no_filter') == FALSE) {
            // Filter Preferences
            if ($request->input('filter') == 1 && $request->has('preference')) {
                $optionCount = count($request->input('preference'));
                foreach ($request->input('preference') as $option) {
                    if (!$request->has('page')) {
                        $preferenceClick = new Preference();
                        $preferenceClick->addClick($option, 1);
                    }

                    if (count($request->input('preference')) >= 2) {
                        //$companies->orWhere('preferences', 'REGEXP', '"([^"]*)'.$option.'([^"]*)"');
                    } else {
                        //$companies->where('preferences', 'REGEXP', '"([^"]*)'.$option.'([^"]*)"');
                    }
                }
            } else  {
                if (
                    $request->has('filter') == FALSE
                    && Sentinel::check()
                    && $this->user->preferences != 'null'
                    && $this->user->preferences != NULL
                    && $this->user->preferences != '[""]'
                ) {
                    $userPreferences = json_decode($this->user->preferences);
                    foreach (json_decode($this->user->preferences) as $option) {
                        if (count($userPreferences) >= 2) {
                            //$companies->orWhere('preferences', 'REGEXP', '"([^"]*)'.$option.'([^"]*)"');
                        } else {
                            //$companies->where('preferences', 'REGEXP', '"([^"]*)'.$option.'([^"]*)"');
                        }
                    }
                }
            }

            // Filter Kitchens
            if ($request->input('filter') == 1 && $request->has('kitchen')) {
                foreach ($request->input('kitchen') as $option) {
                    if (!$request->has('page')) {
                        $preferenceClick = new Preference();
                        $preferenceClick->addClick($option, 2);
                    }

                    if (count($request->input('kitchen')) >= 2) {
                        //$companies->orWhere('kitchens', 'REGEXP', '"([^"]*)'.$option.'([^"]*)"');
                    } else  {
                        //$companies->where('kitchens', 'REGEXP', '"([^"]*)'.$option.'([^"]*)"');
                    }
                }
            } else  {
                if (
                    $request->has('filter') == FALSE
                    && Sentinel::check()
                    && $this->user->kitchens != 'null'
                    && $this->user->kitchens != NULL
                    && $this->user->kitchens != '[""]'
                ) {
                    $userKitchens = json_decode($this->user->kitchens);
                    foreach ($userKitchens as $option) {
                        if (count($userKitchens) >= 2) {
                           // $companies->orWhere('kitchens', 'REGEXP', '"([^"]*)'.$option.'([^"]*)"');
                        } else {
                            //$companies->where('kitchens', 'REGEXP', '"([^"]*)'.$option.'([^"]*)"');
                        }
                    }
                }
            }

            // Filter Allergies
            if ($request->input('filter') == 1 && $request->has('allergies')) {
                foreach ($request->input('allergies') as $option) {
                    if (!$request->has('page')) {
                        $preferenceClick = new Preference();
                        $preferenceClick->addClick($option, 3);
                    }

                    if (count($request->input('allergies')) >= 2) {
                        //$companies->orWhere('allergies', 'REGEXP', '"([^"]*)'.$option.'([^"]*)"');
                    } else {
                        //$companies->where('allergies', 'REGEXP', '"([^"]*)'.$option.'([^"]*)"');
                    }
                }
            } else  {
                if (
                    $request->has('filter') == FALSE
                    && Sentinel::check()
                    && $this->user->allergies != 'null'
                    && $this->user->allergies != NULL
                    && $this->user->allergies != '[""]'
                ) {
                    $userAllergies = json_decode($this->user->allergies);
                    foreach ($userAllergies as $option) {
                        if (count($userAllergies) >= 2) {
                           // $companies->orWhere('allergies', 'REGEXP', '"([^"]*)'.$option.'([^"]*)"');
                        } else  {
                           // $companies->where('allergies', 'REGEXP', '"([^"]*)'.$option.'([^"]*)"');
                        }
                    }
                }
            }

            // Filter Price
            if ($request->input('filter') == 1 && $request->has('price')) {
                foreach ($request->input('price') as $option) {
                    if (!$request->has('page')) {
                        $preferenceClick = new Preference();
                        $preferenceClick->addClick($option, 4);
                    }

                    if (count($request->input('price')) >= 2) {
                        //$companies->orWhere('price', 'REGEXP', '"([^"]*)'.$option.'([^"]*)"');
                    } else  {
                        //$companies->where('price', 'REGEXP', '"([^"]*)'.$option.'([^"]*)"');
                    }
                }
            } else  {
                if (
                    $request->has('filter') == FALSE
                    && Sentinel::check()
                    && $this->user->price != 'null'
                    && $this->user->price != NULL
                    && $this->user->price != '[""]'
                ) {
                    $userPrices = json_decode($this->user->price);
                    foreach ($userPrices as $option) {
                        if (count($userPrices) >= 2) {
                            //$companies->orWhere('price', 'REGEXP', '"([^"]*)'.$option.'([^"]*)"');
                        } else {
                           // $companies->where('price', 'REGEXP', '"([^"]*)'.$option.'([^"]*)"');
                        }
                    }
                }
            }

            // Filter Discount
             if ($request->input('filter') == 1 && $request->has('discount')) {
                $companies = $companies->where(function ($query) use($request) {
                    foreach ($request->input('discount') as $key => $option) {
                        if (!$request->has('page')) {
                            $preferenceClick = new Preference();
                            $preferenceClick->addClick($option, 5);
                        }

                        if ($key == 0) {
                           // $query->where('discount', 'REGEXP', '"([^"]*)'.rawurldecode($option).'([^"]*)"');
                        } else {
                           // $query->orWhere('discount', 'REGEXP', '"([^"]*)'.rawurldecode($option).'([^"]*)"');
                        }
                    }
                });
            } else {
                if (
                    $request->has('filter') == FALSE
                    && Sentinel::check()
                    && $this->user->discount != 'null'
                    && $this->user->discount != NULL
                    && $this->user->discount != '[""]'
                ) {
                    $userDiscounts = json_decode($this->user->discount);

                    $companies = $companies->where(function ($query) use($userDiscounts) {
                        foreach ($userDiscounts as $key => $option) {
                            if ($key == 0) {
                               // $query->where('discount', 'REGEXP', '"([^"]*)'.rawurldecode($option).'([^"]*)"');
                            } else {
                               // $query->orWhere('discount', 'REGEXP', '"([^"]*)'.rawurldecode($option).'([^"]*)"');
                            }
                        }
                    });
                }
            }
        }

        if ($request->has('time') && $request->has('date')) {
            $companies = $companies
                ->join('company_reservations', 'company_reservations.company_id', '=', 'companies.id')
                ->where('company_reservations.date', '=', $request->input('date'));
        }

        $companies = $companies
            ->where('no_show', 0)
            ->with('media')
        ;

        $countCompanies = $companies->count();
        $companies = $companies->paginate($request->input('limit', 15));

        foreach($companies as $company) {
            if($company->ReservationOptions()){
                foreach($company->ReservationOptions()->get() as $deal) {
                    $companyIds[] = $company->id;
                }
            }
        }
        $reservationDate = date('Y-m-d');
        $tomorrowDate = date('Y-m-d', strtotime('+1 days'));

        if (isset($companyIds)) {
            $reservationTimesArray = CompanyReservation::getReservationTimesArray(
                array(
                    'company_id' => $companyIds,
                    'date' => $reservationDate,
                    'selectPersons' => NULL
                )
            );

            $tomorrowArray = CompanyReservation::getReservationTimesArray(
                array(
                    'company_id' => $companyIds,
                    'date' => $tomorrowDate,
                    'selectPersons' => NULL
                )
            );
        }

        if (!$request->has('no_filter') && count($companies) == 0) {
            alert()->error('', 'Er zijn geen zoekresultaten gevonden met uw selectiecriteria.<br /> <br /><small>Klik <a href=\''.URL::to('account').'\'> hier</a> om uw criteria aan te passen.</small>')->html()->persistent('Sluiten');

            return Redirect::to('/?no_filter=1'.($request->has('mobilefilter') ? '&mobilefilter=1' : ''));
        }

        $queryString = $request->query();
        unset($queryString['limit']);

        foreach ($cities as $city) {
            $media = $city->getMedia();


            //Check of file exits with file_exits() against using CURL in app/Helpers/FileHelper.php
            if (isset($media[0]) && file_exists(public_path().$media[0]->getUrl('thumb')))
                    $city->media = url(''.$media[0]->getUrl('thumb'));
            else
                $city->media = url('images/placeholdimage.png');
        }

        return view('pages/home', [
            'user' => $this->user,
            'cities' => $cities,
            'countCompanies' => $countCompanies,
            'companies' => $companies,
            'limit' => $request->input('limit', 15),
            'queryString' => $queryString,
            'news' => $this->news,
            'affiliates' => $this->affiliates,
            'reservationDate' => $reservationDate,
            'tomorrowArray' => (isset($tomorrowArray) ? $tomorrowArray : array()),
            'reservationTimesArray' => (isset($reservationTimesArray) ? $reservationTimesArray : array()),
            'paginationQueryString' => $request->query()
        ]);
    }

    public function indexHome(Request $request)
    {
        $deals = App\Models\ReservationOption::all();

        // Preferences cities
        $cities = Preference::where('category_id', 9)
            ->where('no_frontpage', 0)
            ->with('media')
        ;

        if ($request->cookie('user_off_regio') != null) {
            $cities = $cities->orderByRaw('id = "'. $request->cookie('user_off_regio').'" desc');
        } else {
            $cities = $cities->orderByRaw('name desc');
        }

        $cities = $cities->get();
        // Companies
        $companies = Company::select(
            'companies.id',
            'companies.name',
            'companies.slug',
            'companies.description',
            'companies.discount',
            'companies.days',
            'companies.kitchens',
            'companies.city'
        );

        if (
            Sentinel::check()
            && $this->user->city != 'null'
            && $this->user->city != NULL
            && $this->user->city != '[""]'
        ) {
            $userCities = json_decode($this->user->city);

            if (is_array($userCities)) {
                foreach ($userCities as $userCity) {
                    //return $userCity;
                    $cities = Preference::find($userCity);
                    if($cities){
                        return redirect()->action('HomeController@search',['regio'=> $cities->slug]);
                    }
                    $companies = $companies->orderByRaw('companies.regio REGEXP "[[:<:]]'.$userCity.'[[:>:]]" desc, companies.clicks asc');
                }
            } else {
                $companies = $companies->orderBy('companies.clicks', 'desc');
            }
        } else {
            $companies = $companies->orderBy('companies.clicks', 'desc');
        }

        if ($request->has('no_filter') == FALSE) {
            // Filter Preferences
            if ($request->input('filter') == 1 && $request->has('preference')) {
                $optionCount = count($request->input('preference'));
                foreach ($request->input('preference') as $option) {
                    if (!$request->has('page')) {
                        $preferenceClick = new Preference();
                        $preferenceClick->addClick($option, 1);
                    }

                    if (count($request->input('preference')) >= 2) {
                        $companies->orWhere('preferences', 'REGEXP', '"([^"]*)'.$option.'([^"]*)"');
                    } else {
                        $companies->where('preferences', 'REGEXP', '"([^"]*)'.$option.'([^"]*)"');
                    }
                }
            } else  {
                if (
                    $request->has('filter') == FALSE
                    && Sentinel::check()
                    && $this->user->preferences != 'null'
                    && $this->user->preferences != NULL
                    && $this->user->preferences != '[""]'
                ) {
                    $userPreferences = json_decode($this->user->preferences);
                    foreach (json_decode($this->user->preferences) as $option) {
                        if (count($userPreferences) >= 2) {
                            $companies->orWhere('preferences', 'REGEXP', '"([^"]*)'.$option.'([^"]*)"');
                        } else {
                            $companies->where('preferences', 'REGEXP', '"([^"]*)'.$option.'([^"]*)"');
                        }
                    }
                }
            }

            // Filter Kitchens
            if ($request->input('filter') == 1 && $request->has('kitchen')) {
                foreach ($request->input('kitchen') as $option) {
                    if (!$request->has('page')) {
                        $preferenceClick = new Preference();
                        $preferenceClick->addClick($option, 2);
                    }

                    if (count($request->input('kitchen')) >= 2) {
                        $companies->orWhere('kitchens', 'REGEXP', '"([^"]*)'.$option.'([^"]*)"');
                    } else  {
                        $companies->where('kitchens', 'REGEXP', '"([^"]*)'.$option.'([^"]*)"');
                    }
                }
            } else  {
                if (
                    $request->has('filter') == FALSE
                    && Sentinel::check()
                    && $this->user->kitchens != 'null'
                    && $this->user->kitchens != NULL
                    && $this->user->kitchens != '[""]'
                ) {
                    $userKitchens = json_decode($this->user->kitchens);
                    foreach ($userKitchens as $option) {
                        if (count($userKitchens) >= 2) {
                            $companies->orWhere('kitchens', 'REGEXP', '"([^"]*)'.$option.'([^"]*)"');
                        } else {
                            $companies->where('kitchens', 'REGEXP', '"([^"]*)'.$option.'([^"]*)"');
                        }
                    }
                }
            }

            // Filter Allergies
            if ($request->input('filter') == 1 && $request->has('allergies')) {
                foreach ($request->input('allergies') as $option) {
                    if (!$request->has('page')) {
                        $preferenceClick = new Preference();
                        $preferenceClick->addClick($option, 3);
                    }

                    if (count($request->input('allergies')) >= 2) {
                        $companies->orWhere('allergies', 'REGEXP', '"([^"]*)'.$option.'([^"]*)"');
                    } else {
                        $companies->where('allergies', 'REGEXP', '"([^"]*)'.$option.'([^"]*)"');
                    }
                }
            } else  {
                if (
                    $request->has('filter') == FALSE
                    && Sentinel::check()
                    && $this->user->allergies != 'null'
                    && $this->user->allergies != NULL
                    && $this->user->allergies != '[""]'
                ) {
                    $userAllergies = json_decode($this->user->allergies);
                    foreach ($userAllergies as $option) {
                        if (count($userAllergies) >= 2) {
                            $companies->orWhere('allergies', 'REGEXP', '"([^"]*)'.$option.'([^"]*)"');
                        } else  {
                            $companies->where('allergies', 'REGEXP', '"([^"]*)'.$option.'([^"]*)"');
                        }
                    }
                }
            }

            // Filter Price
            if ($request->input('filter') == 1 && $request->has('price')) {
                foreach ($request->input('price') as $option) {
                    if (!$request->has('page')) {
                        $preferenceClick = new Preference();
                        $preferenceClick->addClick($option, 4);
                    }

                    if (count($request->input('price')) >= 2) {
                        $companies->orWhere('price', 'REGEXP', '"([^"]*)'.$option.'([^"]*)"');
                    } else  {
                        $companies->where('price', 'REGEXP', '"([^"]*)'.$option.'([^"]*)"');
                    }
                }
            } else  {
                if (
                    $request->has('filter') == FALSE
                    && Sentinel::check()
                    && $this->user->price != 'null'
                    && $this->user->price != NULL
                    && $this->user->price != '[""]'
                ) {
                    $userPrices = json_decode($this->user->price);
                    foreach ($userPrices as $option) {
                        if (count($userPrices) >= 2) {
                            $companies->orWhere('price', 'REGEXP', '"([^"]*)'.$option.'([^"]*)"');
                        } else {
                            $companies->where('price', 'REGEXP', '"([^"]*)'.$option.'([^"]*)"');
                        }
                    }
                }
            }

            // Filter Discount
             if ($request->input('filter') == 1 && $request->has('discount')) {
                $companies = $companies->where(function ($query) use($request) {
                    foreach ($request->input('discount') as $key => $option) {
                        if (!$request->has('page')) {
                            $preferenceClick = new Preference();
                            $preferenceClick->addClick($option, 5);
                        }

                        if ($key == 0) {
                            $query->where('discount', 'REGEXP', '"([^"]*)'.rawurldecode($option).'([^"]*)"');
                        } else {
                            $query->orWhere('discount', 'REGEXP', '"([^"]*)'.rawurldecode($option).'([^"]*)"');
                        }
                    }
                });
            } else {
                if (
                    $request->has('filter') == FALSE
                    && Sentinel::check()
                    && $this->user->discount != 'null'
                    && $this->user->discount != NULL
                    && $this->user->discount != '[""]'
                ) {
                    $userDiscounts = json_decode($this->user->discount);

                    $companies = $companies->where(function ($query) use($userDiscounts) {
                        foreach ($userDiscounts as $key => $option) {
                            if ($key == 0) {
                                $query->where('discount', 'REGEXP', '"([^"]*)'.rawurldecode($option).'([^"]*)"');
                            } else {
                                $query->orWhere('discount', 'REGEXP', '"([^"]*)'.rawurldecode($option).'([^"]*)"');
                            }
                        }
                    });
                }
            }
        }

        if ($request->has('time') && $request->has('date')) {
            $companies = $companies
                ->join('company_reservations', 'company_reservations.company_id', '=', 'companies.id')
                ->where('company_reservations.date', '=', $request->input('date'));
        }

        $companies = $companies
            ->where('no_show', 0)
            ->with('media')
        ;

        $countCompanies = $companies->count();
        $companies = $companies->paginate($request->input('limit', 15));

        foreach($companies as $company) {
            if($company->ReservationOptions()){
                foreach($company->ReservationOptions()->get() as $deal) {
                    $companyIds[] = $company->id;
                }
            }
        }
        $reservationDate = date('Y-m-d');
        $tomorrowDate = date('Y-m-d', strtotime('+1 days'));

        if (isset($companyIds)) {
            $reservationTimesArray = CompanyReservation::getReservationTimesArray(
                array(
                    'company_id' => $companyIds,
                    'date' => $reservationDate,
                    'selectPersons' => NULL
                )
            );

            $tomorrowArray = CompanyReservation::getReservationTimesArray(
                array(
                    'company_id' => $companyIds,
                    'date' => $tomorrowDate,
                    'selectPersons' => NULL
                )
            );
        }

        if (!$request->has('no_filter') && count($companies) == 0) {
            alert()->error('', 'Er zijn geen zoekresultaten gevonden met uw selectiecriteria.<br /> <br /><small>Klik <a href=\''.URL::to('account').'\'> hier</a> om uw criteria aan te passen.</small>')->html()->persistent('Sluiten');

            return Redirect::to('/?no_filter=1'.($request->has('mobilefilter') ? '&mobilefilter=1' : ''));
        }

        $queryString = $request->query();
        unset($queryString['limit']);
		
		foreach ($cities as $city) {
            $media = $city->getMedia();
            if (isset($media[0]) && file_exists(public_path().$media[0]->getUrl('thumb')))
                    $city->media = url(''.$media[0]->getUrl('thumb'));
            else
                $city->media = url('images/placeholdimage.png');
        }

        return view('pages/home', [
            'user' => $this->user,
            'cities' => $cities,
            'countCompanies' => $countCompanies,
            'companies' => $companies,
            'limit' => $request->input('limit', 15),
            'queryString' => $queryString,
            'news' => $this->news,
            'affiliates' => $this->affiliates,
            'reservationDate' => $reservationDate,
            'tomorrowArray' => (isset($tomorrowArray) ? $tomorrowArray : array()),
            'reservationTimesArray' => (isset($reservationTimesArray) ? $reservationTimesArray : array()),
            'paginationQueryString' => $request->query()
        ]);
    }


    public function review($id)
    {
        $data = Review::select(
            'reviews.*',
            'companies.name as companySlug',
            'companies.name as companyName'
        )
            ->where('reviews.id', $id)
            ->leftJoin('companies', 'companies.id', '=', 'reviews.company_id')
            ->first()
        ;

        return view('pages/restaurant/reviews/view', [
            'review' => $data,
            'reviewModel' => new Review()
        ]);
    }

    public function searchRedirect(Request $request)
    {
        switch ($request->input('page')) {
            case 'faq':
                $redirectTo = 'faq';
                break;

            case 'restaurant':
                $redirectTo = 'search';
                break;

            case 'saldo':
                $redirectTo = 'tegoed-sparen/search';
                break;
        }

        return Redirect::to($redirectTo.'?q='.$request->input('q'));
    }

    public function preferences(Request $request)
    {
        $dataSearch = array(
            'filter' => 1,
            'q' => $request->input('q'),
            'date' => $request->input('date'),
            'sltime' => $request->input('sltime'),
            'persons' => $request->input('persons'),
            'preference' => $request->input('preference'),
            'kitchen' => $request->input('kitchen'),
            'price' => $request->input('price'),
            'discount' => $request->input('discount'),
            'allergies' => $request->input('allergies')
        );

        $data = array(
            'filter' => 1,
            'q' => $request->input('q'),
            'date' => $request->input('date'),
            'time' => $request->input('time'),
            'persons' => $request->input('persons'),
            'preference' => $request->input('preference'),
            'kitchen' => $request->input('kitchen'),
            'price' => $request->input('price'),
            'discount' => $request->input('discount'),
            'allergies' => $request->input('allergies')
        );

        if ($request->has('type') && $request->input('type') == 'home' && Sentinel::check()) {
            $user = Sentinel::getUser();
            $user->kitchens = json_encode($request->input('kitchen'));
            $user->discount = json_encode($request->input('discount'));
            $user->preferences = json_encode($request->input('preference'));
            $user->allergies = json_encode($request->input('allergies'));
            $user->save();
        }

        if ($request->has('q') || $request->has('date')) {
            return Redirect::to('search?'.http_build_query($dataSearch));
        } else {
           return Redirect::to('/?'.http_build_query($data));
        }
    }

    public function setLang(Request $request, $locale)
    {

        // return $locale;
//        $request->session()->put('locale', $locale);
//
//        App::setLocale($locale);
        if(isset($request->user()->id)){
            $user = App\User::find($request->user()->id);
            $user->update(['lang' => $locale]);
        }
        session(['language' => $locale]);

        return redirect('/home');

        // return Redirect::to($request->has('redirect') ? $request->input('redirect') : '/');
    }

    public function times(Request $request)
    {
        $getTimes = CompanyReservation::getAllTimes();
        sort($getTimes);

        $disabled = array();
        foreach($getTimes as $time)
        {
            $timeCarbon = Carbon::create(date('Y', strtotime($request->input('date'))), date('m', strtotime($request->input('date'))), date('d', strtotime($request->input('date'))), date('H', strtotime($time)), date('i', strtotime($time)), 0);

            if(!$timeCarbon->isPast())
            {
                $disabled[] = $time;
            }
        }

        return view('pages/times', [
            'times' => $getTimes,
            'disabled' => $disabled
        ]);
    }

    public function faq(Request $request, $id = null, $slug = null)
    {
        if ($request->has('q')) {
            $searchHistory = new SearchHistory();
            $searchHistory->addTerm($request->input('q'), '/faq');  // Add to Search History
        }

        if ($request->has('slug') && $request->has('step')) {
            $company = Company::where('slug', $request->input('slug'))->first();

            if ($company) {
                if (Sentinel::inRole('admin') == FALSE && $company->signature_url == NULL) {
                    alert()->error('', 'U heeft nog geen handtekening opgegeven en u bent nog niet akkoord gegaan met de Algemene Voorwaarden.')->persistent('Sluiten');
                    return Redirect::to('admin/companies/update/'.$company->id.'/'.$company->slug.'?step=1');
                }
            }
        }

        $category = FaqCategory::select(
            'id',
            'name',
            'category_id'
        )
            ->where('id', '=', $id)
            ->where('slug', '=', $slug)
            ->first()
        ;

        if (count($category) == 1) {
            $categories = FaqCategory::select(
                'id',
                'slug',
                'name'
            )
                ->whereNull('category_id')
                ->get()
            ;

            $subcategories = FaqCategory::select(
                'id',
                'slug',
                'name'
            )
                ->whereNotNull('category_id')
                ->where('category_id', '=', $category->category_id)
                ->orWhere('category_id', '=', $category->id)
                ->get()
            ;

            $questions = Faq::select(
                'id',
                'title',
                'answer'
            )
                ->orderBy('clicks', 'desc')
            ;

            if ($request->has('q')) {
                $questions = $questions->where('title', 'LIKE', '%'.$request->input('q').'%');
            }

            $questions = $questions->where(function ($query) use ($id, $category) {
                if (isset($category->category_id)) {
                    $query->where('subcategory', '=', $id);
                } else {
                    $query
                        ->where('category', '=', $category->id)
                        ->orWhere('subcategory', '=', $category->id)
                    ;
                }
            })
                ->paginate(15)
            ;

            return view('pages/faq', [
                'questions' => $questions,
                'categories' => $categories,
                'subcategories' => $subcategories,
                'slug' => $slug,
                'categoryId' => $category->category_id == null ? $category->id : $category->category_id,
            ]);
        } else {
            $categories = FaqCategory::select(
                'id',
                'slug',
                'name'
            )
                ->whereNull('category_id')
                ->get()
            ;

            $questions = Faq::select(
                'id',
                'title',
                'answer'
            )
                ->orderBy('clicks', 'desc')
            ;

            if ($request->has('q')) {
                $questions = $questions->where('title', 'LIKE', '%'.$request->input('q').'%');
            }

            $questions = $questions->paginate(15);

            return view('pages/faq', [
                'questions' => $questions,
                'categories' => $categories,
                'categoryId' => null
            ]);
        }
    }

    public function page($slug)
    {
        $page = Page::where('slug', $slug);

        if (Sentinel::check() == FALSE || Sentinel::check() && Sentinel::inRole('admin') == FALSE) {
            $page = $page->where('is_hidden', 0);
        }

        $page = $page->first();

        if ($page) {
            return view('pages/page', ['page' => $page]);
        } else {
            App::abort(404);
        }
    }

    public function search(Request $request)
    {
		
        $searchHistory = new SearchHistory();
        $searchHistory->addTerm($request->input('q'), '/search');

       $companiesLimit = $request->input('limit', 15);
        

        $companies = Company::select(
            'companies.id',
            'companies.name',
            'companies.slug',
            'companies.description',
            'companies.discount',
            'companies.days',
            'companies.kitchens',
            'companies.city'
        );

        if (
            Sentinel::check()
            && $this->user->city != 'null'
            && $this->user->city != NULL
            && $this->user->city != '[""]'
        ) {
            $userCities = json_decode($this->user->city);

            if (is_array($userCities)) {
                foreach ($userCities as $userCity) {
                    $companies = $companies->orderByRaw('companies.regio REGEXP "[[:<:]]'.$userCity.'[[:>:]]" desc');
                }
            } else {
                $companies = $companies->orderBy('companies.created_at', 'asc');
            }
        } else {
            $companies = $companies->orderBy('companies.created_at', 'asc');
        }

        if ($request->input('filter') == 1 && $request->has('preference')) {
            $companies = $companies->where(function ($query) use($request) {
                foreach ($request->input('preference') as $key => $option) {
                    $preferenceClick = new Preference();
                    $preferenceClick->addClick($option, 1);

                    if ($key == 0) {
                        $query->where('preferences', 'REGEXP', '"([^"]*)'.$option.'([^"]*)"');
                    } else {
                        $query->orWhere('preferences', 'REGEXP', '"([^"]*)'.$option.'([^"]*)"');
                    }
                }
            });
        }

        if ($request->has('kitchen'))  {
            $companies = $companies->where(function ($query) use($request) {
                foreach ($request->input('kitchen') as $key => $option) {
                    if ($key == 0) {
                        $query->where('kitchens', 'REGEXP', '"([^"]*)'.$option.'([^"]*)"');
                    } else {
                        $query->orWhere('kitchens', 'REGEXP', '"([^"]*)'.$option.'([^"]*)"');
                    }
                }
            });
        }

        if ($request->has('allergies')) {
            $companies = $companies->where(function ($query) use($request) {
                foreach ($request->input('allergies') as $key => $option) {
                    if ($key == 0) {
                        $query->where('allergies', 'REGEXP', '"([^"]*)'.$option.'([^"]*)"');
                    } else {
                        $query->orWhere('allergies', 'REGEXP', '"([^"]*)'.$option.'([^"]*)"');
                    }
                }
            });
        }


        if ($request->has('discount')) {
            $companies = $companies->where(function ($query) use($request) {
                foreach ($request->input('discount') as $key => $option) {
                    if ($key == 0) {
                        $query->where('discount', 'REGEXP', '"([^"]*)'.rawurldecode($option).'([^"]*)"');
                    } else {
                        $query->orWhere('discount', 'REGEXP', '"([^"]*)'.rawurldecode($option).'([^"]*)"');
                    }
                }
            });
        }

        if ($request->input('filter') == 1 && $request->has('price')) {
            $companies = $companies->where(function ($query) use($request) {
                foreach ($request->input('price') as $key => $option) {
                    if ($key == 0) {
                        $query->where('price', 'REGEXP', '"([^"]*)'.$option.'([^"]*)"');
                    } else {
                        $query->orWhere('price', 'REGEXP', '"([^"]*)'.$option.'([^"]*)"');
                    }
                }
            });
        }

        if ($request->has('q')) {
            $termDivider = str_replace(' ', '|', $request->input('q'));

            $companies->where(function ($query) use($request, $termDivider) {
                $query
                    ->where('companies.name', 'LIKE', '%'.$request->input('q').'%')
                    ->orWhere('address', 'RLIKE', $termDivider)
                    ->orWhere('zipcode', 'RLIKE', $termDivider)
                    ->orWhere('city', 'RLIKE', $termDivider)
                    ->orWhere('preferences', 'REGEXP', '"([^"]*)'.$request->input('q').'([^"]*)"')
                    ->orWhere('kitchens', 'REGEXP', '"([^"]*)'.$request->input('q').'([^"]*)"')
                    ->orWhere('allergies', 'REGEXP', '"([^"]*)'.$request->input('q').'([^"]*)"')
                    ->orWhere('discount', 'REGEXP', '"([^"]*)'.$request->input('q').'([^"]*)"')
                    ->orWhere('sustainability', 'REGEXP', '"([^"]*)'.$request->input('q').'([^"]*)"')
                    ->orWhere('price', 'REGEXP', '"([^"]*)'.$request->input('q').'([^"]*)"')
                    ->orWhere('facilities', 'REGEXP', '"([^"]*)'.$request->input('q').'([^"]*)"')
                ;
            });
        }

        if ($request->has('regio')) { 
            $preferences = new Preference();
            $regio = $preferences->getRegio();
            $companies = $companies
                ->where('companies.regio', 'REGEXP', '"[[:<:]]'.$regio['regioNumber'][$request->input('regio')].'[[:>:]]"')
                ->orWhere('companies.regio', '=', $regio['regioNumber'][$request->input('regio')])
            ;
        }

        $newCompanyId = array();
        $companyId = array();

        if ($request->has('sltime')) {
            $companies = $companies
                ->join('company_reservations', 'company_reservations.company_id', '=', 'companies.id')
                ->where('company_reservations.date', '=', trim($request->input('date')) != '' ? date('Y-m-d', strtotime($request->input('date'))) : date('Y-m-d'))
                ->where('company_reservations.available_persons', 'REGEXP', '"([^"]*)'.$request->input('sltime').'([^"]*)"')
                ->groupBy('companies.id')
            ;
        }

        $time = date('H:i', strtotime($request->input('sltime')));

        $reservationDate = ($request->has('date') ? date('Y-m-d', strtotime($request->input('date'))) : date('Y-m-d'));
        $tomorrowDate = date('Y-m-d', strtotime('+1 days'));

        if ($request->has('sltime') && $request->has('date')) {
            if (isset($reservationTimesArray[$time])) {
                foreach ($reservationTimesArray[$time] as $key => $reservation) {
                    $newCompanyId[] = $key;
                }
            }

            if (count($newCompanyId) >= 1) {
                 $companies = $companies->whereIn('companies.id', $newCompanyId);
            }
        }


        $companies = $companies->where('no_show', 0 )->with('media');

		$countCompanies = $companies->count();
        $companies = $companies->paginate($companiesLimit);
        $queryString = $request->query();

        foreach($companies as $key => $company)  {
            $companyId[] = $company->id;
        }

        // Recommended
        if ($request->has('sltime')) {
            $recommended = Company::select(
                'companies.id',
                'companies.name',
                'companies.slug',
                'companies.description',
                'companies.discount',
                'companies.days',
                'companies.kitchens',
                'companies.city'
            )
                ->leftJoin('company_reservations', 'company_reservations.company_id', '=', 'companies.id')
                ->where('companies.no_show', '=', 0)
                ->where('company_reservations.date', '=', trim($request->input('date')) != '' ? date('Y-m-d', strtotime($request->input('date'))) : date('Y-m-d'))
            ;

             if ($request->has('q')) {
                $termDivider = str_replace(' ', '|', $request->input('q'));

                $recommended->where(function ($query) use($request, $termDivider) {
                    $query
                        ->where('companies.name', 'LIKE', '%'.$request->input('q').'%')
                        ->orWhere('address', 'RLIKE', $termDivider)
                        ->orWhere('zipcode', 'RLIKE', $termDivider)
                        ->orWhere('city', 'RLIKE', $termDivider)
                        ->orWhere('preferences', 'REGEXP', '"([^"]*)'.$request->input('q').'([^"]*)"')
                        ->orWhere('kitchens', 'REGEXP', '"([^"]*)'.$request->input('q').'([^"]*)"')
                        ->orWhere('allergies', 'REGEXP', '"([^"]*)'.$request->input('q').'([^"]*)"')
                        ->orWhere('discount', 'REGEXP', '"([^"]*)'.$request->input('q').'([^"]*)"')
                        ->orWhere('sustainability', 'REGEXP', '"([^"]*)'.$request->input('q').'([^"]*)"')
                        ->orWhere('price', 'REGEXP', '"([^"]*)'.$request->input('q').'([^"]*)"')
                        ->orWhere('facilities', 'REGEXP', '"([^"]*)'.$request->input('q').'([^"]*)"')
                    ;
                });
            }

            if ($request->has('sltime')) {
                $recommended = $recommended
                    ->where('company_reservations.date', '=', trim($request->input('date')) != '' ? date('Y-m-d', strtotime($request->input('date'))) : date('Y-m-d'))
                ;
            }

            $recommended = $recommended
                ->limit(10)
                ->groupBy('companies.id')
                ->whereNotIn('companies.id', $companyId)
                ->with('media')
                ->get();
            ;

            foreach($recommended as $key => $company)  {
                $companyId[] = $company->id;
            }
        }

        $reservationTimesArray = CompanyReservation::getReservationTimesArray(
            array(
                'company_id' => $companyId,
                'date' => $reservationDate,
               'selectPersons' => ($request->has('persons') ? $request->input('persons') : null)
            )
        );
        $tomorrowArray = CompanyReservation::getReservationTimesArray(
            array(
                'company_id' => $companyId,
                'date' => $tomorrowDate,
                'selectPersons' => ($request->has('persons') ? $request->input('persons') : null)
            )
        );

        if (
            count($companies) == 0
            OR date('Y-m-d') == date('Y-m-d', strtotime($request->input('date')))
            && date('H:i') >= date('H:i', strtotime($request->input('sltime')))
        ) {
            alert()->error('', 'Er zijn geen zoekresultaten gevonden met uw selectiecriteria.')->persistent('Sluiten');

            return Redirect::to('/index');
        }


        if (request()->has("layout") && request()->get("layout") === "grid")
            $searchPage = "pages/search-grid";
        else
            $searchPage = "pages/search";
		
        return view($searchPage, [
            'companies' => $companies,
            'affiliates' => $this->affiliates,
            'countCompanies' => $countCompanies,
            'recommended' => isset($recommended) ? $recommended : array(),
            'news' => $this->news,
            'times' => CompanyReservation::getAllTimes(),
            'limit' => $companiesLimit,
            'queryString' => $queryString,
            'reservationDate' => $reservationDate,
            'reservationTimesArray' => (isset($reservationTimesArray) ? $reservationTimesArray : array()),
            'tomorrowArray' => (isset($tomorrowArray) ? $tomorrowArray : array()),
            'paginationQueryString' => $request->query()
        ]);
    }

    public function createIcs(Request $request)
    {
        header('Content-type: text/calendar; charset=utf-8');
        header('Content-Disposition: attachment; filename='.'event-uwvoordeelpas.ics');

        return view('template/ics', array(
            'title' => $request->input('title'),
            'location' => $request->input('location'),
            'description' => $request->input('description'),
            'startdate' => $request->input('startdate'),
            'enddate' => $request->input('enddate'),
            'todaydate' => $request->input('todaydate')
        ));
    }

    public function contact(Request $request)
    {
        return view('pages/contact', array(
        ));
    }

    public function testPage(Request $request)
    {
        $mailtemplate = new MailTemplate();
		$mailtemplate->sendMail(array(
                  'email' => 'bhalodia.sandip@gmail.com',
                  'reservation_id' => 56,
                  'template_id' => 'new-reservation-company',
                  'company_id' => 157,
                  'manual' => 0,
                  'replacements' => array(
                  '%name%' => 'Kanjji Bhagat',
                  '%cname%' => 'Virlo hansraj',
                  '%saldo%' => 50,
                  '%phone%' => '951077784',
                  '%email%' => 'kanji.hardayal@gmail.com',
                  '%date%' => date('d-m-Y', strtotime('2017-10-24')),
                  '%time%' => date('H:i', strtotime('11:00')),
                  '%persons%' => 8,
                  '%comment%' => 'Kai comment nathi karvi',
                  '%discount%' => '',
                  '%discount_comment%' =>  '',
                  '%days%' => '',
                  '%allergies%' => '',
                  '%preferences%' => '',
                  )
                  ));
	 exit('success');
        return view('pages/test', array(
        ));
    }

    public function contactAction(Request $request)
    {
            $this->validate($request, [
                'name' => 'required|min:2',
                'email' => 'required|email',
                'content' => 'required|min:10',
                'subject' => 'required|min:5',
                'CaptchaCode' => 'required|valid_captcha'
            ]);

            $data[ 'name' ] = $request->input('name');
            $data[ 'email' ] = $request->input('email');
            $data[ 'content' ] = $request->input('content');
            $data[ 'subject' ] = $request->input('subject');
            $data[ 'status' ] = "new";
            $data[ 'created_at' ] = date('Y-m-d H:i:s');

            $answer = Company::checkQuestion($request->subject, $request->content);

            if(count($answer) > 0) {

                $data[ 'status' ] = "replied";
                $id = Company::saveContact($data);
                $data[ 'id' ] = $id;
                session()->put("contact_data", $data);

                $data[ 'questions' ] = $answer;

                return view('pages/contact-faq', $data);
            }
            else {

                $id = Company::saveContact($data);

                Alert::success('', 'Uw bericht is succesvol verzonden.  Wij hopen u zo snel mogelijk antwoord te kunnen geven.')->persistent('Sluiten');

                return Redirect::to('/');
            }
//        $mail = array(
//            'request' => $request
//        );
//
//        Mail::send('emails.contact_site', $mail, function ($message) use ($request) {
//            $message
//                ->to('sseymor@roc-dev.com')
//                ->subject($request->input('subject'))
//                ->from($request->input('email'))
//            ;
//        });

    }

    public function contactFAQAction(Request $request)
    {
        if($request->has("req") && $request->get("req") == "complete")
        {
            Alert::success('',
                'Uw bericht is succesvol verzonden.  Wij hopen u zo snel mogelijk antwoord te kunnen geven.')->persistent('Sluiten');
            return Redirect::to('/');
        }

        if (! session()->has('contact_data')) {
//            $mail_user = array(
//                'request' => $request,
//                'body' => $answer
//            );
//            Mail::send('emails.contact_site_user', $mail_user, function ($message) use ($request) {
//                $message
//                    ->to($request->email)
//                    ->subject($request->input('subject'))
//                    ->from('sseymor@roc-dev.com');
//            });
            $data = session()->pull('contact_data');
            $contact = Contact::find($data[ 'id' ]);
            $contact->status = "new";
            $contact->save();

            Alert::success('',
                'Uw bericht is succesvol verzonden.  Wij hopen u zo snel mogelijk antwoord te kunnen geven.')->persistent('Sluiten');

        }
            return Redirect::to('/');
    }

    public function redirectTo(Request $request)
    {
        if ($request->has('p')) {
            switch ($request->input('p')) {
                case 2:
                    Setting::set('discount.views2', Setting::get('discount.views2') + 1);
                    break;

                case 3:
                    Setting::set('discount.views3', Setting::get('discount.views3') + 1);
                    break;

                default:
                    Setting::set('discount.views', Setting::get('discount.views') + 1);
                    break;
            }
        }

        return Redirect::to($request->has('to') ? $request->input('to') : '/');
    }

    public function sourceRedirect(Request $request)
    {
        $websiteSettings = json_decode(json_encode(Setting::get('website')), true);

        if (isset($websiteSettings['source'])) {
            $sources = explode(PHP_EOL, $websiteSettings['source']);

            if (is_array($sources) && in_array($request->input('source'), $sources)) {
                if ($request->has('source')) {
                    return Redirect::to('/')->withCookie(cookie('source', $request->input('source'), 44640));
                }
            } else {
                return Redirect::to('/');
            }
        }
    }

    public static function getPersons($option_id){

        $data = DB::table('reservations')->select(DB::raw('SUM(persons) as total_persons'))->where("option_id",$option_id)->get();

         return $data;

    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function buyDealByReference(Request $request)
    {
       if (!Sentinel::check())
        {
            return redirect('/');
        }
        $referrer = App\User::where('reference_code', $request->get('reference'))->first();
        $user = Sentinel::getUser();
        
        
        if ($referrer && $user->reference_code != $request->get('reference'))
        {
            $referralExist = App\Models\Referral::exists($user->id, $referrer->id);
            if(!$referralExist){
                $referral = App\Models\Referral::create([
                    'user_id' => $user->id,
                    'referrer_id' => $referrer->id,
                    'reference' =>$request->get('reference')
                ]);                        
                session(['reference' => $request->get('reference'), 'referer' => $referrer->id]);
                return redirect('/home');
            }else{
                return redirect('/reference_code');
            }
                
        }else {
            session(['reference' => null, 'referer' => null]);
            return redirect('/');
        }
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function referenceCode(Request $request)
    {
		
        /*if (Sentinel::check() == FALSE) {
                    Sentinel::login($user);
                }*/
        $reference = Sentinel::getUser();
        if (!$reference) {
            return redirect('/');
        }

        if (is_null($reference->reference_code) || $reference->reference_code =="") {
            $reference->reference_code = str_random(17);
            $reference->save();
        }

        if (Sentinel::inRole('admin') != FALSE) {
            $friends = App\Models\FutureDeal::where('reference_id', '!=', null)
                ->where('user_id', '!=', Sentinel::getUser()->id)->get();
        } else {
            $friends = App\Models\FutureDeal::where('reference_id', $reference->id)
                ->groupBy('user_id')->distinct()->get();
        }
	
        return view('reference-code', compact('reference', 'friends'));
    }
}
