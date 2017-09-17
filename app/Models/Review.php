<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{

    protected $table = 'reviews';

    public function countReviews($user)
    {
    	$reviews = static::where('user_id', $user)->where('is_approved', 1);

    	return $reviews->count();
    }

    public function getAverage(Array $sum)
    {
    	return round(array_sum($sum) / count($sum));
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function deal(){
        return $this->belongsTo('App\Models\ReservationOption', 'deal_id');
    }
}