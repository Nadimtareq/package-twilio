<?php

namespace App\Console\Commands\Newsletter;

use App\Models\Reservation;
use App\Models\MailTemplate;
use App\Helpers\DealHelper;
use Mail;
use Illuminate\Console\Command;
use Exception;
use Setting;
use DB;

class NewsletterEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'newsletter:dealmail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatic send deals to users of city';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function sendNewsletter()
    {
      DealHelper::sendNewsletterEmail();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $commandName = 'newsletter_dealmail';
        $dateTime=date('Y-m-d H:i:s');
        DB::table('crons_log')->insert(['cron_name' => $commandName, 'date_time' => $dateTime]);
        Setting::set('cronjobs.active.'.$commandName, 0);
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
                    $this->sendNewsletter();
                } catch (Exception $e) {
                    $this->line('Er is een fout opgetreden. '.$this->signature.$e);

                    Mail::raw('Er is een fout opgetreden:<br /><br /> '.$e, function ($message) {
                        // $message->to(getenv('DEVELOPER_EMAIL'))->subject('Fout opgetreden: '.$this->signature);
                        $message->to('prateek.darmwal@gmail.com')->subject('Fout opgetreden: '.$this->signature);
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
