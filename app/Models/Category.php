<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMediaConversions;

class Category extends Model implements HasMediaConversions
{
    use HasMediaTrait;

    protected $table = 'categories';

    public function registerMediaConversions()
    {
        $this
            ->addMediaConversion('175Thumb')
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

        $this
            ->addMediaConversion('mobileThumb')
            ->setManipulations(
                array(
                    'w' => 550, 
                    'h' => 340, 
                    'fit' => 'max', 
                    'format' => 'jpg'
                )
            )
            ->nonQueued()
        ;
        
        $this
            ->addMediaConversion('450pic')
            ->setManipulations(
                array(
                    'w' => 451, 
                    'h' => 340, 
                    'fit' => 'stretch', 
                    'format' => 'jpg'
                )
            )
            ->nonQueued()
        ;

        $this->addMediaConversion('thumb')
            ->setManipulations(
                array(
                    'w' => 368, 
                    'h' => 232, 
                    'format' => 'jpg'
                )
            )
            ->nonQueued()
        ;
    }    

    public static function newItem($name, $subCategory = null)
    {
    	$item = new Category();
    	$item->name = $name;
    	$item->slug = str_slug($name);
    	$item->subcategory_id = $subCategory == null ? 0 : $subCategory;
		$item->no_show = "0";
    	$item->save();

    	return $item;
    }

    public function sub_cat(){
        return $this->hasMany('App\Models\Category', 'subcategory_id');
    }
    
}