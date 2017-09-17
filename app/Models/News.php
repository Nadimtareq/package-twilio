<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMediaConversions;
use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;

class News extends Model implements SluggableInterface, HasMediaConversions
{
    use SluggableTrait;
    use HasMediaTrait;

    protected $sluggable = [
        'build_from' =>  ['company_id', 'title'],
        'save_to'    => 'slug',
    ];

 	protected $guarded = array();
    protected $table = 'news';

    public function registerMediaConversions()
    {
        $this->addMediaConversion(
            '175Thumb'
        )
            ->setManipulations(
                array(
                    'w' => 175, 
                    'h' => 132, 
                    'fit' => 'stretch', 
                    'format' => 'jpg'
                )
            )
            ->nonQueued()
        ;

        $this->addMediaConversion(
            '175Thumbstretch'
        )
            ->setManipulations(
                array(
                    'w' => 175, 
                    'h' => 132, 
                    'fit' => 'stretch', 
                    'format' => 'jpg'
                )
            )
            ->nonQueued()
        ;
    }    
}