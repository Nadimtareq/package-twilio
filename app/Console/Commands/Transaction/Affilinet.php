<?php

namespace App\Console\Commands\Transaction;

use Illuminate\Console\Command;
use anlutro\cURL\cURL;
use App\Models\Transaction;
use App\User;
use Config;
use Sentinel;
use Exception;
use SoapClient;
use Setting;
use Mail;
use DB;

class Affilinet extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */   
    protected $signature = 'affilinet:transaction';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * The affiliate network
     *
     * @var string
     */
    protected $affiliate_network = 'affilinet';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->transaction = Transaction::select('external_id')->where('affiliate_network', $this->affiliate_network)->get()->toArray();
    }
    
    public function addTransactions()
    {
    	$status = Config::get('preferences.transactionStatus');
    	$transactions = $this->getTransactions();
    	if(!empty($transactions->TransactionCollection->Transaction)) {
    		foreach ($transactions->TransactionCollection->Transaction as $transaction) {
    			if (!in_array($transaction->TransactionId, array_flatten($this->transaction))) {
    				$insertTransaction[] = array(
    						'program_id' => $transaction->ProgramId,
    						'ip' => '',
    						'external_id' => $transaction->TransactionId,
    						'user_id' => 0,
    						'status' => $status[strtolower($transaction->TransactionStatus)],
    						'processed' => date('Y-m-d H:i:s', strtotime($transaction->CheckDate)),
    						'amount' => number_format($transaction->PublisherCommission / 100 * 70, 2),
    						'affiliate_network' => $this->affiliate_network,
    						'created_at' => date('Y-m-d H:i:s')
    				);
    			} else {
    				$transactionUpdate = Transaction::where('external_id', $transaction->TransactionId)
    				->where('affiliate_network', $this->affiliate_network)
    				->first()
    				;
    				 
    				if ($transactionUpdate->getMeta('transaction_stop_changes') == NULL) {
    					if ($transactionUpdate) {
    						if ($transactionUpdate->status == 'open' && $status[strtolower($transaction->TransactionStatus)] == 'accepted') {
    							$transactionUpdate->processed = date('Y-m-d H:i:s');
    						}
    						 
    						$transactionUpdate->status = $status[strtolower($transaction->TransactionStatus)];
    						$transactionUpdate->save();
    					}
    				}
    			}
    		}
    		 
    		if (isset($insertTransaction)) {
    			Transaction::insert($insertTransaction);
    		}
    	}
    }
    
    public function getTransactions() {
    	define("WSDL_LOGON", "https://api.affili.net/V2.0/Logon.svc?wsdl");
    	define("WSDL_STATS", "https://api.affili.net/V2.0/PublisherStatistics.svc?wsdl");
    	
    	$soapLogon = new \SoapClient(WSDL_LOGON);
    	$token = $soapLogon->Logon(array(
    			'Username'  => env("AFFILINET_SITE_ID"),
    			'Password'  => env("AFFILINET_PUBLISHER_PASS"),
    			'WebServiceType' => 'Publisher'
    	));
    	// Set page setting parameters
    	$pageSettings = array('CurrentPage' => 1, 'PageSize' => 100);
    	 
    	// Set transaction query parameters
    	$startDate = strtotime("-2 weeks");
    	$endDate = strtotime("today");
    	$rateFilter = array('RateMode' => 'PayPerSale','RateNumber' => 1);
    	 
    	$transactionQuery = array(
    			'StartDate' => $startDate,
    			'EndDate' => $endDate,
    			'RateFilter' => $rateFilter,
    			'TransactionStatus' => 'All',
    			'ValuationType' => 'DateOfRegistration'
    	);
    	 
    	$soapRequest  = new \SoapClient(WSDL_STATS);
    	 
    	$transactionData = $soapRequest->GetTransactions(array(
    			'CredentialToken' => $token,
    			'PageSettings' => $pageSettings,
    			'TransactionQuery' => $transactionQuery
    	));
    	 
    	return $transactionData;
    }
    

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
    	
    	$commandName = 'affilinet_transaction';
    	 $dateTime=date('Y-m-d H:i:s');
        DB::table('crons_log')->insert(['cron_name' => $commandName, 'date_time' => $dateTime]);
    	if (Setting::get('cronjobs.'.$commandName) == NULL) {
    		echo 'This command is not working right now. Please activate this command.';
    	} else {
    		Setting::set('cronjobs.active.'.$commandName, 0);
    		Setting::save();
    		if (Setting::get('cronjobs.active.'.$commandName) == NULL OR Setting::get('cronjobs.active.'.$commandName) == 0) {
    			// Start cronjob
    			$this->line(' Start '.$this->signature);
    			Setting::set('cronjobs.active.'.$commandName, 1);
    			Setting::save();
    	
    			// Processing
    			try {
    				$this->addTransactions();
    			} catch (Exception $e) {
    				$this->line($e->getMessage() . $e->getLine());
    				 
    				Mail::raw('Er is een fout opgetreden:<br /><br /> '.$e, function ($message) {
    					$message->to(getenv('DEVELOPER_EMAIL'))->subject('Fout opgetreden: '.$this->signature);
    				});
    			}
    			// End cronjob
    			$this->line('Finished '.$this->signature);
    			Setting::set('cronjobs.active.'.$commandName, 0);
    			Setting::save();
    		} else {
    			// Don't run a task mutiple times, when the first task hasnt been finished
    			$this->line('This task is busy at the moment.');
    		}
    	}
    	
        // // Set webservice endpoints
        // define("WSDL_LOGON", "https://api.affili.net/V2.0/Logon.svc?wsdl");
        // define("WSDL_STATS", "https://api.affili.net/V2.0/PublisherStatistics.svc?wsdl");
         
        // // Set credentials
        // $username = ''; // the publisher ID
        // $password = ''; // the publisher web services password
         
        // // Send a request to the Logon Service to get an authentication token
        // $soapLogon = new SoapClient(WSDL_LOGON);
        // $token = $soapLogon->Logon(array(
        //     'Username' => Setting::get('settings.affilinet_name'),
        //     'Password' => Setting::get('settings.affilinet_pw'),
        //     'WebServiceType' => 'Publisher'
        // ));
         
        // // Set page setting parameters
        // $pageSettings = array(
        //     'CurrentPage' => 1,
        //     'PageSize' => 5,
        // );
         
        // // Set transaction query parameters
        // $startDate = strtotime("-2 weeks");
        // $endDate = strtotime("today");
        
        // $rateFilter = array(
        //     'RateMode' => 'PayPerSale',
        //     'RateNumber' => 1
        // );

        // $transactionQuery = array(
        //     'StartDate' => $startDate,
        //     'EndDate' => $endDate,
        //     'RateFilter' => $rateFilter,
        //     'TransactionStatus' => 'All',
        //     'ValuationType' => 'DateOfRegistration'
        // );
         
        // // Send a request to the Publisher Statistics Service
        // $soapRequest = new SoapClient(WSDL_STATS);
        // $response = $soapRequest->GetTransactions(array(
        //     'CredentialToken' => $token,
        //     'PageSettings' => $pageSettings,
        //     'TransactionQuery' => $transactionQuery
        // ));
         
        // // Show response
        // print_r($response);
    }
}