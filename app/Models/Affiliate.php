<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMedia;

class Affiliate extends Model implements HasMedia
{
	use HasMediaTrait;
	
 	protected $guarded = array();
 	
    protected $table   = 'affiliates';

}