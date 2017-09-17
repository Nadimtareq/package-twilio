<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FavoriteAffiliate extends Model
{

    protected $table = 'favorite_affiliates';
    
    public function addFavorite($options) 
    {
    	extract($options);

    	$existsQuery = $this->where(
    		'user_id', '=', $options['userId']
    	)
    		->where('affiliate_id', '=', $options['companyId'])
    		->count();

    	if ($existsQuery == 0) {
    		$this->user_id = $options['userId'];
    		$this->affiliate_id = $options['companyId'];
    		$this->save();
    	}
    }

    public function removeFavorite($options) 
    {
    	extract($options);

    	$existsQuery = $this->where(
    		'user_id', '=', $options['userId']
    	)
    		->where('affiliate_id', '=', $options['companyId'])
    		->first();

    	if (count($existsQuery) == 1) {
    		$existsQuery->delete();
    	}
    }

    public function getFavorites($userId) 
    {
    	$favoritesQuery = $this->select(
    		'affiliate_id'
    	)
	    	->where('user_id', '=', $userId)
    		->get();

    	if (count($favoritesQuery) >= 1) {
    		foreach ($favoritesQuery as $favoritesFetch) {
    			$favoriteCompanies[$favoritesFetch->affiliate_id] = 1;
    		}
    	}

    	if (isset($favoriteCompanies)) {
    		return $favoriteCompanies;
    	}
    }
    
}