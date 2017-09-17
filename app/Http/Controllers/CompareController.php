<?php
namespace App\Http\Controllers;

use App;
use App\Models\Affiliate;
use App\Models\Category;
use App\Models\Page;
use App\Models\AffiliateCategory;
use App\Helpers\AffiliateHelper;
use Illuminate\Http\Request;
use Mail;
use Redirect;
use Sentinel;
use DB;

class CompareController extends Controller 
{

    public function __construct(Request $request)
    {

    }

    public function index(Request $request)
    {
        return view('pages/compare/index', []);
    }

    public function car(Request $request)
    {
        return view('pages/compare/car', []);
    }

    public function energy(Request $request)
    {
        return view('pages/compare/energy', []);
    }

    public function contents(Request $request)
    {
        return view('pages/compare/contents', []);
    }

    public function building(Request $request)
    {
        return view('pages/compare/building', []);
    }

    public function law(Request $request)
    {
        return view('pages/compare/law', []);
    }

    public function travel(Request $request)
    {
        return view('pages/compare/travel', []);
    }

    public function care(Request $request)
    {
        return view('pages/compare/care', []);
    }

}