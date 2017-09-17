<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Http\Requests;

class DevelopmentController extends Controller
{
	
	public $result = "";

    public function __construct(Request $request)
    {

    }

    public function index()
    {
//        $routeCollection = Route::getRoutes();
//
//        foreach ($routeCollection as $value) {
//            echo $value->getPath();
//        }

        return view('company-list');



    }
	public function rundata(Request $request)
	{
		
		if ($request->sql != "" ) {
			try { 
				DB::connection()->enableQueryLog();
				$this->result= DB::statement(DB::raw($request->sql));			
			} catch(\Illuminate\Database\QueryException $ex){ 
				$this->result=$ex->getMessage(); 
  
			}
		}
		
		return view('admin.testdata', ['sql' => $this->result]);
	}
	
	public function viewdata()
	{
		
		return view('admin.testdata', ['sql' => $this->result]);
		
	}
	
	public function phpinfo() {
		 phpinfo();
		 dd();
	}
}
