<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Phoenix\EloquentMeta\MetaTrait;

class WifiGuest extends Model
{
    use MetaTrait;

    protected $table = 'guests_wifi';

}