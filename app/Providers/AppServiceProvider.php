<?php

namespace App\Providers;

use App;
use App\User;
use App\Models\Content;
use App\Models\Preference;
use App\Models\Page;
use App\Models\Affiliate;
use App\Models\FavoriteAffiliate;
use App\Models\CompanyReservation;
use Carbon\Carbon;
use Illuminate\Contracts\Logging\Log;
use Session;
use Setting;
use Illuminate\Support\ServiceProvider;
use Blade;
use Request;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(Request $request)
    {
        $preference = new Preference();

        $getTimes = CompanyReservation::getAllTimes();
        sort($getTimes);

        $websiteSettings =  json_decode(json_encode(Setting::get('website')), true);
        $sources = (isset($websiteSettings['source']) ? explode(PHP_EOL, $websiteSettings['source']) : array());

        view()->share(array(
            'allTimesArray' => CompanyReservation::getAllTimes(),
            'contentBlock' => Content::getBlocks(),
            'getTimes' => $getTimes,
            'regio' => $preference->getRegio()['regio'],
            'preference' => Preference::getPreferences(),
            'pageLinks' => Page::getPages(),
            'discountSettings' => json_decode(json_encode(Setting::get('discount')), true),
            'websiteSetting' => json_decode(json_encode(Setting::get('website')), true),
            'sources' =>$sources
        ));
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
//        $this->app->alias('bugsnag.logger', Log::class);
//        $this->app->alias('bugsnag.logger', \Psr\Log\LoggerInterface::class);
    }
}
