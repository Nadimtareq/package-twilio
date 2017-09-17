<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GuestThirdParty extends Model
{

    protected $table = 'guests_third_party';

    public function addGuest(array $guest) 
    {
        /*
    	$exists = $this
            ->where('user_id',  $guest['user_id'])
            ->where('company_id',  $guest['company_id'])
            ->get()
        ;

    	if ($exists->count() == 0) {
           */
	    	$this->name = $guest['name'];
	    	$this->network = $guest['network'];
	    	$this->save();
            /*
	    }
        */
    }

}