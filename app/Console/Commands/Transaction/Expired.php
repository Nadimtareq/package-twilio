<?php

namespace App\Console\Commands\Transaction;

use Illuminate\Console\Command;
use App\Models\Transaction;
use Exception;
use Setting;
use Mail;
use DB;

class Expired extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */ 
    protected $signature = 'expired:transaction';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function setAsExpired()
    {
        $this->transaction = Transaction::select(
                    'id',
                    'created_at'
                )
                    ->where('status', '=', 'accepted')
                    ->whereRaw('date(date_add(created_at, interval 90 day)) <= "'.date('Y-m-d').'"')
                    ->get()
                ; 
                $expired_ids = array();
                if (count($this->transaction) >= 1) {                    
                    foreach ($this->transaction as $transaction) {
                        $expired_ids[] = $transaction->id;
                    }                    
                    if(!empty($expired_ids)){                     
                        Transaction::whereIn('id', $expired_ids)->update(['status' => 'expired']);
                    }
                }
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $commandName = 'expired_transaction';
 $dateTime=date('Y-m-d H:i:s');
        DB::table('crons_log')->insert(['cron_name' => $commandName, 'date_time' => $dateTime]);
        if (Setting::get('cronjobs.'.$commandName) == NULL) {
            echo 'This command is not working right now. Please activate this command.';
        } else {
            if (Setting::get('cronjobs.active.'.$commandName) == NULL OR Setting::get('cronjobs.active.'.$commandName) == 0) {
                // Start cronjob
                $this->line(' Start '.$this->signature);
                Setting::set('cronjobs.active.'.$commandName, 1);
                Setting::save();

                // Processing
                try {
                    $this->setAsExpired(); 
                } catch (Exception $e) {
                    $this->line('Er is een fout opgetreden. '.$this->signature);
                   
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
    }
}
