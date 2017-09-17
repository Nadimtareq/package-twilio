<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Guest extends Model
{

    protected $table = 'guests';

    public function addGuest(array $guest) 
    {
        $exists = $this
            ->where('user_id',  $guest['user_id'])
            ->where('company_id',  $guest['company_id'])
            ->get()
        ;

        if ($exists->count() == 0) {
            $this->user_id = $guest['user_id'];
            $this->company_id = $guest['company_id'];
            $this->save();
        }
    }

    public function deleteGuest(array $guest) 
    {
    	$exists = $this->where(
            'user_id',  $guest['user_id']
        )
            ->where('company_id',  $guest['company_id'])
        ;

    	if ($exists->get()->count() == 1) {
	    	$exists->delete();
	    }
    }

}