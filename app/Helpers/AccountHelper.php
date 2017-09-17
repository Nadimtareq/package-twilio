<?php
namespace App\Helpers;
use App\User;
use DB;
use Sentinel;

class AccountHelper
{
/**
 * Gets the data about the profile.
 * @return array $profileStatus
 */
  public static function getProfileStatus(){
    $profileFields = ["name","email","preferences","kitchens","phone","price","newsletter","allergies","facilities","people","city","discount","sustainability","birthday_at"];
    $user = User::find(Sentinel::getUser()->id);
    if(!empty($user)){
      $status = 0;
      $remFields = [];
      foreach ($profileFields as $value) {
          if(!is_null($user->$value) && !empty($user->$value) && $user->$value!='[""]' && $user->$value!='null'){
            $status+=1;
          }
          else{
            array_push($remFields,$value);
          }
      }
      $status = round(($status/count($profileFields))*100);
      $profileStatus["remfields"] = $remFields;
      $profileStatus["completed"] = $status."%";
    }
    return ($profileStatus["completed"]);
  }

}
