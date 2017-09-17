<?php
namespace App\Http\Controllers\Admin;
use Mail;
use App;
use Alert;
use App\Http\Controllers\Controller;
use App\Models\CompanyService;
use App\Models\NewsletterJob;
use App\Models\Company;
use App\Models\Invoice;
use App\Helpers\DealHelper;
use Config;
use Sentinel;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use Intervention\Image\ImageManagerStatic;
use Intervention\Image\Exception\NotReadableException;
use Redirect;
use Setting;
use Illuminate\Console\Commands\Guest\Wifi;

use DB;
use URL;
use App\Models\TempAuthRedirectUrl;
use App\Models\TemporaryAuth;

class SettingsController extends Controller
{

    public function __construct(Request $request)
    {
       	$this->slugController = 'settings';
       	$this->section = 'Website instellingen';
    }

    public function index(Request $request)
    {
        $websiteSettings = json_decode(json_encode(Setting::get('website')), true);
        $discountSettings = json_decode(json_encode(Setting::get('discount')), true);
        $cronjobSettings = json_decode(json_encode(Setting::get('cronjobs')), true);
        $apiSettings = json_decode(json_encode(Setting::get('settings')), true);
        $kitchensSettings = json_decode(json_encode(Setting::get('filters')), true);
        $invoicesSettings = json_decode(json_encode(Setting::get('default')), true);

        $kitchens = Config::get('preferences.kitchens');
        $cities = array_values(Config::get('preferences.cities'));


        sort($cities);

        foreach ($cities as $key => $city) {
            $citiesArray[str_slug($city)] = $city;
        }

        $citiesData = App\Models\Preference::where('category_id', 9)->get();
        return view('admin/'.$this->slugController.'/index', [
            'cities' => $citiesArray,
            'citiesData' => $citiesData,
            'kitchens' => $kitchens,
            'slugController' => $this->slugController,
            'section' => $this->section,
            'currentPage' => 'Overzicht',
            'kitchensSettings' => $kitchensSettings,
            'cronjobSettings' => $cronjobSettings,
            'apiSettings' => $apiSettings,
            'discountSettings' => $discountSettings,
            'invoicesSettings' => $invoicesSettings,
            'websiteSettings' => $websiteSettings
        ]);
    }

    public function indexAction(Request $request)
    {
        $requests = $request->all();
        unset($requests['_token']);

        $settingsArray = array(
            'affilinet_name',
            'affilinet_pw',
            'daisycon_name',
            'daisycon_pw',
            'tradetracker_name',
            'tradetracker_pw',
            'tradedoubler_name',
            'tradedoubler_pw',
            'zanox_name',
            'zanox_pw',
            'awin_name',
            'awin_pw',
            'hotspot_pw',
			'mailgun_key',
            'callcenter_reminder',
            'callcenter_reminder_status'
        );

        foreach ($requests as $key => $value) {
            if (in_array($key, $settingsArray)) {
                Setting::set('settings.'.$key, $value);
            }
        }

        Alert::success('De instellingen zijn succesvol aangepast.')->persistent('Sluiten');

        return Redirect::to('admin/settings');
    }

    public function cronjobsAction(Request $request)
    {
        $requests = $request->all();
        unset($requests['_token']);
		
        Setting::forget('cronjobs');


        foreach ($requests as $key => $value) {
            Setting::set('cronjobs.'.$key, 1);
        }

        Alert::success('De instellingen zijn succesvol aangepast.')->persistent('Sluiten');

        return Redirect::to('admin/settings');
    }

    public function invoicesAction(Request $request)
    {
        if ($request->isMethod('post')) {
            $requests = $request->all();

            Setting::set('default.services_noshow', $request->input('services_noshow'));
            Setting::set('default.services_name', $request->input('name'));
            Setting::set('default.services_price', $request->input('price'));
            Setting::set('default.services_tax', $request->input('tax'));

            Alert::success('De instellingen zijn succesvol aangepast.')->persistent('Sluiten');

            return Redirect::to('admin/settings');
        } else {
            alert()->error('', 'Het formulier is niet correct ingevuld.')->persistent('Sluiten');
            return Redirect::to('admin/settings');
        }
    }

    public function websiteAction(Request $request)
    {
        if ($request->isMethod('post')) {
            $requests = $request->all();

            Setting::set('website.facebook', $request->input('facebook'));
            Setting::set('website.source', $request->input('source'));
            Setting::set('website.regio', $request->input('regio'));

            Alert::success('De instellingen zijn succesvol aangepast.')->persistent('Sluiten');

            return Redirect::to('admin/settings');
        } else {
            alert()->error('', 'Het formulier is niet correct ingevuld.')->persistent('Sluiten');
            return Redirect::to('admin/settings');
        }
    }

    public function eetnuAction(Request $request)
    {
        if ($request->isMethod('post')) {
            $requests = $request->all();

            Setting::set('filters.kitchens', json_encode($request->input('kitchens')));

            if (trim($request->input('cities')) != '') {
                Setting::set('filters.cities', json_encode(array_values($request->input('cities'))));
            } else {
                Setting::set('filters.cities', '');
            }

            Alert::success('De instellingen zijn succesvol aangepast.')->persistent('Sluiten');

            return Redirect::to('admin/settings');
        } else {
            alert()->error('', 'Het formulier is niet correct ingevuld.')->persistent('Sluiten');
            return Redirect::to('admin/settings');
        }
    }

    public function discountAction(Request $request)
    {
        if ($request->isMethod('post')) {
            $requests = $request->all();

            $files = array(
                'discount_image',
                'discount_image2',
                'discount_image3',
                'discount_image4',
            );

            if ($request->has('remove_image2')) {
                Setting::forget('discount.discount_image2');
            }

            if ($request->has('remove_image2')) {
                Setting::forget('discount.discount_image3');
            }

            foreach ($files as $id => $file) {
                if ($request->hasFile($file)) {
                    try {
                        ImageManagerStatic::make($request->file($file))->save(public_path('images/voordeelpas.'.$request->file($file)->getClientOriginalExtension()));
                    } catch (NotReadableException $e) {
                    }

                    Setting::set('discount.'.$file, 'images/voordeelpas.'.$request->file($file)->getClientOriginalExtension());
                }
            }

            Setting::set('discount.discount_width', $request->input('discount_width'));
            Setting::set('discount.discount_width2', $request->input('discount_width2'));
            Setting::set('discount.discount_width3', $request->input('discount_width3'));
            Setting::set('discount.discount_width4', $request->input('discount_width4'));

            Setting::set('discount.discount_height', $request->input('discount_height'));
            Setting::set('discount.discount_height2', $request->input('discount_height2'));
            Setting::set('discount.discount_height3', $request->input('discount_height3'));
            Setting::set('discount.discount_height4', $request->input('discount_height4'));

            Setting::set('discount.discount_old', $request->input('discount_old'));
            Setting::set('discount.discount_new', $request->input('discount_new'));
            Setting::set('discount.discount_old3', $request->input('discount_old3'));
            Setting::set('discount.discount_new3', $request->input('discount_new3'));

            Setting::set('discount.discount_url', $request->input('discount_url'));
            Setting::set('discount.discount_url2', $request->input('discount_url2'));
            Setting::set('discount.discount_url3', $request->input('discount_url3'));

            Alert::success('De instellingen zijn succesvol aangepast.')->persistent('Sluiten');

            return Redirect::to('admin/settings');
        } else {
            alert()->error('', 'Het formulier is niet correct ingevuld.')->persistent('Sluiten');
            return Redirect::to('admin/settings');
        }
    }

    public function run(Request $request, $slug)
    {
		
        switch ($slug) {
            case 'affilinet':
                Setting::set('cronjobs.affilinet_affiliate', 1);
                break;

            case 'tradetracker':
                Setting::set('cronjobs.tradetracker_affiliate', 1);
                break;

            case 'zanox':
                Setting::set('cronjobs.zanox_affiliate', 1);
                break;

            case 'daisycon':
                Setting::set('cronjobs.daisycon_affiliate', 1);
                break;

            case 'tradedoubler':
                Setting::set('cronjobs.tradedoubler_transaction', 1);
                break;

            case 'hotspot':
               Setting::set('cronjobs.wifi_guest', 1);			   
                break;
			
			case 'mailgun':
                Setting::set('cronjobs.wifi_guest', 1);
                break;
        }

        Alert::success('De gekozen api wordt nu uitgevoerd.')->persistent('Sluiten');

        return Redirect::to('admin/settings');
    }

	public function newsletterAction(Request $request)
	{
		 if ($request->isMethod('post')) {
       $data = $request->all();

       foreach ($data['date_jobs'] as $key => $value) {
         # code...
         $city = $key;
         $days = json_encode($value);
         $time = json_encode($data['time_jobs'][$key]);
         $status = $data['status_jobs'][$key];
         NewsletterJob::updateOrCreate(['city_id' => $city],['city_id' => $city,'date' => $days, 'time' => $time, 'status' => $status]);
       }


       Alert::success('De gekozen api wordt nu uitgevoerd.')->persistent('Sluiten');

       return Redirect::to('admin/settings');
	  }
		else {
			  alert()->error('', 'Het formulier is niet correct ingevuld.')->persistent('Sluiten');
			  return Redirect::to('admin/settings');
		}

	}
	
	 public function hotspotAction(Request $request)
    {
        if ($request->isMethod('post')) {
            $requests = $request->all();
			$wifikey= $request->input('hotspot_pw');
			
			$this->addGuests($wifikey);
           

            Alert::success('De instellingen zijn succesvol aangepast.')->persistent('Sluiten');

            return Redirect::to('admin/settings');
        } else {
            alert()->error('', 'Het formulier is niet correct ingevuld.')->persistent('Sluiten');
            return Redirect::to('admin/settings');
        }
    }
	public function getLocations($wifikey)
    {
		$headers = array(
			  'sn-apikey: ' . $wifikey
			);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.hotspotsystem.com/v2.0/locations');
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CAINFO, base_path('cacert.pem'));
        curl_setopt($ch, CURLOPT_VERBOSE, true);

        $response = curl_exec($ch);
        $info = curl_getinfo($ch);

        if ($info['http_code'] === 200) {
            $json = json_decode($response, true);

            return $json['items'];
        }

        curl_close($ch);
    }

    public function getCustomers($locationId, $i,$wifikey)
    {
		$headers = array(
			  'sn-apikey: ' . $wifikey
			);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.hotspotsystem.com/v2.0/locations/'.$locationId.'/customers?limit=100&offset='.$i);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CAINFO, base_path('cacert.pem'));
        curl_setopt($ch, CURLOPT_VERBOSE, true);

        $response = curl_exec($ch);
        $info = curl_getinfo($ch);

        if ($info['http_code'] === 200) {
            $json = json_decode($response, true);
            return $json['items'];
        }

        curl_close($ch);
    }
 public function addGuests($wifikey)
    {
        $zipcodesArray = array();
        $zipcodeLocation = array();
		
		$locations=$this->getLocations($wifikey);	
        // Get locations
        foreach ($locations as $location) {
            $zipcode = preg_replace('/\s+/', '', $location['zip']);

            $zipcodesArray[] = $zipcode;
            $zipcodeLocation[$zipcode][strtolower($location['address'])] = (int) $location['id'];
        }

        // Get company by zipcode
        if (count($zipcodesArray) >= 1) {
            $zipcodesImplode = implode("', '", $zipcodesArray);

            $companies = Company::whereRaw("REPLACE(zipcode, ' ', '') IN('".$zipcodesImplode."')")
                ->where('no_show', 0)
                ->get()
            ;
			
            foreach ($companies as $company) {
                $zipcode = preg_replace('/\s+/', '', $company->zipcode);

                if (isset($zipcodeLocation[$zipcode][strtolower($company->address)])) {
                    $locationCompanies[] = array(
                        'regio' => $company->regio,
                        'name' => $company->name,
                        'id' => $company->id,
                        'locationId' => $zipcodeLocation[$zipcode][strtolower($company->address)]
                    );
                }
            }

            Setting::set('last_wifi_row', Setting::get('last_wifi_row') + 25);
            Setting::save();

            $lastPage = Setting::get('last_wifi_row');

            foreach ($locationCompanies as $locationArrayId => $locationArray) {
                $locationId = $locationArray['locationId'];

                for ($i = ($lastPage == 25 ? 0 : $lastPage); $i < ($lastPage == 25 ? 25 : $lastPage + 25); $i++) { 
                    if (count($this->getCustomers($locationId, $i,$wifikey)) > 0) {
                        foreach ($this->getCustomers($locationId, $i,$wifikey) as $customer) {
                            if (
                                $customer['email'] != null
                                && $customer['name'] != null
                                && $this->checkEmailAndDomain($customer['email']) == TRUE
                            ) {
                                $jsonRegio = (is_array(json_decode($locationArray['regio'])) ? $locationArray['regio'] : json_encode((array) $locationArray['regio']));

                                $customerArray[$locationId][str_slug($customer['name'])] = array(
                                    'name' => ucwords($customer['name']),
                                    'email' => strtolower($customer['email']),
                                    'phone' => $customer['phone'],
                                    'regio' => $jsonRegio,
                                    'com' => $locationArray['name']
                                );
                            }
                        }
                    }
                }

                if (isset($customerArray[$locationId])) {
                    foreach ($customerArray[$locationId] as $customer) {
                        $userCheck = Sentinel::findByCredentials(array('login' => $customer['email']));
               
                        $randomPassword = str_random(20);

                        // Add new User
                        if (count($userCheck) == 0) {
                            $user = Sentinel::registerAndActivate(array(
                                'email' => $customer['email'],
                                'password' => $randomPassword
                            ));

                            $user->name = $customer['name'];
                            $user->phone = $customer['phone'];
                            $user->expire_code = str_random(64);
                            $user->terms_active = 1;

                            if (trim($customer['regio']) != '') {
                                $user->city = json_encode((array) $customer['regio']);
                            }

                            $user->save();

                            $userId = $user->id;
                        } else {
                            $userId = $userCheck->id;
                        }

                        $guest = new Guest();

                        $guest->addGuest(array(
                            'user_id' => $userId,
                            'company_id' => $locationArray['id']
                        ));
						$extensions = explode("@",$customer['email']);
						$extenables=DB::table('guest_list_extension')->where('email_extension',$extensions[1])->first();
						
						if($extenables->id1==1){
							// chheck user already exits or not
							$user = DB::table('users')
							->where('email', $customer['email'])
							->first();
							
							// insert user if not exists
							if(count($user)==0){
								// user password
														
								$unwanted=DB::table('unwanted_word')->get();
								foreach($unwanted as $unw){
									if (strpos($extensions[1], $unw->word) !== false || strpos($extensions[0], $unw->word) !== false) {
										return false;
									}
									
								}
								$pass = Hash::make('simple2568');
								$reference_code = str_random(64);
								DB::table('users')->insert(array('name'=>$customer['name'], 'email'=>$customer['email'], 'phone'=>$customer['phone'], 'from_company_id'=>$locationArray['id'], 'password'=>$pass,'source'=>'wifi','reference_code'=>$reference_code,'extension_downloaded'=>0,'lang'=>'NL'));
								
							}
							
						}else{
					
                        // Add as wifi guest
                        $wifiGuestCheck = WifiGuest::where('email', $customer['email'])
                            ->where('company_id', $locationArray['id'])
                            ->get()
                        ;

                        if (count($wifiGuestCheck) == 0) {
                            $wifiguest = new WifiGuest();							
                            $wifiguest->name = $customer['name'];
                            $wifiguest->email = $customer['email'];
                            $wifiguest->phone = $customer['phone'];
                            $wifiguest->company_id = $locationArray['id'];							
                            $wifiguest->save();
                        }
						}
                    }
                }
            }
        } 
    }
	
	 private function sendNewsletter()
	  {
		$newsletterJobs = NewsletterJob::all()->where('status',1);
		//dd($newsletterJobs);
		$newsletters = [];
		mail('rushabhmadhu@gmail.com','Deal Email','Deal email is called'.date('Y-m-d H:i:s'));
		foreach ($newsletterJobs as $job) {
			$deals = $this->getDeals($job->city_id);
			$users = $this->getSubscribedUsers($job->city_id);
			if(count($deals)>0 && count($users)>0) $this->sendDealsToUser($deals,$users);
		}
	 }
	 private function getDeals($city_id)
	  {
		# code...
		$deals = array();
		
		$data = DB::table('companies')->select('companies.*')->join('users','companies.user_id','=','users.id')->where('users.city','LIKE','%"'.$city_id.'"%')->get();
		foreach ($data as $company) {
		  # code...
		  $deals[$company->slug] = DB::table('reservations_options')->where([['company_id',$company->id],['newsletter', 1]])->get();
		}
		return $deals;
	  }
	  
	  private function getSubscribedUsers($city_id)
  {
    # code...
    $users = DB::table('users')->where([['city','LIKE','%"'.$city_id.'"%'],['newsletter',1]])->get(['id','email','name','saldo','extension_downloaded']);
    return $users;
  }

  /**
   * Queue Mail for sending deals.
   * @param array $deals, $users
   */
  private function sendDealsToUser($deals=[],$users=[])
  {
    # code...
    if(!empty($deals) && !empty($users)){
      foreach ($users as $user) {
        # code...
        $data = $this->createTempAuth($user,$deals);
        Mail::queue('emails.deals',['user' => $data[0],'deals' => $data[1]],function($message) use ($user){
          $message->to($user->email)->subject('UW Voordeelpas - De beste deals');
        });
      }
    }
  }

  
  private function createTempAuth($user,$deals)
  {
    # code...
    if(!empty($user) && !empty($deals)){
      $tempAuth = new TemporaryAuth;
      $user->{'saldo_url'}=$tempAuth->createCode($user->id,'account/reservations/saldo');
      $user->{'unsubscribe_url'}=$tempAuth->createCode($user->id,'unsubscribe/'.$user->id);
      $user->{'extension_download_url'}=$tempAuth->createCode($user->id,'?extension_download_btn=1');
      foreach ($deals as $key => $restaurant) {
        # code...
        foreach ($restaurant as $index => $deal) {
          # code...
          $url = 'future-deal/'.$key.'?deal='.$deal->id;
          $deals[$key][$index]->{'deal_url'} = $tempAuth->createCode($user->id,$url);
        }
      }

    }
    $data = [$user,$deals];
    return $data;
  }

}
