<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Helpers\DealHelper;
use App\Models\GuestThirdParty;
use App\User;
use Sentinel;
use Setting;
use App\Models\WifiGuest;
use Illuminate\Support\Facades\Response;
use App\Models\Preference;

class TestcronjobController extends Controller
{
    //
    public function index()
    {
      DealHelper::sendNewsletterEmail();
    }

    public function test() {
    	mail("rushabhmadhu@gmail.com","croncalled","newslater cron");
    	DealHelper::sendNewsletterEmail();
    	echo "test";
    	exit;
    }
    public function guestMigrate(){
        $thirdParty = GuestThirdParty::select()
            ->get()
            ->toArray();
        $default_city = array(Setting::get('website.regio'));
        $new_record = 0;
        $existing_record = 0;
        if(!empty($thirdParty)) {
            foreach ($thirdParty as $key => $value) {
                $email = $value['email'];
                $default_password = 123456;
                $check_user_exists = User::where('email', $email)->exists();
                if(empty($check_user_exists)) {
                    
                    $name = $value['name'];
                    if(!empty($email)) {
                        $data = Sentinel::registerAndActivate(array(
                            'email' => $email,
                            'password' =>  $default_password
                        ));
                        
                        $data->name = $value['name'];
                        $data->phone = $value['phone'];
                        $data->city = json_encode($default_city);
                        $data->expire_code = str_random(64);
                        $data->expired_at = date('Y-m-d H:i', strtotime('+2 hours')).':00';
                        $data->terms_active = 1;
                        $data->save();
                        echo "Data saved to database<br/>";
                        $new_record++;
                    } else {
                        echo "Data already exists<br/>";
                        $existing_record++;
                    }
                    ob_flush(); # http://php.net/ob_flush
                    flush(); # http://php.net/flush
                    sleep(0.5);
                }
            }
        }
        echo "Report of All the Records<br/>New Records : $new_record<br/>Existing Records : $existing_record";exit;
    }
    public function updateWifiUser(Request $request){
        ini_set('max_execution_time', '800');
        if($request->has('source') && $request->has('city')) {
            $regioName = strtolower($request->input('city'));
            $source = strtolower($request->input('source'));
            $preferences = new Preference();
            $regio = $preferences->getRegio();
            $city_id = array($regio['regioNumber'][$regioName]);
            $new_record = 0;
            $existing_record = 0;
            $wifi_guests = WifiGuest::select()
            ->get()
            ->toArray();
            if(!empty($city_id) && !empty($wifi_guests)) {
                foreach ($wifi_guests as $key => $value) {
                    $email = $value['email'];
                    $default_password = 123456;
                    $user_info = User::where('email', $email)->first();
                    
                    if($user_info === null) {
                        $name = $value['name'];
                        $data = Sentinel::registerAndActivate(array(
                            'email' => $email,
                            'password' =>  $default_password
                        ));

                        $data->name = $value['name'];
                        $data->phone = $value['phone'];
                        $data->city = json_encode($city_id);
                        $data->expire_code = str_random(64);
                        $data->expired_at = date('Y-m-d H:i', strtotime('+2 hours')).':00';
                        $data->terms_active = 1;
                        $data->source = $source;
                        $data->save();
                        echo "Data saved to database<br/>";
                        $new_record++;
                    } else {
                        $id = !empty($user_info->id) ? $user_info->id : 0;
                        $user_city = !empty($user_info->city) ? json_decode($user_info->city) : array();
                        //check for add city if its not there
                        if(!empty($user_city) && !in_array($regio['regioNumber'][$regioName], $user_city)) {
                            array_push($user_city, $regio['regioNumber'][$regioName]);
                        } else if(empty($user_city)) {
                            $user_city = array($regio['regioNumber'][$regioName]);
                        }
                        
                        if(!empty($id)) {
                            $user = Sentinel::findById($id);
                            $user->city = json_encode($user_city);
                            $user->source = $source;
                            $user->save();
                        }
                        echo "Data already exists<br/>";
                        $existing_record++;
                    }
                    ob_flush(); 
                    flush();
                }
            }
            echo "Report of All the Records<br/>New Records : $new_record<br/>Existing Records : $existing_record";exit;
            
        } else {
            echo "Source and City can't be blank";exit;
        }
    }
}
