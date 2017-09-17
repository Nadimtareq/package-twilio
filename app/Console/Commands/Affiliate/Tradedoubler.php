<?php

namespace App\Console\Commands\Affiliate;

use Illuminate\Console\Command;
use App\Models\Category;
use App\Models\Affiliate;
use App\Models\AffiliateCategory;
use App\Helpers\AffiliateHelper;
use Exception;
use Intervention\Image\ImageManagerStatic;
use Intervention\Image\Exception\NotReadableException;
use Mail;
use DB;
use SoapClient;
use SoapFault;
use Request;
use Setting;
use anlutro\cURL\cURL;
use Illuminate\Support\Facades\File;

class Tradedoubler extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tradedoubler:affiliate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';
    
    /**
     * @var string
     */
    protected $affiliate_network = 'tradedoubler';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        
        $this->affiliateHelper = new AffiliateHelper;
        
        $this->lastAffiliates = Affiliate::select('id','name')->get();
        
        $this->affiliates = Affiliate::select('program_id')->where('affiliate_network', $this->affiliate_network)->get()->toArray();
        
        $this->categories = Category::select('id','slug','name')->where('subcategory_id', 0)->get()->toArray();
        
        $this->lastId = count($this->lastAffiliates) >= 1 ? $this->lastAffiliates->last()->id : 0;
    }
    
    public function checkConnection() {
    	
    	try {
    		set_time_limit(0);
    	
    		$curl = new cURL;
    		
    		$this->client = $curl->newRequest('GET', 'https://api.tradedoubler.com/1.0/conversions/subscriptions?token=5916178954ABB020FC2499E90851A3703B6E7137')
    		->setHeader('Content-Type', 'application/json')
    		->send();
    		
    		dd($this->client);
    		
    	 	return $this->client;
    	 
        } catch (SoapFault $fault) {
            echo "error";
        }
    	 
    	define("WSDL_LOGON", "https://api.affili.net/V2.0/Logon.svc?wsdl");
    	define("WSDL_PROGRAM", "https://api.affili.net/V2.0/PublisherProgram.svc?wsdl");
    	 
    	$soapLogon = new \SoapClient(WSDL_LOGON);
    	$this->token = $soapLogon->Logon(array(
    			'Username'  => Setting::get('settings.affilinet_name'),
    			'Password'  => Setting::get('settings.affilinet_pw'),
    			'WebServiceType' => 'Publisher'
    	));
    	 
    	$this->client = new \SoapClient(WSDL_PROGRAM);
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
    	$commandName = 'tradedoubler_affiliate';
         $dateTime=date('Y-m-d H:i:s');
        DB::table('crons_log')->insert(['cron_name' => $commandName, 'date_time' => $dateTime]);
    	//getClient = $this->checkConnection();
    	

//         if (Setting::get('cronjobs.'.$commandName) == NULL) {
//             echo 'This command is not working right now. Please activate this command.';
//         } else {
//             $getClient = $this->checkConnection();

//             try {
//                 if ($getClient) {
//                     if (Setting::get('cronjobs.active.'.$commandName) == NULL OR Setting::get('cronjobs.active.'.$commandName) == 0) {
//                         // Start cronjob
//                         $this->line(' Start '.$this->signature);
//                         Setting::set('cronjobs.active.'.$commandName, 1);
// //                         Setting::save();

//                         // Processing
// //                         $this->updateCampaigns(); // Update Campagins
//                         $this->addCampaigns(); // Add Campagins

//                         // End cronjob
//                         $this->line('Finished '.$this->signature);
//                         Setting::set('cronjobs.active.'.$commandName, 0);
//                         Setting::save();
//                     } else {
//                         // Don't run a task mutiple times, when the first task hasnt been finished
//                         $this->line('This task is busy at the moment.');
//                     }
//                 } else {
//                     $this->line('This task is not available, because there is no connection.');
//                 }
//             } catch (Exception $e) {
//                 $this->line('Er is een fout opgetreden. '.$this->signature);
               
//                 Mail::raw('Er is een fout opgetreden:<br /><br /> '.$e, function ($message) {
//                     $message->to(getenv('DEVELOPER_EMAIL'))->subject('Fout opgetreden: '.$this->signature);
//                 });
//             }
//         }
    }
}
