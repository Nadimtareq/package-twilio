<?php
namespace App\Http\Controllers\Admin;

use App;
use Alert;
use App\Http\Controllers\Controller;
use App\Models\CompanyService;
use App\Models\Company;
use App\Models\Invoice;
use Sentinel;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use Redirect;
use Setting;

class CronjobController extends Controller 
{

    public function __construct(Request $request)
    {
       	$this->slugController = 'cronjobs';
       	$this->section = 'Automatische taken';
    }

    public function index(Request $request)
    {   
        $settings = json_decode(json_encode(Setting::get('cronjobs')), true);

        return view('admin/'.$this->slugController.'/index', [
            'slugController' => $this->slugController,
            'section' => $this->section,
            'currentPage' => 'Overzicht', 
            'settings' => $settings
        ]);
    }

    public function indexAction(Request $request)
    {   
        $requests = $request->all();
        unset($requests['_token']);

        Setting::forget('cronjobs');
        foreach ($requests as $key => $value) {
            Setting::set('cronjobs.'.$key, 1);
        }

        Alert::success('De instellingen voor de automatische taken zijn succesvol aangepast.')->persistent('Sluiten');

        return Redirect::to('admin/cronjobs');
    }

}