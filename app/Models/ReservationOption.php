<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReservationOption extends Model {

    protected $table = 'reservations_options';

    /**
     * Get the phone record associated with the user.
     */
    public function company() {
//        return $this->hasOne('App\Models\Company');
        return $this->hasOne('App\Models\Company', 'id', 'company_id')->first();
    }

    public function reservation() {
        return $this->hasMany('App\Models\Reservation', 'option_id', 'id');
    }

    public function reservationCount() {
        return $this->reservation()
            ->selectRaw('option_id, count(*) as total_reservation');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function reviews(){
        return $this->hasMany('App\Models\Review', 'deal_id');
    }

    public function getApprovedReviews(){
        return $this->hasMany('App\Models\Review', 'deal_id')->where('is_approved', 1);
    }
}
