<?php

namespace App\Console\Commands\Invoice;

use Illuminate\Console\Command;
use Request;
use App\User;
use DateTime;
use DatePeriod;
use DateInterval;
use DB;

class Reminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reminder:invoice';

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

    /**
     * Execute the console command.
     *
     * @return mixed
     */

    public function handle()
    {
         $dateTime=date('Y-m-d H:i:s');
        DB::table('crons_log')->insert(['cron_name' => 'Invoice reminder', 'date_time' => $dateTime]);

        if (\Setting::get('cronjobs.reminder_invoice') == NULL) {
            echo 'This command is not working right now. Please activate this command.';
        } else {
        }
    }

}
