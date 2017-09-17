<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsletterJob extends Model
{

    protected $table = 'newsletters_jobs';
    protected $fillable = ['city_id', 'date', 'time', 'status', 'updated_at', 'created_at'];

}
