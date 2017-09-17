<?php
namespace App\Http\Controllers;

use App;
use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\Company;
use Sentinel;
use Illuminate\Http\Request;
use Carbon\Carbon;

class NewsController extends Controller 
{
    public function index(Request $request)
    {
        $data = News::with('media')
            ->where('is_published', 1)
            ->paginate(15)
        ;

        $companies = Company::with('media')
            ->get()
        ;

        $companyImage = array();

        foreach ($companies as $company) {
            $media = $company->getMedia('default');

            if ($media != '[]') {
                $companyImage[$company->id] = $media->last()->getUrl('175Thumb');
            }
        }

        $queryString = $request->query();
        unset($queryString['limit']);

        return view('pages/news/posts', [
            'news' => $data,
            'companyImage' => $companyImage,
            'queryString' => $queryString,
            'paginationQueryString' => $request->query()
        ]); 
    }

    public function view($slug)
    {
        $news = News::where('slug', $slug);

        if(Sentinel::check() == FALSE || Sentinel::check() &&  Sentinel::inRole('admin') == FALSE && Sentinel::inRole('bedrijf') == FALSE) {
            $news = $news->where('is_published', 1);
        }

        $news = $news->first();

        if ($news) {
            $company = Company::with('media')
                ->where('id', $news->company_id)
                ->first()
            ;
            
            return view('pages/news/post', [
                'news' => $news,
                'media' => $news->getMedia(),
                'company' => $company
            ]); 
        } else {
            App::abort(404);
        }
    }
}