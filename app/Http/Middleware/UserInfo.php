<?php

namespace App\Http\Middleware;

use Closure;
use App\User;
use App\Models\Affiliate;
use App\Models\FavoriteCompany;
use App\Models\Company;
use Sentinel;
use Redirect;
use Route;

class UserInfo
{
    public function handle($request, Closure $next)
    {   
        $topAffiliates = Affiliate::select(
            'affiliates.id',
            'affiliates.name',
            'affiliates.program_id',
            'affiliates.affiliate_network',
            'affiliates.image_extension',
            'affiliates.slug',
            'affiliates.compensations'
        )
            ->leftJoin('favorite_affiliates', function ($join) {
                $join
                    ->on('favorite_affiliates.affiliate_id', '=', 'affiliates.id')
                    ->where('favorite_affiliates.user_id', '=', Sentinel::check() ? Sentinel::getUser()->id : 0)
                ;
            })
            ->orderBy('favorite_affiliates.created_at', 'desc')
            ->orderBy('affiliates.clicks', 'desc')
            ->where('affiliates.no_show', '=', 0)
            ->groupBy('affiliates.name')
            ->take(50)
            ->get()
        ;

        $this->userCompanies = array();
        $this->userCompaniesWaiter = array();
        $this->userCompaniesCallcenter = array();

        if (Sentinel::check() && Sentinel::check()) {
            if (Sentinel::inRole('bedrijf')) {
                $this->userCompanies = User::find(Sentinel::getUser()->id)->companies;
            }

            if (Sentinel::check() && Sentinel::inRole('bediening')) {
                $this->userCompaniesWaiter = Company::select(
                    'id',
                    'slug',
                    'name'
                )
                    ->where('waiter_user_id', '=', Sentinel::getUser()->id)
                    ->get()
                ;
            }

            if (Sentinel::check() && Sentinel::inRole('callcenter')) {
                $this->userCompaniesCallcenter = Company::select(
                    'id',
                    'slug',
                    'name'
                )
                    ->where('caller_id', '=', Sentinel::getUser()->id)
                    ->get()
                ;
            }

            // If banned
            $userBanned = User::banned(Sentinel::getUser()->id);

            if (is_array($userBanned) && count($userBanned) >= 1) {
                $reasonsArray = array();

                foreach ($userBanned as $key => $userBan) {
                    $reasonsArray[] = $userBan['reason'];
                }

                $reasons = implode($reasonsArray, '');

                Sentinel::logout();
                
                alert()->error('Helaas', 'Wij moeten u helaas mededelen dat u verbannen bent om de volgende reden(s): '.$reasons)->persistent('Sluiten');
               
                if (Route::getCurrentRoute()->uri() != '/')
                    return Redirect::to('/');
            }
        }

        $favorite = new FavoriteCompany();

        view()->share(array(
            'userFavorite' => Sentinel::check() ? $favorite->getFavorites(Sentinel::getUser()->id) : '',
            'userAuth' => Sentinel::check(),
            'userInfo' => Sentinel::getUser(),
            'userAdmin' => Sentinel::check() ? Sentinel::inRole('admin') : '',
            'userCompanies' => $this->userCompanies,
            'userCompaniesWaiter' => $this->userCompaniesWaiter,
            'userCompaniesCallcenter' => $this->userCompaniesCallcenter,
            'userWaiter' => Sentinel::check() ? Sentinel::inRole('bediening') : '',
            'userCompany' => Sentinel::check() ? Sentinel::inRole('bedrijf') : '',
            'userCallcenter' => Sentinel::check() ? Sentinel::inRole('callcenter') : '',
            'topAffiliates' => $topAffiliates
        ));

        return $next($request);
    }
}
