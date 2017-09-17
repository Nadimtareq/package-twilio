<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Table extends Model {
    
    /* Database table name */
    protected $table = "dinning_tables";

/*Disable default ts */
    public $timestamps = false;

    /* Primary key of database table */
    protected $primaryKey = "id";
        
    /* Filable columns */
    protected $fillable = array("table_number","comp_id","seating","description","priority","duration","status");

} 
