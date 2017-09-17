<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FutureDeal extends Model
{
    protected $table = 'future_deals';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(){
        return $this->belongsTo('App\User', 'user_id');
    }

    public function referral(){
        $referral = Referral::where('user_id',$this->user_id)->where('referrer_id',$this->reference_id)->first();
        if($referral)
            return date('d/m/Y h:i A', strtotime($referral->due));
        else
            return "betaald";
        
    }
}
