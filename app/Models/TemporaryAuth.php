<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class TemporaryAuth extends Model
{

    protected $table = 'temporary_auth';
    protected $fillable = ['code','user_id','redirect_to'];

    public function createCode($userId, $redirectTo = null)
    {
    	$randomCode = str_random(64);

    	// Add new temporary login session
    	$redirectTo == null ? 'account' : $redirectTo;
      $this->create(['code'=>$randomCode,'user_id'=>$userId,'redirect_to'=>$redirectTo]);
    	return $randomCode;
    }

}
