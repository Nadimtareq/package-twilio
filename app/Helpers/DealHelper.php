<?php
namespace App\Helpers;
use App\User;
use App\Models\ReservationOption;
use App\Models\NewsletterJob;
use App\Models\Company;
use App\Models\Preference;
use App\Models\TempAuthRedirectUrl;
use App\Models\TemporaryAuth;
use Carbon\Carbon;
use Mail;
use DB;
use URL;

class DealHelper
{

  /**
   * Sends the newsletter to the subscribed user.
   */
  public static function sendNewsletterEmail(){
    $newsletter = new DealHelper;
    $jobs = $newsletter->getNewsletter();
  }

  /**
   * Gets the newsleter to be sent on current day and time.
   * @return newsletterArray.
   
  private function getNewsletter()
  {
    $newsletterJobs = NewsletterJob::all()->where('status',1);
    $newsletters = [];
    foreach ($newsletterJobs as $job) {
      # code...
      $data = $this->getDateTime(json_decode($job->date),json_decode($job->time));
      if($data){
        $deals = $this->getDeals($job->city_id);
        $users = $this->getSubscribedUsers($job->city_id);
        $this->sendDealsToUser($deals,$users);
      }
    }
  }
  */
   private function getNewsletter()
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
  

  /**
   * Gets the date and time of the newsletter.
   * @param $dateArray, $timeArray
   * @return boolean true/false
   */
   private function getDateTime($dateArray=[],$timeArray=[])
   {
     # code...
     $dt = Carbon::now();
     $day = strtolower($dt->format('l'));
     $hour = $dt->hour;
     $dateTime = [];
     foreach ($dateArray as $key => $value) {
       # code...
       if($value==trans("app.$day")){
         $date = $value;
         $currentHour = $hour.":00:00";
         $time = !empty($timeArray[$key]) ? $timeArray[$key].":00" : $currentHour;
         if(intval($time) >= intval($hour) && intval($time) < intval($hour)+1){
           return true;
         }
       }
     }
     return false;
   }

   /**
    * Gets the related deals from the city.
    * @param int $city_id
    * @return array $deals.
    */
  private function getDeals($city_id)
  {
    # code...
    $deals = array();
    //$data = Company::where('regio','LIKE','%"'.$city_id.'"%')->get(['id','name','slug','regio']);
    $data = DB::table('companies')->select('companies.*')->join('users','companies.user_id','=','users.id')->where('users.city','LIKE','%"'.$city_id.'"%')->get();
    foreach ($data as $company) {
      # code...
      $deals[$company->slug] = DB::table('reservations_options')->where([['company_id',$company->id],['newsletter', 1]])->get();
    }
    return $deals;
  }

  /**
   * Gets the list of users who subscribed to the city newsletter.
   * @param int $city_id
   * @return array $users.
   */
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

  /**
   * Create TemporaryAuth for the user.
   * @param array $user,$deals.
   * @return auth id.
   */
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
