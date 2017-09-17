<?php

namespace App\Console\Commands\Transaction;

use Illuminate\Console\Command;
use App\Models\Transaction;
use App\Models\MailTemplate;
use App\User;
use Sentinel;
use Exception;
use Mail;
use DB;
use Setting;

class Que extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */   
    protected $signature = 'que:transaction';

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

    public function sendMail()
    {

                
                $transactions = Transaction::select(
                    'transactions.id',
                    'transactions.external_id',
                    'transactions.user_id',
                    'transactions.status',
                    'transactions.amount',
                    'affiliates.name as affiliatesName',
                    'users.id as userId',
                    'users.name as userName',
                    'users.email as userEmail'
                )
                    ->leftJoin('users', 'users.id', '=', 'transactions.user_id')
                    ->leftJoin('affiliates', function ($join) {
                        $join
                            ->on('transactions.program_id', '=', 'affiliates.program_id')
                            ->on('transactions.affiliate_network', '=', 'affiliates.affiliate_network')
                        ;
                    })
                    ->where('transactions.user_id', '!=', '0')
                    ->get()
                ; 
                
                foreach ($transactions as $transaction) {
                    $mailtemplate = new MailTemplate();

                    switch ($transaction->status) {
                        case 'rejected':
                            if ($transaction->getMeta('transaction_rejected') == NULL) {
                                $mailtemplate->sendMailSite(array(
                                    'email' => $transaction->userEmail,
                                    'template_id' => 'transaction_rejected',
                                    'replacements' => array(
                                        '%name%' => $transaction->userName,
                                        '%email%' => $transaction->userEmail,
                                        '%euro%' => $transaction->amount,
                                        '%webshop%' => $transaction->affiliatesName,
                                    )
                                ));

                                $transaction->addMeta('transaction_rejected', 1);
                            }
                        break;

                        case 'open':
                            if ($transaction->getMeta('transaction_open') == NULL) {
                                $mailtemplate->sendMailSite(array(
                                    'email' => $transaction->userEmail,
                                    'template_id' => 'transaction_open',
                                    'replacements' => array(
                                        '%name%' => $transaction->userName,
                                        '%email%' => $transaction->userEmail,
                                        '%euro%' => $transaction->amount,
                                        '%webshop%' => $transaction->affiliatesName,
                                    )
                                ));

                                $transaction->addMeta('transaction_open', 1);
                            }
                        break;

                        case 'accepted':
                            if ($transaction->getMeta('transaction_accepted') == NULL) {
                                $transaction->paid = 1;
                                $transaction->save();

                                $user = User::find($transaction->userId);

                                if ($user) {
                                    $user->saldo = ($user->saldo + $transaction->amount);
                                    $user->save();
                                }

                                $mailtemplate->sendMailSite(array(
                                    'email' => $transaction->userEmail,
                                    'template_id' => 'transaction_accepted',
                                    'replacements' => array(
                                        '%name%' => $transaction->userName,
                                        '%email%' => $transaction->userEmail,
                                        '%euro%' => $transaction->amount,
                                        '%webshop%' => $transaction->affiliatesName
                                    )
                                ));

                                $transaction->addMeta('transaction_accepted', 1);

                                $this->line('Set transaction #'.$transaction->id.'; User: '.$transaction->userName.'; as accepted');
                            }
                        break;
                    }
                }
    }

    public function removeDuplicates()
    {
        $transactions = Transaction::whereNotNull('external_id')
            ->havingRaw('count(*) > 1')
            ->groupBy('external_id')
            ->get()
        ;
        
        foreach ($transactions as $transaction) {
            $transactionArray[] = array(
                'externalId' => $transaction->external_id,
                'transactionId' => $transaction->id
            );
        }

        if (isset($transactionArray)) {
            foreach ($transactionArray as $transactionFetch) {
                Transaction::where('external_id', $transactionFetch['externalId'])
                    ->where('id', '!=', $transactionFetch['transactionId'])
                    ->delete()
                ;
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
        $commandName = 'que_transaction';
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
                    $this->removeDuplicates(); 
                    $this->sendMail(); 
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