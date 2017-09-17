<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Referral extends Model
{
    //
    protected $fillable = ['user_id','referrer_id','reference'];

    public static function exists($user, $referrer)
    {
        $referral = Self::where('user_id',$user)->where('referrer_id',$referrer)->count();
        if($referral > 0){
            return true;
        }else{
            return false;
        }

    }

    public static function pendingReferrals($user){
        $referral = Self::where('user_id',$user)->where('status','!=',"Complete");
        return $referral;
    }

    public static function getDue($user, $referrer)
    {
        if(Self::exists($user, $referrer))
        {
              $referral = Self::where('user_id',$user)->where('referrer_id',$referrer)->first();
              return $referral->due;
        }else{
             return "betaald";
        }
    }
}
