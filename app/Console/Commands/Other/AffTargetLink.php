<?php

namespace App\Console\Commands\Other;

use Illuminate\Console\Command;
use App;
use App\Models\Affiliate;
use DB;
use Exception;
use URL;

class AffTargetLink extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'afftargetlink:other';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Affiliate repair domain';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {    
     $dateTime=date('Y-m-d H:i:s');
        DB::table('crons_log')->insert(['cron_name' => 'Affiliate repair domain', 'date_time' => $dateTime]);    
        $affs = DB::table('affiliates')->select('id', 'link', 'slug', 'name')->get();
        foreach ($affs as $aff) {
            if ($this->isShortUrl($aff->link)) {
                // CODE TO STORE SHORTEN URL
            } else {                
                $domain = $this->reconstructUrl($aff->link);                
                DB::table('affiliates')->where('id', $aff->id)->update(['domain' => $domain]);
            }
        }
//          $this->line('This task is busy at the moment.'); 
    }

    public function isShortUrl($url) {
        // 1. Overall URL length - May be a max of 30 charecters
//        if (strlen($url) > 30)
//            return false;

        $parts = parse_url($url);
        if (isset($parts["host"])) {
            $host = $parts["host"];
            $short_url_hosts = array('dt51.net', 'lt45.net', 'at19.net', 'ds1.nl');
            if (in_array($host, $short_url_hosts)) {
                return true;
            }
        }
        return false;
    }

    public function reconstructUrl($url) {
        $url_parts = parse_url($url);        
        $return_url = "";
        if(isset($url_parts['scheme'])){
            $return_url .= $url_parts['scheme'] . '://';
        }
        if(isset($url_parts['host'])){
            $return_url .= $url_parts['host'];
        } elseif(isset($url_parts['path'])){
            $return_url .= $url_parts['path'];
        }
        

        return $return_url;
    }

}
