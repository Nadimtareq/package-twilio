<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Response;

use Alert;
use App;
use App\Models\Affiliate;
use App\Models\Category;
use App\Models\Page;
use App\Models\AffiliateCategory;
use App\Models\FavoriteAffiliate;
use App\Models\SearchHistory;
use App\Helpers\AffiliateHelper;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Mail;
use Redirect;
use Sentinel;
use DB;
use URL;

class CashbackController extends Controller 
{

    public function __construct(Request $request)
    {
        // Categories
        $this->categoriesAll = Category::all();
        $this->categories = Category::select(
            'categories.id as catId',
            'categories.slug as catSlug',
            'categories.name as catName',
            'subcategories.id as subcatId',
            'subcategories.extra_categories_id as subExtra',
            'subcategories.slug as subcatSlug',
            'subcategories.name as subcatName',
            DB::raw('(SELECT count(*) FROM affiliates_categories WHERE affiliates_categories.category_id = catId) as countCategoryPrograms'),
            DB::raw('(SELECT count(*) FROM affiliates_categories WHERE affiliates_categories.category_id = subcatId) as countSubCategoryPrograms')
        )
            ->leftJoin('categories as subcategories', 'subcategories.subcategory_id', '=', 'categories.id')
            ->where('categories.subcategory_id', 0)
            ->where('categories.no_show', 0)
            ->orderBy('categories.name', 'asc')
            ->get()
        ;

        $this->categoriesArray = array();

        foreach ($this->categories as $categoriesFetch) {
            $categories[$categoriesFetch->catId] = array(
                'name' => $categoriesFetch->catName,
                'slug' => str_slug($categoriesFetch->catName),
                'name' => $categoriesFetch->catName,
                'countCategoryPrograms' => $categoriesFetch->countCategoryPrograms
            );
        }

        foreach ($this->categories as $categoriesFetch) {
            $subcategories[$categoriesFetch->catName][$categoriesFetch->subcatName] = array(
                'id' => $categoriesFetch->subcatId,
                'slug' => str_slug($categoriesFetch->subcatName),
                'name' => $categoriesFetch->subcatName,
                'countSubCategoryPrograms' => $categoriesFetch->countSubCategoryPrograms
            );

            // Extra subcategories connected to categories
            if (count(json_decode($categoriesFetch->subExtra)) >= 1) {
                foreach (json_decode($categoriesFetch->subExtra) as $subExtra) {
                    if (isset( $categories[$subExtra])) {
                        $catName = $categories[$subExtra]['name'];

                        $subcategories[$catName][$categoriesFetch->subcatName] = array(
                            'id' => $categoriesFetch->subcatId,
                            'slug' => str_slug($categoriesFetch->subcatName),
                            'name' => $categoriesFetch->subcatName,
                            'countSubCategoryPrograms' => $categoriesFetch->countSubCategoryPrograms
                        );
                    }
                }
            }
        }

        foreach ($this->categories as $categoriesFetch) {
            $this->categoriesArray[$categoriesFetch->catName] = array(
                'id' => $categoriesFetch->catId,
                'slug' => str_slug($categoriesFetch->catName),
                'name' => $categoriesFetch->catName,
                'subcategories' => $subcategories[$categoriesFetch->catName],
                'countCategoryPrograms' => $categoriesFetch->countCategoryPrograms
            );
        }

        $this->queryString = $request->query();
        unset($this->queryString['limit']);
    }

    public function index(Request $request)
    {
        $affiliateHelper = new AffiliateHelper();

        // Affiliates
        $affiliatesQuery = Affiliate::select(
            'program_id',
            'image_extension',
            'affiliate_network',
            'tracking_link',
            'slug',
            'name',
            'compensations'
        )
            ->where('no_show', 0)
        ;
        
        if (in_array($request->input('sort'), array('name'))) {
            if ($request->has('sort') && $request->has('order')) {
                $affiliatesQuery = $affiliatesQuery->orderBy($request->input('sort'), $request->input('order'));
                session(['sort' => $request->input('sort'), 'order' => $request->input('order')]);
            } else {
                $affiliatesQuery = $affiliatesQuery->orderBy('clicks', 'desc');
            }
        }

        $affiliatesQuery = $affiliatesQuery
            ->orderBy('clicks', 'desc')
            ->groupBy('program_id')
            ->get()
        ;

        foreach ($affiliatesQuery as $affiliatesFetch) {
            $affiliatesArray[] = array(
                'image_extension' => $affiliatesFetch->image_extension,
                'program_id' => $affiliatesFetch->program_id,
                'affiliate_network' => $affiliatesFetch->affiliate_network,
                'slug' => $affiliatesFetch->slug,
                'tracking_link' => $affiliatesFetch->tracking_link,
                'name' => $affiliatesFetch->name,
                'commissions' => $affiliateHelper->commissionMaxValue($affiliatesFetch->compensations),
                'commissionsNoUnit' => str_replace(',', '.', $affiliateHelper->commissionMaxValue($affiliatesFetch->compensations, 1))
            );
        }

        if (in_array($request->input('sort'), array('commissions'))) {
            switch ($request->input('order')) {
                case 'asc':
                     uasort($affiliatesArray, array('App\Helpers\AffiliateHelper', 'sortArrayCommissionsDesc'));
                    break;

                case 'desc':
                    uasort($affiliatesArray, array('App\Helpers\AffiliateHelper', 'sortArrayCommissionsAsc'));
                    break;
            }
        }

        $affiliates = new LengthAwarePaginator(
            array_slice(
                $affiliatesArray, 
                $request->input('limit', 16) * ($request->input('page', 1) - 1), 
                $request->input('limit', 16)
            ),
            count($affiliatesArray), 
            $request->input('limit', 16)
        );
        
        $affiliates->setPath(URL::to('tegoed-sparen'));

        return view('pages/cashback/index', [
            'affiliates' => $affiliates,
            'categories' => $this->categoriesArray,
            'queryString' => $this->queryString,
            'limit' => $request->input('limit', 16),
            'paginationQueryString' => $request->query()
        ]);
    }
    
    public function category(Request $request, $id, $slug)
    {
        $affiliateHelper = new AffiliateHelper;

        $category = Category::where('id', $id)
            ->where('slug', $slug)
            ->with('media')
            ->where('no_show', 0)
            ->first()
        ;
        
        $mediaItems = $category->getMedia();

        if ($category) {
            // Subcategories
            $subcategory = Category::where('subcategory_id', $id)->get();

            $subcategories = array();
            $subcategories = $subcategory->map(function ($cat) {
                return $cat->id;
            })
                ->toArray();
            ;

            array_push($subcategories, (int) $id);

            // Affiliate
            $affiliatesQuery = Affiliate::leftJoin('affiliates_categories', 'affiliates_categories.affiliate_id', '=', 'affiliates.id')
                ->where('affiliates.no_show', 0)
                ->whereIn('affiliates_categories.category_id', $subcategories)
                ->orderBy('clicks', 'desc')
                ->groupBy('program_id')
                ->get()
            ;

             foreach ($affiliatesQuery as $affiliatesFetch) {
                $affiliatesArray[] = array(
                    'image_extension' => $affiliatesFetch->image_extension,
                    'program_id' => $affiliatesFetch->program_id,
                    'affiliate_network' => $affiliatesFetch->affiliate_network,
                    'slug' => $affiliatesFetch->slug,
                    'tracking_link' => $affiliatesFetch->tracking_link,
                    'name' => $affiliatesFetch->name,
                    'commissions' => $affiliateHelper->commissionMaxValue($affiliatesFetch->compensations),
                    'commissionsNoUnit' => str_replace(',', '.', $affiliateHelper->commissionMaxValue($affiliatesFetch->compensations, 1))
                );
            }

            if (in_array($request->input('sort'), array('commissions'))) {
                switch ($request->input('order')) {
                    case 'asc':
                         uasort($affiliatesArray, array('App\Helpers\AffiliateHelper', 'sortArrayCommissionsDesc'));
                        break;

                    case 'desc':
                        uasort($affiliatesArray, array('App\Helpers\AffiliateHelper', 'sortArrayCommissionsAsc'));
                        break;
                }
            }

            $affiliates = new LengthAwarePaginator(
                array_slice(
                    $affiliatesArray, 
                    $request->input('limit', 16) * ($request->input('page', 1) - 1), 
                    $request->input('limit', 16)
                ),
                count($affiliatesArray), 
                $request->input('limit', 16)
            );
            
            $affiliates->setPath(URL::to('tegoed-sparen/category/'.$id.'/'.$slug));

            // Ads
            $page = Page::find($category->ad_page_id);

            return view('pages/cashback/index', [
                'mediaItems' => $mediaItems,
                'page' => $page,
                'affiliates' => $affiliates,
                'categories' => $this->categoriesArray,
                'currentCategory' => ($category->subcategory_id == 0 ? $category->id : $category->subcategory_id),
                'queryString' => $this->queryString,
                'limit' => $request->input('limit', 16),
                'paginationQueryString' => $request->query()
            ]);
        } else {
            App::abort(404);
        }
    }
    
    public function company(Request $request, $slug)
    {
        $company = Affiliate::select(
            'affiliates.*',
            'affiliates_categories.category_id'
        )
            ->leftJoin('affiliates_categories', 'affiliates_categories.affiliate_id', '=', 'affiliates.id')
            ->where('affiliates.no_show', 0)
            ->where('slug', $slug)
            ->first()
        ;

        if ($company) {
            $company->clicks = ($company->clicks + 1);
            $company->save();
            
            $affiliateHelper = new AffiliateHelper;
            $url = $affiliateHelper->getAffiliateUrl(
                $company,
                (Sentinel::check() ? Sentinel::getUser()->id : '')
            );

            $affiliatesQuery = Affiliate::select(
                'affiliates.*',
                'affiliates_categories.category_id'
            );
            
            if (in_array($request->input('sort'), array('name', 'percentage'))) {
                if ($request->has('sort') && $request->has('order')) {
                    $affiliatesQuery = $affiliatesQuery->orderBy($request->input('sort'), $request->input('order'));
                    session(['sort' => $request->input('sort'), 'order' => $request->input('order')]);
                } else {
                    $affiliatesQuery = $affiliatesQuery->orderBy('clicks', 'desc');
                }
            }

            $affiliatesCatIds = explode(' ', $company->category_id);
            
            if (count($affiliatesCatIds) >= 1) {
                $affiliatesQuery = $affiliatesQuery->whereIn('affiliates_categories.category_id', $affiliatesCatIds);
            }

            $affiliatesQuery = $affiliatesQuery->where('affiliates.no_show', 0)
                ->leftJoin('affiliates_categories', 'affiliates_categories.affiliate_id', '=', 'affiliates.id')
                ->orderBy('clicks', 'desc')
                ->groupBy('program_id')
                ->get()
            ;

            foreach ($affiliatesQuery as $affiliatesFetch) {
                $affiliatesArray[] = array(
                    'image_extension' => $affiliatesFetch->image_extension,
                    'program_id' => $affiliatesFetch->program_id,
                    'affiliate_network' => $affiliatesFetch->affiliate_network,
                    'slug' => $affiliatesFetch->slug,
                    'tracking_link' => $affiliatesFetch->tracking_link,
                    'name' => $affiliatesFetch->name,
                    'commissions' => $affiliateHelper->commissionMaxValue($affiliatesFetch->compensations),
                    'commissionsNoUnit' => str_replace(',', '.', $affiliateHelper->commissionMaxValue($affiliatesFetch->compensations, 1))
                );
            }

            if (in_array($request->input('sort'), array('commissions'))) {
                switch ($request->input('order')) {
                    case 'asc':
                         uasort($affiliatesArray, array('App\Helpers\AffiliateHelper', 'sortArrayCommissionsDesc'));
                        break;

                    case 'desc':
                        uasort($affiliatesArray, array('App\Helpers\AffiliateHelper', 'sortArrayCommissionsAsc'));
                        break;
                }
            }

            $affiliates = new LengthAwarePaginator(
                array_slice(
                    $affiliatesArray, 
                    $request->input('limit', 16) * ($request->input('page', 1) - 1), 
                    $request->input('limit', 16)
                ),
                count($affiliatesArray), 
                $request->input('limit', 16)
            );
            
            $affiliates->setPath(URL::to('tegoed-sparen/company/'.$slug));

            if (Sentinel::check()) {
                $favoriteCompany = FavoriteAffiliate::select(
                    'id'
                )
                    ->where('favorite_affiliates.user_id', Sentinel::getUser()->id)
                    ->where('favorite_affiliates.affiliate_id', $company->id)
                    ->count()
                ;
            }

            return view('pages/cashback/company', [
                'url' => (isset($url) ? $url : ''),
                'favoriteCompany' => isset($favoriteCompany) ? $favoriteCompany : 0,
                'data' => $company,
                'affiliates' => $affiliates,
                'categories' => $this->categoriesArray,
                'queryString' => $this->queryString,
                'limit' => $request->input('limit', 16),
                'paginationQueryString' => $request->query()
            ]);
        } else {
            App::abort(404);
        }
    }

    public function search(Request $request)
    {
        $affiliateHelper = new AffiliateHelper;

        $affiliatesQuery = AffiliateCategory::select(
            'affiliates.*'
        )
            ->leftJoin('categories', 'affiliates_categories.category_id', '=', 'categories.id')
            ->leftJoin('affiliates','affiliates_categories.affiliate_id', '=', 'affiliates.id')
            ->where('affiliates.no_show', 0)
            ->orderBy('affiliates.clicks', 'desc')
        ;

        if ($request->has('q')) {
            $affiliatesQuery = $affiliatesQuery
                ->where('affiliates.name', 'LIKE', '%'.$request->input('q').'%')
                ->orWhere('categories.name', 'LIKE', '%'.$request->input('q').'%')
            ;
            
            // Add to Search History
            $searchHistory = new SearchHistory();
            $searchHistory->addTerm($request->input('q'), '/tegoed-sparen');
        }

        if ($request->has('category')) {
            $affiliatesQuery = $affiliatesQuery->where('categories.slug', '=', $request->input('category'));
            
            if ($request->has('subcategory')) {
                $affiliatesQuery = $affiliatesQuery->orWhere('categories.slug', '=', $request->input('subcategory'));
            }
        }

        if ($request->has('sort') && $request->has('order')) {
            $affiliatesQuery = $affiliatesQuery->orderBy($request->input('sort'), $request->input('order'));
            session(['sort' => $request->input('sort'), 'order' => $request->input('order')]);
        } else {
            $affiliatesQuery = $affiliatesQuery->orderBy('clicks', 'desc');
        }

        $affiliatesQuery = $affiliatesQuery
            ->orderBy('clicks', 'desc')
            ->groupBy('program_id')
            ->get()
        ;

        foreach ($affiliatesQuery as $affiliatesFetch) {
            $affiliatesArray[] = array(
                'image_extension' => $affiliatesFetch->image_extension,
                'program_id' => $affiliatesFetch->program_id,
                'affiliate_network' => $affiliatesFetch->affiliate_network,
                'slug' => $affiliatesFetch->slug,
                'tracking_link' => $affiliatesFetch->tracking_link,
                'name' => $affiliatesFetch->name,
                'commissions' => $affiliateHelper->commissionMaxValue($affiliatesFetch->compensations),
                'commissionsNoUnit' => str_replace(',', '.', $affiliateHelper->commissionMaxValue($affiliatesFetch->compensations, 1))
            );
        }

        if (!isset($affiliatesArray)) {
            alert()->error('', 'Er zijn geen zoekresultaten gevonden met uw selectiecriteria.')->persistent('Sluiten');

            return Redirect::to('tegoed-sparen');
        }   
        
        if (in_array($request->input('sort'), array('commissions'))) {
            switch ($request->input('order')) {
                case 'asc':
                     uasort($affiliatesArray, array('App\Helpers\AffiliateHelper', 'sortArrayCommissionsDesc'));
                    break;

                case 'desc':
                    uasort($affiliatesArray, array('App\Helpers\AffiliateHelper', 'sortArrayCommissionsAsc'));
                    break;
            }
        }

        $affiliates = new LengthAwarePaginator(
            array_slice(
                $affiliatesArray, 
                $request->input('limit', 16) * ($request->input('page', 1) - 1), 
                $request->input('limit', 16)
            ),
            count($affiliatesArray), 
            $request->input('limit', 16)
        );
        
        $affiliates->setPath(URL::to('tegoed-sparen/search'));

        return view('pages/cashback/index', [
            'affiliates' => $affiliates,
            'categories' => $this->categoriesArray,
            'queryString' => $this->queryString,
            'limit' => $request->input('limit', 16),
            'paginationQueryString' => $request->query()
        ]);
    }

    public function favorite(Request $request, $id, $slug)
    {
        $favorite = new FavoriteAffiliate();
        $favorite->addFavorite(array(
            'userId' => Sentinel::getUser()->id,
            'companyId' => $id
        ));

        Alert::success('Je hebt succesvol een nieuwe webwinkel aan je favorieten toegevoegd.')->persistent('Sluiten');   

        return Redirect::to('tegoed-sparen/company/'.$slug);    
    }

    public function deleteFavorite(Request $request, $id, $slug)
    {
        $favorite = new FavoriteAffiliate();
        $favorite->removeFavorite(array(
            'userId' => Sentinel::getUser()->id,
            'companyId' => $id
        ));

        Alert::success('Je hebt succesvol deze  webwinkel uit je favorieten verwijderd.')->persistent('Sluiten');   

        return Redirect::to('tegoed-sparen/company/'.$slug);    
    }

    public function getSubCat(Request $request,$slug){
        $id=Category::select('id')->where('slug',$slug)->first();

        $subcate=Category::select(
            'categories.id as catId',
            'categories.slug as slug',
            'categories.name as name')->where('subcategory_id',$id->id)->get();
            //->leftJoin('categories as subcategories', 'subcategories.subcategory_id', '=', 'categories.id')->get();
        return Response::json(array(
            'subcat' => $subcate
        ));
    }

}