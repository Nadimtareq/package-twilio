<?php
namespace App\Http\Controllers;

use Alert;
use App\Models\Company;
use App\Models\FavoriteCompany;
use App\Models\CompanyReservation;
use Sentinel;
use Redirect;
use Illuminate\Http\Request;

class FavoriteCompaniesController extends Controller 
{

	public function index(Request $request) 
    {
		$companies = Company::select(
			'companies.id',
			'companies.name',
			'companies.slug',
			'companies.description',
			'companies.discount',
			'companies.days',
			'companies.kitchens',
			'companies.city'
		)
			->join('favorite_companies', 'companies.id', '=', 'favorite_companies.company_id')
			->where('companies.no_show', 0)
			->where('favorite_companies.user_id', Sentinel::getUser()->id)
			->with('media')
			->orderBy('companies.created_at', 'desc')
        	->paginate($request->input('limit', 15))
        ;

        foreach ($companies as $company) {
            $companyId[] = $company->id;
        }

        if (isset($companyId)) {
            $reservationDate = date('Y-m-d');
            $reservationTimesArray = CompanyReservation::getReservationTimesArray(
                array(
                    'company_id' => $companyId, 
                    'date' => $reservationDate,
                    'selectPersons' => NULL
                )
            );

            $tomorrowArray = CompanyReservation::getReservationTimesArray(
                array(
                    'company_id' => $companyId, 
                    'date' => date('Y-m-d', strtotime('+1 days')),
                    'selectPersons' => NULL
                )
            );
        }

        $queryString = $request->query();
        unset($queryString['limit']);

		return view('account/favorites', array(
			'companies' => $companies,
			'onFavoritePage' => 1,
            'reservationTimesArray' => (isset($reservationTimesArray) ? $reservationTimesArray : array()),
			'tomorrowArray' => (isset($tomorrowArray) ? $tomorrowArray : array()),
			'limit' => $request->input('limit', 15),
            'queryString' => $queryString,
            'paginationQueryString'  => $request->query()
		));
    }

    public function add($id, $slug) 
    {
    	$favorite = new FavoriteCompany();
    	$favorite->addFavorite(array(
    		'userId' => Sentinel::getUser()->id,
    		'companyId' => $id
    	));

    	Alert::success('Je hebt succesvol een nieuw restaurant aan je favorieten toegevoegd.')
    		->persistent('Sluiten')
    	;   

    	return Redirect::to('account/favorite/companies');
    }

    public function remove($id, $slug) 
    {
    	$favorite = new FavoriteCompany();
    	$favorite->removeFavorite(array(
    		'userId' => Sentinel::getUser()->id,
    		'companyId' => $id
    	));

    	Alert::success('Je hebt succesvol dit restaurant uit je favorieten verwijderd.')
    		->persistent('Sluiten')
    	;   

    	return Redirect::to('account/favorite/companies');
    }
}
