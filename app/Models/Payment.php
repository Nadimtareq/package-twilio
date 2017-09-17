<?php

namespace App\Models;

use Sentinel;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
	
	protected $fillable = [
		'type', 'status', 'user_id', 'amount', 'payment_type', 'mollie_id', 'id', 'updated_at'
	];

	public function user()
	{
        return $this->hasOne('App\User');
    }

}
