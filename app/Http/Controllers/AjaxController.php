<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\CompanyReservation;
use App\Models\Company;
use App\Models\Guest;
use App\Models\AffiliateCategory;
use App\Models\Affiliate;
use App\Models\Faq;
use App\Models\FaqCategory;
use App\Models\Content;
use App\Models\Reservation;
use App\Models\Notification;
use App\Models\Preference;
use App\Models\Category;
use App\Models\CompanyService;
use App\Models\CompanyCallcenter;
use App\Models\MailTemplate;
use App\Models\NewsletterGuest;
use App\Helpers\AffiliateHelper;
use App\Models\SearchHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Sentinel;
use URL;
use anlutro\cURL\cURL;
use DB;
class AjaxController extends Controller 
{
    public function extensiondownloadStatus(Request $request) {
            $user = Sentinel::getUser();
            if(isset($user->id)){
                DB::update('update users set extension_downloaded = 2 where id = ? and extension_downloaded=0', [$user->id]);
                return $user->id;
            }
            return 0;
    }

    public function usersSetRegio(Request $request) 
    { 
        $preferences = new Preference;

        $regio = $preferences->getRegio();
        $city = str_slug($request->input('city'));

        if ($request->has('city') && isset($regio['regioNumber'][$city]) && Sentinel::check()) {
            $user = Sentinel::getUser();
            $userCities = is_array(json_decode($user->city)) ? json_decode($user->city) : array();

            if (!in_array($regio['regioNumber'][$city], $userCities)) {
                $exists = 0;
            } else {
                $exists = 1;
            }

            if (is_array($userCities)) {
                if (!in_array($regio['regioNumber'][$city], $userCities)) {
                    array_push($userCities, ''.$regio['regioNumber'][$city].'');
                    
                    $user->city = json_encode($userCities);
                    $user->save();
                }
            }

            return $exists;
        }

        if ($request->has('city') && isset($regio['regioNumber'][$city]) && !Sentinel::check()) {
            // session('user_off_regio', $regio['regioNumber'][$city]);
            

            return response('Hello World')->cookie(
                'user_off_regio', $regio['regioNumber'][$city]
            );
        }
    }

    public function removeNewsletterGuest(Request $request) 
    { 
        $guest = NewsletterGuest::where('newsletter_id', '=', $request->input('newsletter_id'))
            ->where('company_id', '=', $request->input('company_id'))
            ->where('user_id', '=', $request->input('user_id'))
            ->first()
        ; 

        $guest->no_show = $request->input('no_show');
        $guest->save();

        return 'removed';
    }

    public function nearbyCompany(Request $request) 
    {
        $preferences = Preference::where('category_id', '=', 2)
            ->with('media')
            ->get()
        ;

        foreach ($preferences as $key => $preference) {
            $media = $preference->getMedia();

            $preferencesArray[str_slug($preference->slug)] = array(
                'slug' => str_slug($preference->slug),
                'name' => $preference->name,
                'image' => isset($media[0]) ? URL::to($media[0]->getUrl('thumb')) : ''
            );
        }

        $companies = Company::where('no_show', '=', 0)
            ->where('address', '=', $request->input('address'))
            ->where('zipcode', '=', $request->input('zipcode'))
            ->groupBy('name')
            ->get()
        ;

        $company = array();

        foreach ($companies as $key => $result) {
            $curl = new cURL;

            $curlResult = $curl->newRequest(
                'GET', 'https://maps.googleapis.com/maps/api/geocode/json?address='.urlencode($result->address.','.$result->zipcode)
            )
                ->setOption(CURLOPT_CAINFO, base_path('cacert.pem'))
                ->send()
            ;

            $body = json_decode($curlResult->body);

            if (count($body) == 1) {
                if (is_array($body->results) && isset($body->results[0])) {
                    $geometry = $body->results[0]->geometry->location;

                    $company[] = array(
                        'lat' => $geometry->lat,
                        'lng' => $geometry->lng,
                        'name' => $result->name,
                        'address' => $result->address,
                        'zipcode' => $result->zipcode,
                        'city' => $result->city,
                        'url' => url('restaurant/'.$result->slug),
                        'kitchen' => (is_array(json_decode($result->kitchens)) && isset($preferencesArray[str_slug(json_decode($result->kitchens)[0])]) ? $preferencesArray[str_slug(json_decode($result->kitchens)[0])]['image'] : '')
                        
                     );
                }
            }
        }

        return json_encode($company);
    }

    public function nearbyCompanies(Request $request) 
    {  
        $preferences = Preference::where('category_id', '=', 2)
            ->with('media')
            ->get()
        ;

        foreach ($preferences as $key => $preference) {
            $media = $preference->getMedia();

            $preferencesArray[str_slug($preference->slug)] = array(
                'slug' => str_slug($preference->slug),
                'name' => $preference->name,
                'image' => isset($media[0]) ? URL::to($media[0]->getUrl('thumb')) : ''
            );
        }

        $companies = Company::where('no_show', '=', 0)
            ->where('address', '!=', '')
            ->where('zipcode', '!=', '')
            ->groupBy('name')
            ->get()
        ;

        $company = array();

        foreach ($companies as $key => $result) {
             $company[] = array(
                'name' => $result->name,
                'address' => $result->address,
                'zipcode' => $result->zipcode,
                'city' => $result->city,
                'url' => url('restaurant/'.$result->slug),
                'kitchen' => (is_array(json_decode($result->kitchens)) && isset($preferencesArray[str_slug(json_decode($result->kitchens)[0])]) ? $preferencesArray[str_slug(json_decode($result->kitchens)[0])]['image'] : '')
            );
        }

        return json_encode($company);
    }

    public function mailtemplates(Request $request) 
    {  
        $mailtemplate = MailTemplate::select(
            'mail_templates.id',
            'mail_templates.is_active'
        )
            ->leftJoin('companies', 'mail_templates.company_id', '=', 'companies.id')
        ;

        if (Sentinel::inRole('bedrijf') && Sentinel::inRole('admin') == FALSE)  {
            $mailtemplate = $mailtemplate->where('companies.user_id', Sentinel::getUser()->id);
        }

        $mailtemplate  = $mailtemplate->find($request->input('id'));

        if ($mailtemplate) {
            $mailtemplate->is_active = $request->input('is_active');
            $mailtemplate->save();
        }
    }

    public function appointmentCompanies(Request $request) 
    {  
        $companies = CompanyCallcenter::select(
            'id',
            'name',
            'city',
            'email',
            'address',
            'zipcode',
            'contact_name'
        )
            ->where('id', $request->input('id'))
            ->get()
            ->toArray()
        ;

        return count($companies) == 1 ? json_encode($companies) : json_encode(array());
    }


    public function newsletterGuests(Request $request) 
    {  
        $guests = Guest::select(
            'users.id',
            'users.name',
            'companies.name as companyName'
        )
            ->leftJoin('users', 'guests.user_id', '=', 'users.id')
            ->leftJoin('companies', 'companies.id', '=', 'guests.company_id')
            ->whereIn('guests.company_id', $request->input('companies'))
            ->get()
            ->toArray()
        ;

        return json_encode($guests);
    }

    public function changeTableNumber(Request $request) 
    {  
        $reservation = Reservation::select(
            'reservations.id',
            'reservations.table_nr',
            'companies.slug'
        )
            ->leftJoin('companies', 'companies.id', '=', 'reservations.company_id')
            ->where('reservations.id', $request->input('id'))
            ->first()
        ;

        if ($reservation) {
            $companyOwner = Company::isCompanyUserBySlug($reservation->slug, Sentinel::getUser()->id);

            if ($companyOwner['is_owner'] == TRUE || Sentinel::inRole('admin')) {
                $reservation->table_nr = $request->input('tablenr');
                $reservation->save();
            }
        }
    }
        
    public function notifications(Request $request) 
    {  
        $notifications = Notification::with('media')
            ->where('is_on', 1)
        ;

        if ($request->has('id')) {
            $notifications = Notification::where('id', $request->input('id'));        
        }

        $notifications = $notifications->first();

        if ($notifications) {
            $mediaItems = $notifications->getMedia();

            if ($notifications) {
               $jsonArray['text'] = '
                '.(isset($mediaItems[0]) ? '<img width="'.($request->has('width') ? $request->input('width') : $notifications->width).'px" height="'.($request->has('height') ? $request->input('height') : $notifications->height).'px" src="'.url('public/'.$mediaItems[0]->getUrl()).'" /><br />' : '').'
                <div class="description">
                    '.$request->input('content').'
               </div>';

               $jsonArray['success'] = 1;
               $jsonArray['id'] = $notifications->id;
           }
        } else {
            $jsonArray['success'] = 0;
        }

        return isset($jsonArray) ? json_encode($jsonArray) : '';
    }

    public function adminCompaniesServices(Request $request) 
    {
        $data = CompanyService::select(
            'name',
            'content',
            'tax',
            'price'
        )
            ->where('company_id','=', $request->input('company'))
            ->get()
            ->toArray()
        ;

        $errorMessage = array(
            'error' => 'Uw gekozen bedrijf heeft geen producten. <a href="'.URL::to('admin/services/create').'" target="_blank">Klik hier</a> om nieuwe producten toe te voegen.'
        );

        return count($data) >= 1 ? json_encode($data) : json_encode($errorMessage);

    }

    public function adminCompaniesContract(Request $request) 
    {
        $data = Company::where('slug','=', $request->input('slug'));

        if (Sentinel::inRole('bedrijf') && Sentinel::inRole('admin') == FALSE)  {
            $data = $data->where('user_id', Sentinel::getUser()->id);
        }

        $data = $data->first();

        if ($data) {
            $documentItems = $data->getMedia('documents');

            foreach ($documentItems as $doc) {
                $documents[] = '<a href="'.url('public/'.$doc->getUrl()).'" target="_blank"><i class="file pdf outline red large icon"></i> Contract</a><br />';
            }
        }

        return isset($documents) ? implode(',', $documents) : 'Er is momenteel nog geen contract voor uw bedrijf.';
    }

    public function cashbackPopup(Request $request) 
    {
        $user = Sentinel::getUser();
        
        if ($user->cashback_popup == 0) {
            $user->cashback_popup = 1;
            $user->save();
            
            return json_encode(array('success' => 1));
        }
    }

    public function faq(Request $request) 
    {
        $faq = Faq::find($request->input('id'));
        $faq->clicks = ($faq->clicks + 1);
        $faq->save();
    }

    public function faqSubCategories(Request $request) 
    {
        $subcategories = FaqCategory::select(
            'id', 
            'name'
        )
            ->where('category_id', '=', $request->input('category'))
            ->get()
            ->toArray()
        ;

        return json_encode($subcategories);
    }

    public function faqSearch(Request $request) 
    {
        $faqQuery = Faq::select(
            'id', 
            'title',
            'answer'
        )
            ->where('title', 'LIKE', '%'.$request->input('q').'%')
            ->get()
        ;

        $faq = array();

        foreach ($faqQuery as $key => $info) {
            $faq[$key]['name'] =  $info->title;
            $faq[$key]['link'] = URL::to('faq?q='.$request->input('q'));
        }

        $searchHistory = SearchHistory::where("term", $request->input('q'))->first();
        if($searchHistory) {
            $searchHistory->count += 1;
            $searchHistory->save();
        }
        else {
            $searchHistory = new SearchHistory();
            $searchHistory->term = $request->input('q');
            $searchHistory->count = 1;
            $searchHistory->page = $request->path();
            $searchHistory->save();
        }

        $faqJson['items'] = $faq;

        return json_encode($faqJson);
    }

    public function cashbackInfo(Request $request)
    {
        $contentBlock1 = (isset(Content::getBlocks()[55]) ? Content::getBlocks()[55] : '');
        $contentBlock2 = (isset(Content::getBlocks()[19]) ? Content::getBlocks()[19] : '');

        return $contentBlock1.' '.$contentBlock2.'
            <div id="visitStore"></div><br />

            <input type="hidden" id="token" value="'.csrf_token().'" />
            <div class="ui checkbox" id="cashbackCheckbox">
                <input type="checkbox" class="checkbox" name="noDisplayCashback">
                <label>Niet meer weergeven</label>
            </div>';
    }

    public function cashbackSubcategories(Request $request) 
    {
        $subcategories = Category::select(
            'id', 
            'slug', 
            'name'
        )
            ->where('subcategory_id', '=', $request->input('id'))
            ->get()
            ->toJson()
        ;

        return $subcategories;
    }

    public function availableTime(Request $request) 
    {
        $times = CompanyReservation::getReservationTimesArray(
            array(
                'company_id' => array($request->input('company')), 
                'date' => date('Y-m-d', strtotime($request->input('date'))), 
                'selectPersons' => $request->input('persons'),
                'groupReservations' => ($request->has('group_res') ? $request->has('group_res') : NULL)
            )
        );

        return json_encode($times);   
    }

    public function availableDates(Request $request) 
    {
        $dates = CompanyReservation::getAllDates(
            $request->input('company'), 
            $request->input('year'), 
            ($request->has('month') ? $request->input('month') + 1 : ''),
            null,
            $request->input('persons')
        );

        foreach ($dates as $date) {
            $dateArray[]['date'] = $date['date'];
            $availablePersons = $date['availablePersons'];
        }  
        
        $finalJson = array(
            'dates' => isset($dateArray) ? $dateArray : array(),
            'availablePersons' => isset($availablePersons) ? $availablePersons : ''
        );

        return json_encode($finalJson, true);
    }

    public function availableReservation(Request $request) 
    {
        $datesArray = CompanyReservation::getReservationsDatesTimes(
            array($request->input('company')),
            $request->input('year'), 
            $request->input('month')
        );

        // $datesArray = CompanyReservation::getAllDates(
        //     $request->input('company'), 
        //     $request->input('year'), 
        //     $request->input('month' ),
        //     null,
        //     $request->input('persons')
        // );

        if (count($datesArray) >= 1) {
            foreach ($datesArray as $dateKey => $datesFetch) {
                foreach ($datesFetch as $dateKey => $date) {
                    if (count($date) > 0) {
                        $dateOutput[]['date'] = $dateKey;
                        $availablePersons = 2;
                    }  
                }  
            }  
        }  
        
        $finalJson = array(
            'dates' => isset($dateOutput) ? $dateOutput : array(),
            'availablePersons' => isset($availablePersons) ? $availablePersons : ''
        );

        return json_encode($finalJson, true);
    }

    public function users(Request $request)
    {
        $users = Sentinel::getUserRepository()->select(
            'id', 
            'name',
            'email'
        )
            ->where('name', 'LIKE', $request->input('q').'%')
            ->orWhere('email', 'LIKE', $request->input('q').'%')
            ->get()
            ->toArray()
        ;

        $user['items'] = $users;

        return json_encode($user);
    }

    public function guests($company, Request $request)
    {
        $guest = Sentinel::getUserRepository()->select(
            'users.name', 
            'users.email', 
            'users.phone'
        )
            ->leftJoin('guests', 'users.id', '=', 'guests.user_id' )
            ->leftJoin('companies', 'companies.id', '=', 'guests.company_id')
            ->where('company_id', $company)
            ->where(function ($query) use($request) {
                 $query
                    ->where('users.name', 'LIKE', $request->input('q').'%')
                    ->orWhere('users.email', 'LIKE', '%'.$request->input('q').'%')
                    ->orWhereRaw('(replace(users.phone, "-", "") LIKE "%'.str_replace('-', '', $request->input('q')).'%")')
                ;
            })
            ->get()
            ->toArray()
        ;

        $guests['items'] = $guest;

        return json_encode($guests);
    }

    public function barcodesCompanies(Request $request) 
    {
        $company = Company::select(
            'slug', 
            'name'
        )
            ->where('name', 'LIKE', $request->input('q').'%')
            ->get()
            ->toArray()
        ;

        foreach ($company as $key => $info) {
            $company[$key]['link'] = URL::to('admin/barcodes?company='.$info['slug']);
        }     

        $companies['items'] = $company;

        return json_encode($companies);
    }

    public function usersCompanies(Request $request) 
    {
        $termDivider = str_replace(' ', '|', $request->input('q'));

        $companies = Company::where('no_show', '=', 0)
            ->where(function ($query) use($request, $termDivider) {
                $query
                    ->where('name', 'LIKE', '%'.$request->input('q').'%')
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
            })
            ->with('media')
            ->get()
        ;

        $company = array();
        foreach ($companies as $key => $info) {
            $media = $info->getMedia('default');
            $company[$key]['name'] =  $info->name;
            $company[$key]['link'] = URL::to('restaurant/'.$info['slug']);

            if (isset($media[0])) {
                $company[$key]['image'] = url(($media[0]->getUrl('175Thumb')));
            } else {
               $company[$key]['image'] = url('public/images/placeholdimage.png');
            }     
        }     

        $companiesJson['items'] = $company;

        return json_encode($companiesJson);
    }

    public function adminCompaniesInvoices(Request $request) 
    {
        $company = Company::select(
            'slug', 
            'name'
        )
            ->where('name', 'LIKE', $request->input('q').'%')
            ->get()
            ->toArray()
        ;

        foreach ($company as $key => $info) {
            $company[$key]['link'] = URL::to('admin/invoices/overview/'.$info['slug']);
        }     

        $companies['items'] = $company;

        return json_encode($companies);
    }

    public function adminCompanies(Request $request) 
    {
        $company = Company::select(
            'slug', 
            'name'
        )
            ->where('name', 'LIKE', $request->input('q').'%')
        ;

        $second = Sentinel::getUserRepository()->select(
            'id as slug', 
            'name'
        )
            ->where('name', 'LIKE', $request->input('q').'%')
            ->union($company)
            ->get()
            ->toArray()
        ;

        foreach ($second as $key => $info) {
            if (is_numeric($info['slug'])) {
                $companies[$key]['link'] = URL::to('admin/reservations/saldo?user='.$info['slug']);
                $companies[$key]['name'] = $info['name'];
            } else {
                $companies[$key]['link'] = URL::to('admin/reservations/saldo/'.$info['slug']);
                $companies[$key]['name'] = $info['name'];
            }
        }     

        $companiesJson['items'] = isset($companies) ? $companies : '';

        return json_encode($companiesJson);
    }

    public function adminCompaniesOwners(Request $request) 
    {
        $owner = Sentinel::getUserRepository()->select(
            'users.id', 
            'users.name', 
            'users.email', 
            'users.phone'
        )
            ->whereIn('default_role_id', array(2,3))
            ->where('name', 'LIKE', $request->input('q').'%')
            ->get()
            ->toArray()
        ;

        $owners['items'] = $owner;

        return json_encode($owners);
    }

    public function adminCompaniesCallers(Request $request) 
    {
        $owner = Sentinel::getUserRepository()->select(
            'users.id', 
            'users.name', 
            'users.email', 
            'users.phone'
        )
            ->where('default_role_id', '=', 5)
            ->where('name', 'LIKE', $request->input('q').'%')
            ->get()
            ->toArray()
        ;

        $owners['items'] = $owner;

        return json_encode($owners);
    }

    public function adminCompaniesWaiters(Request $request) 
    {
        $owner = Sentinel::getUserRepository()->select(
            'users.id', 
            'users.name', 
            'users.email', 
            'users.phone'
        )
            ->where('default_role_id', '=', '4')
            ->where('name', 'LIKE', $request->input('q').'%')
            ->get()
            ->toArray()
        ;

        $owners['items'] = $owner;

        return json_encode($owners);
    }

    public function affiliates(Request $request)
    {
        $wordExplode = explode(' ', $request->input('q'));

        $affiliateQuery = Affiliate::select(
            'affiliates.slug', 
            'affiliates.name', 
            'affiliates.affiliate_network', 
            'affiliates.program_id',
            'affiliates.image_extension',
            'affiliates.compensations',
            'affiliates.no_show'
        )
            ->leftJoin('affiliates_categories', 'affiliates_categories.affiliate_id', '=', 'affiliates.id')
            ->leftJoin('categories', 'affiliates_categories.category_id', '=', 'categories.id')
            ->where('affiliates.no_show', '=', 0)
            ->where('affiliates.name', 'LIKE', '%'.$request->input('q').'%')
            ->orWhere('categories.name', 'LIKE', '%'.$request->input('q').'%');

        if (count($wordExplode) >= 1) {
            foreach ($wordExplode as $words) {
                $affiliateQuery = $affiliateQuery->orWhere('affiliates.name', 'LIKE', $words.'%');
            }
        }

        $affiliateQuery = $affiliateQuery
            ->groupBy('affiliates.name')
            ->limit(15)
            ->get()
            ->toArray()
        ;

        $affiliateHelper = new AffiliateHelper();
        
        $affiliate = array();
        
        foreach ($affiliateQuery as $key => $info) {
            if ($info['no_show'] == 0) {
                $affiliate[$key]['commission'] = (trim($info['compensations']) != '' ? $affiliateHelper->commissionMaxValue($info['compensations']) : '');
                $affiliate[$key]['link'] = URL::to('tegoed-sparen/company/'.$info['slug']);
                $affiliate[$key]['name'] = $info['name'];
                $affiliate[$key]['affiliate_network'] = $info['affiliate_network'];
                $affiliate[$key]['program_id'] = $info['program_id'];
                $affiliate[$key]['image'] = URL::to('images/affiliates/'.$info['affiliate_network'].'/'.$info['program_id'].'.'.$info['image_extension']);
//                $affiliate[$key]['image'] = URL::to('public/images/affiliates/'.$info['affiliate_network'].'/'.$info['program_id'].'.'.$info['image_extension']);
            }
        }     

        $affiliates['items'] = $affiliate;
        
         return json_encode($affiliates);
    }

    public function adminGuestsQuery(Request $request)
    {
        $queryString = array();

        if($request->has('limit')) {
            $queryString['limit'] = $request->input('limit');
        }

        if($request->has('sort')) {
            $queryString['sort'] = $request->input('sort');
        }

        if($request->has('order')) {
            $queryString['order'] = $request->input('order');
        }

        parse_str($request->input('query'), $getParameters);
        unset($getParameters['order']);
        unset($getParameters['limit']);
        unset($getParameters['sort']);

        if ($request->has('query')) {
            foreach ($getParameters as $key => $getParameter) {
                foreach ($getParameter as $getParameterValue) {
                    if ($key == 'dayno') {
                       $queryString['dayno'][$getParameterValue] = $getParameterValue;
                    }

                    if ($key == 'time') {
                       $queryString['time'][] = $getParameterValue;
                   }
                }
            }
        }

        if ($request->has('city')) {
            foreach ($request->input('city') as $days => $day) {
                $queryString['city'][$day] = $day;
            }
        }

        if ($request->has('allergies')) {
            foreach ($request->input('allergies') as $key => $time) {
                $queryString['allergies'][] = $time;
            }
        }

        if ($request->has('preferences')) {
            foreach ($request->input('preferences') as $key => $time) {
                $queryString['preferences'][] = $time;
            }
        }

        return urldecode(http_build_query($queryString));
    }
    
    public function adminReservationQuery(Request $request)
    {
        $queryString = array();

        if($request->has('limit')) {
            $queryString['limit'] = $request->input('limit');
        }

        if($request->has('sort')) {
            $queryString['sort'] = $request->input('sort');
        }

        if($request->has('order')) {
            $queryString['order'] = $request->input('order');
        }

        parse_str($request->input('query'), $getParameters);
        unset($getParameters['order']);
        unset($getParameters['limit']);
        unset($getParameters['sort']);

        if($request->has('query')) {
            foreach ($getParameters as $key => $getParameter) {
                foreach ($getParameter as $getParameterValue) {
                    if ($key == 'dayno') {
                       $queryString['dayno'][$getParameterValue] = $getParameterValue;
                    }

                    if ($key == 'time') {
                       $queryString['time'][] = $getParameterValue;
                   }
                }
            }
        }

        if($request->has('days')) {
            foreach ($request->input('days') as $days => $day) {
                $queryString['dayno'][$day] = $day;
            }
        }

        if($request->has('time')) {
            foreach ($request->input('time') as $key => $time) {
                $queryString['time'][] = $time;
            }
        }

        return http_build_query($queryString);
    }

    public function transferAction(Request $request)
    {
        if (count($request->input('categoryId')) >= 1) {
            $affiliates = explode(',', $request->input('affiliateIds'));
            $categoriesJson = implode(',', $request->input('categoryId'));
            $categories = explode(',', $categoriesJson);

            $affiliatesIds = array();

            foreach ($affiliates as $affiliate) {
                 foreach ($categories as $category) {
                    if (trim($category) != '') {
                        $affiliatesIds[] = array(
                            'affiliate_id' => $affiliate,
                            'category_id' => $category
                        );
                    }
                }
            }
            
            if (isset($affiliatesIds)) {
                AffiliateCategory::whereIn('affiliate_id', $affiliates)->delete();
                AffiliateCategory::insert($affiliatesIds);
            }
        }
    }
	/**
	* This function for update the extension
	*/
	public function updateExtension(Request $request){
		if($request->has('extension_id')) { // extension_id
            $extension_id = $request->input('extension_id');
        }
		if($request->has('ext_status')) { // ext_status
            $ext_status = $request->input('ext_status');
        }
		// update extension
		if(!empty($extension_id)){
			DB::update("UPDATE guest_list_extension SET id1 = {$ext_status} WHERE id = {$extension_id}");
			
			// create users if status marked enabled
			if($ext_status==1){
				$data  = DB::select(DB::raw("SELECT email, name, company_id FROM guests_wifi WHERE lower(substring_index(email, '@', -1)) IN (SELECT email_extension FROM guest_list_extension WHERE id='{$extension_id}')"));
				
				if(count($data)>0){
					$i=0;
					foreach($data as $d){
						// chheck user already exits or not
						$user = DB::table('users')
						->where('email', $d->email)
						->first();
						
						// insert user if not exists
						if(count($user)==0){
							// user password
							$extensions = explode("@",$d->email);						
							$unwanted=DB::table('unwanted_word')->get();
							foreach($unwanted as $unw){
								if (strpos($extensions[1], $unw->word) !== false || strpos($extensions[0], $unw->word) !== false) {
									return false;
								}
								
							}
							$pass = Hash::make('simple2568');
							$reference_code = str_random(64);
							DB::table('users')->insert(array('name'=>$d->name, 'email'=>$d->email, 'from_company_id'=>$d->company_id, 'password'=>$pass,'source'=>'wifi','reference_code'=>$reference_code,'extension_downloaded'=>0,'lang'=>'NL'));
							DB::table('guests_wifi')->where('email',$d->email)->delete();
						}
						$i++;
					}
				}
				$thirdpartyusers  = DB::select(DB::raw("SELECT email, name, restaurant_id FROM guests_third_party WHERE lower(substring_index(email, '@', -1)) IN (SELECT email_extension FROM guest_list_extension WHERE id='{$extension_id}')"));
				
				if(count($thirdpartyusers)>0){
					$i=0;
					foreach($thirdpartyusers as $d){
						// chheck user already exits or not
						$user = DB::table('users')
						->where('email', $d->email)
						->first();
						
						// insert user if not exists
						if(count($user)==0){
							// user password
							$extensions = explode("@",$d->email);						
							$unwanted=DB::table('unwanted_word')->get();
							foreach($unwanted as $unw){
								if (strpos($extensions[1], $unw->word) !== false || strpos($extensions[0], $unw->word) !== false) {
									return false;
								}
								
							}
							$reference_code = str_random(64);
							$pass = Hash::make('simple2568');
							DB::table('users')->insert(array('name'=>$d->name, 'email'=>$d->email, 'from_company_id'=>$d->restaurant_id, 'password'=>$pass,'source'=>'guest third party','reference_code'=>$reference_code,'extension_downloaded'=>0,'lang'=>'NL'));
							DB::table('guests_third_party')->where('email',$d->email)->delete();
						}
						$i++;
					}
				}
			}
			echo 'success';
		}else{
			
			
			echo 'fail';
		}
	}
	
	public function searchextension(Request $request){
		if($request->has('extension_text')) { // extension_text
            $extension_text = $request->input('extension_text');
        }
		
		
	}
}