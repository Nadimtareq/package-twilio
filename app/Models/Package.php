<?php
namespace App\Models;

use Sentinel;
use Illuminate\Database\Eloquent\Model;

class Package extends Model 
{
    protected $fillable = [
		'type', 'quantity', 'price', 'total',  'company_id', 'is_active'
	];

	public function user()
	{
        return $this->hasOne('App\User');
    }
    
}