<?php

namespace App\Http\Controllers;

use App\Models\Affiliate;
use App\Helpers\AffiliateHelper;
use App\User;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Sentinel;
use App\Http\Requests;
use Illuminate\Support\Facades\DB;

class ApiController extends Controller {

    public function checkAuth() {
        $return_user = $current_user = $default_user = '';
        $current_user = Sentinel::getUser();
        if ($current_user) {
            $return_user = $current_user;
        } else {
            $default_user = User::where('email', 'martijn@uwvoordeelpas.nl')->first();
            $return_user = $default_user;
        }
        return $return_user;
    }

    public function findProgram($userId, $url) {
        $match_links_array = $slash_links = array();
        $match_links_array[] = $without_http_or_https = preg_replace('/https:\/\/ | http:\/\/]/', '', $url);
        $match_links_array[] = $domain_without_www = preg_replace('/www\./', '', $without_http_or_https);
        $match_links_array[] = $domain_with_www = 'www.' . $domain_without_www;
        $match_links_array[] = $with_http_1 = "http://" . $domain_without_www;
        $match_links_array[] = $with_http_2 = "http://" . $domain_with_www;
        $match_links_array[] = $with_https_1 = "https://" . $domain_without_www;
        $match_links_array[] = $with_https_2 = "https://" . $domain_with_www;

        foreach ($match_links_array as $match) {
            $slash_links[] = $match . '/';
        }
        if (!empty($slash_links)) {
            $match_links_array = array_merge($match_links_array, $slash_links);
        }

        $affiliate = Affiliate::where('no_show', 0)
                ->whereIn('affiliates.domain', $match_links_array)
                ->first();
        if (count($affiliate) == 1) {
            $affiliateHelper = new AffiliateHelper();

            $jsonArray = array(
                'name' => $affiliate->name,
                'url' => $affiliateHelper->getAffiliateUrl($affiliate, $userId)
            );

            return json_encode($jsonArray);
        }
    }

    public function getUsers($key) {
        $users = User::all();

        if ($key == env('API_KEY')) {
            return $users->toJson();
        } else {
            return "ACCES DENIED";
        }
    }

    public function getAffiliates($key) {
        $affiliates = Affiliate::where('no_show', 0)->get();

        if ($key == env('API_KEY')) {
            return $affiliates->toJson();
        } else {
            return "ACCES DENIED";
        }
    }

    public function getAffiliatesUrl($name) {
      $aff['response'] = "false";
        $affiliates = DB::table('affiliates')->where([['no_show', 0],['name','LIKE','%'.$name.'%']])->get();
        if(!empty($affiliates)){
          $aff['response'] = "true";
        }
        return json_encode($aff);
    }

    public function saldoForExtension() {
        $ret_array = array();
        $ret_array['status'] = 'fail';
        $ret_array['deposit_balance'] = 0;
        $ret_array['current_balance'] = 0;
        DB::transaction(function () use (&$ret_array){
            $current_user = Sentinel::getUser();
            $extension_download_bonus = 5.0;
            if ($current_user) {
                if ($current_user->extension_downloaded == 0) {
                    $cur_balance = (float) $current_user->saldo;
                    $update_balance = $cur_balance + $extension_download_bonus;

                    $result = User::where('id', $current_user->id)->update(array('extension_downloaded' => 1, 'saldo' => $update_balance));

                     $insertTransaction[] = array(
                        'program_id' => 270099,
                        'ip' => '',
                        'external_id' => 'uwvoordeelpas',
                        'user_id' => $current_user->id,
                        'status' => 'accepted',
                        'processed' => date('Y-m-d H:i:s'),
                        'amount' => $extension_download_bonus,
                        'affiliate_network' => 'uwvoordeelpas',
                        'created_at' => date('Y-m-d H:i:s')
                    );

                    Transaction::insert($insertTransaction);

                    if ($result) {
                        $ret_array['status'] = 'success';
                        $ret_array['deposit_balance'] = $extension_download_bonus;
                        $ret_array['current_balance'] = $update_balance;
                    }
                }
            }
        });
        echo json_encode($ret_array);
        exit;
    }

}
