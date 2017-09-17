<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use Redirect;
use Alert;
use App\Models\Table;
use App\Models\Company;
use Sentinel;
use DB;

class TablesController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
	public function index(Request $request) {
		$roleId = Sentinel::getUser()->roles[0]->id;
		if($roleId == 1){
		$dbobj = new Table;
       $companies = Company::lists('name', 'id');		 
		$data['companies'] = $companies;
		$dbobj->select('dinning_tables.*', 'companies.name');
		
		if (Sentinel::getUser()->default_role_id != 1) {
			$company = Company::where('user_id', Sentinel::getUser()->id)->first();
			
            if (count($company) > 0) {
                $dbobj = $dbobj->where('comp_id', $company['id']);
            } else {
                //$dbobj = $dbobj->where('comp_id', '100000000');
            }
        }
		foreach ($request->except(['orderby', 'page']) as $key => $val) {
            if ($val != '') {
				if($key=='comp_id'){
					$companyname = Company::where('id', trim($val))->first();
					if (count($companyname) > 0) {
						$data['companyname'] = $companyname['name'];
					}
					$dbobj = $dbobj->where('comp_id', trim($val));
				}else{
					$dbobj = $dbobj->where($key, 'LIKE', '%' . trim($val) . '%');
				}
            }
        }
        foreach ($request->only(['orderby']) as $val) {
            if ($val != '') {
                $col = explode(' ', $val);
                $dbobj = $dbobj->orderBy($col[0], $col[1]);
            }
        }
		$dbobj->leftJoin('companies', 'companies.id', '=', ' dinning_tables.comp_id');

        if (!isset($_GET['orderby'])) {
            $dbobj = $dbobj->with('company')->orderBy('priority', 'asc');
        }
        $dbobj = $dbobj->paginate(20);
	    	$data["model"] = $dbobj;
		}
		else{
			$company = Company::where('user_id', Sentinel::getUser()->id)->first();
			$data = DB::table('dinning_tables')->where('comp_id',$company->id )->get();
			$data["model"] = $data;	 
		}
        return view("admin/tables.index", $data);
    }

    public function setpriority(Request $request) {
        $input = $request->all();
        $priority = json_decode($input['priority']);
        foreach ($priority[0] as $key => $val) {
            $tbl = Table::findOrFail($val->id);
            $tbl->priority = $key;
            $tbl->save();
        }
        return "1";
    }

    public function create() {
		$roleId = Sentinel::getUser()->roles[0]->id;
		if (Sentinel::getUser()->default_role_id == 1) {
            $data["company"] = Company::lists('name', 'id');
        } else {
            $data["company"] = Company::where('user_id', Sentinel::getUser()->id)->lists('name', 'id');
        }
		$dbobj = new Table;
		$companies = Company::lists('name', 'id');
		$data['companies'] = $companies;
		$dbobj->select('dinning_tables.*', 'companies.name');
		if (Sentinel::getUser()->default_role_id != 1) {
            $company = Company::where('user_id', Sentinel::getUser()->id)->first();
            if (count($company) > 0) {
                $dbobj = $dbobj->where('comp_id', $company['id']);
            } else {
                //$dbobj = $dbobj->where('comp_id', '100000000');
            }
        }
		return view("admin/tables.create", $data);
    }

    public function store(Request $request) {
        $this->validate($request, ["table_number" => "required", "seating" => "required", "description" => "required", "priority" => "required", "duration" => "required",]);
        $input = $request->all();
		if(Sentinel::getUser()->roles[0]->id != 1){
			$company = Company::where('user_id', Sentinel::getUser()->id)->first();
			$input['comp_id'] = $company->id; 
			$input['created_at'] = date('Y-m-d'); 
		} 
        Table::create($input);
        Session::flash("flash_message", "Tables successfully added!");
        Alert::success("Table Added successfully");
        return Redirect::to('admin/tables');
    }

    public function show($id) {
        $data["model"] = Table::findOrFail($id);

        return view("admin/tables.show", $data);
    }

    public function edit($id) {
        $data["model"] = Table::findOrFail($id);
		
		$data["status"] = $data["model"]->status;
		
        if (Sentinel::getUser()->default_role_id == 1) {
            $data["company"] = Company::lists('name', 'id');
        } else {
            $data["company"] = Company::where('user_id', Sentinel::getUser()->id)->lists('name', 'id');
        }
        return view("admin/tables.edit", $data);
    }

    public function update($id, Request $request) {
        $data = Table::findOrFail($id);
        $this->validate($request, ["table_number" => "required", "seating" => "required", "description" => "required", "priority" => "required", "duration" => "required",]);
        $input = $request->all();
        $data->fill($input)->save();
        Session::flash("flash_message", "Deze tafel is succesvol aangepast!");
        Alert::success("Deze tafel is succesvol aangepast");
        return redirect()->back();
    }

    public function destroy($id) {
        $data = Table::findOrFail($id);
        $data->delete();
        Session::flash("flash_message", "Tables successfully deleted!");
        return redirect()->back();
    }

}

/*End of file */