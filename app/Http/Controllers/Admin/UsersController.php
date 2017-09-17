<?php
namespace App\Http\Controllers\Admin;

use Alert;
use App\Http\Controllers\Controller;
use App\Helpers\StrHelper;
use App\Models\Preference;
use App\Models\Transaction;
use App\Models\Payment;
use App\Models\Reservation;
use App\Models\Company;
use App\Role;
use App\User;
use Sentinel;
use DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use Redirect;

class UsersController extends Controller
{

    public function __construct()
    {
        $this->slugController = 'users';
        $this->section = 'Gebruikers';
        $this->roles = Sentinel::getRoleRepository()->all();
        $this->companies = Company::where('no_show', '=', 0)->get();
    }

    public function stristrArray($haystack, $needle)
    {
        if (!is_array($haystack)) {
            return false;
        }

        foreach ($haystack as $element) {
            if (stristr($element, $needle)) {
                return $element;
            }
        }
    }

    public function index(Request $request)
    {	
		
        $preferences = new Preference();
        $regio = $preferences->getRegio();
		
		
		
		
			$data = Sentinel::getUserRepository()->select(
				'users.id',
				'users.name',
				'users.email',
				'users.phone',
				'users.created_at',
				'users.gender',
				'users.updated_at',
				'users.saldo',
				'users.newsletter',
				'users.city',
				'preferences.name as cityName'
			)
				->leftJoin('preferences', 'users.city', '=', 'preferences.id');
		
			if ($request->has('q')) {
				$data = $data->where(
					'users.name', 'LIKE', '%'.$request->input('q').'%'
				)
				   ->orWhere('users.email', 'LIKE', '%'.$request->input('q').'%')
				   ->orWhere('users.saldo', 'LIKE', '%'.$request->input('q').'%')
				   ->orWhere('preferences.name', 'LIKE', '%'.$request->input('q').'%')
				;

				$regioName = $request->input('q');

				if (isset($regio['regioNumber'][$regioName])) {
					$data = $data->orWhere('users.city', 'REGEXP', '"([^"]*)'.$regio['regioNumber'][$regioName].'([^"]*)"');
				}
			}

			if ($request->has('source')) {
				switch ($request->input('source')) {
					case 'wifi':
						$data = $data
							->rightJoin('guests_wifi', 'guests_wifi.email', '=', 'users.email')
						;
						break;

					default:
						 $data = $data
							->leftJoin('reservations', 'reservations.user_id', '=', 'users.id')
							->join('guests_third_party', 'guests_third_party.email','=','users.email')
							->where('guests_third_party.network', '=', $request->input('source'))
							->groupBy('users.id')
						;
						break;
				}
			}
	//        echo $request->input('source');
			if ($request->has('has_saving')) {
				switch ($request->input('has_saving')) {
					case '0':
						$data = $data->where('users.extension_downloaded', '=', 0);
						break;
					case '1':
						$data = $data->where('users.extension_downloaded', '=', 1);
						break;
					case '2':
						$data = $data->where('users.extension_downloaded', '=', 2);
						break;
						
				}
			}
			if ($request->has('sort') && $request->has('order')) {
				$data = $data->orderBy('users.'.$request->input('sort'), $request->input('order'));
				session(['sort' => $request->input('sort'), 'order' => $request->input('order')]);
			} else {
				$data = $data->orderBy('users.id', 'desc');
			}

			if ($request->has('city')) {
				 $regioName = $request->input('city');

				if (isset($regio['regioNumber'][$regioName])) {
					$data = $data->whereNotNull(
						'users.city'
					)
						->where('users.city', 'REGEXP', '"([^"]*)'.$regio['regioNumber'][$regioName].'([^"]*)"')
					;
				}
			}

			if($request->has('role')){
				$role = $request->input('role');
				$data = User::whereHas('roles', function ($q) use ($role){
					$q->where('name', "LIKE", $role);
				});
			}
		
        $dataCount = $data->count();

        $data = $data->paginate($request->input('limit', 15));
        $data->setPath($this->slugController);

        # Redirect to last page when page don't exist
        if ($request->input('page') > $data->lastPage()) {
            $lastPageQueryString = json_decode(json_encode($request->query()), true);
            $lastPageQueryString['page'] = $data->lastPage();

            return Redirect::to($request->url().'?'.http_build_query($lastPageQueryString));
        }

        $queryString = $request->query();
        unset($queryString['source']);
        unset($queryString['has_saving']);
        unset($queryString['limit']);

        return view('admin/'.$this->slugController.'/index', [
            'data' => $data,
            'regio' => $regio['regio'],
            'countItems' => $dataCount,
            'slugController' => $this->slugController,
            'queryString' => $queryString,
            'paginationQueryString' => $request->query(),
            'limit' => $request->input('limit', 15),
            'section' => $this->section,
            'currentPage' => 'Overzicht',
            'companies' => $this->companies,
        ]);
		
    }

    public function create()
    {
        return view('admin/'.$this->slugController.'/create', [
            'section' => $this->section,
            'slugController' => $this->slugController,
            'section' => $this->section,
            'currentPage' => 'Nieuwe gebruiker',
            'roles' => $this->roles
        ]);
    }

    public function update($id)
    {
        $data = Sentinel::findById($id);

        //removed the section duplicate key

        return view('admin/'.$this->slugController.'/update', [
            'data' => $data,
            'section' => $this->section,
            'slugController' => $this->slugController,
            'currentPage' => 'Wijzig gebruiker',
            'roles' => $this->roles
        ]);
    }

    public function createAction(Request $request)
    {
        $this->validate($request, [
	         'name' => 'required',
        	 'email' => 'required|email|unique:users'
        ]);

        $user = Sentinel::registerAndActivate(array(
            'email' =>  $request->input('email'),
            'password' => trim($request->input('password')) == '' ? '12345678' : $request->input('password')
        ));

        $user->name = $request->input('name');
        $user->phone = $request->input('phone');
        $user->gender = $request->input('gender');
        $user->saldo = $request->input('saldo');
        $user->birthday_at = $request->input('birthday_at');
        $user->default_role_id = $request->input('role');
        $user->city  = json_encode($request->input('city'));
        $user->facilities = json_encode($request->input('facilities'));
        $user->kitchens = json_encode($request->input('kitchens'));
        $user->allergies = json_encode($request->input('allergies'));
        $user->sustainability = json_encode($request->input('sustainability'));
        $user->kids = json_encode($request->input('kids'));
        $user->price = json_encode($request->input('price'));
        $user->preferences = json_encode($request->input('preferences'));
        $user->discount = json_encode($request->input('discount'));

        if ($request->has('role')) {
            switch ($request->input('role')) {
                case 5:
                    $role = Sentinel::findRoleByName('Callcenter');
                    $role->users()->attach($user);
                    break;

                case 4:
                    $role = Sentinel::findRoleByName('Bediening');
                    $role->users()->attach($user);
                    break;

                case 2:
                    $role = Sentinel::findRoleByName('Bedrijf');
                    $role->users()->attach($user);
                    break;

                case 3:
                    $role = Sentinel::findRoleByName('Admin');
                    $role->users()->attach($user);
                    break;
            }
        }

        $user->save();

        Alert::success('Deze gebruiker is succesvol aangemaakt.')->persistent('Sluiten');
        return Redirect::to('admin/'.$this->slugController.'/update/'.$user->id);
    }

    /**
     * This function is used for activating deactivating newsletters
     * @param Request $request
     * @return mixed
     */
    public function newsletter(Request $request)
    {
        $user = Sentinel::findById($request->get('id'));

        $user->newsletter = $request->get('status');
        $user->save();

        Alert::success('Deze gebruiker is succesvol aangepast.')->persistent('Sluiten');
        return redirect()->back();

    }
    public function updateAction($id, Request $request)
    {
       	$this->validate($request, [
            'name' => 'required',
            'password' => 'min:8|confirmed',
            'email' => 'required|email|unique:users,email,'.$id,
  	    ]);

       	$user = Sentinel::findById($id);
        $user->name = $request->input('name');
        $user->phone = $request->input('phone');
        $user->attempts=$request->input('attempts');
        $user->gender = $request->input('gender');
        $user->saldo = $request->input('saldo');
        $user->birthday_at = $request->input('birthday_at');
        $user->default_role_id = $request->input('role');
        $user->city  = json_encode($request->input('city'));
        $user->facilities = json_encode($request->input('facilities'));
        $user->kitchens = json_encode($request->input('kitchens'));
        $user->allergies = json_encode($request->input('allergies'));
        $user->sustainability = json_encode($request->input('sustainability'));
        $user->kids = json_encode($request->input('kids'));
        $user->price = json_encode($request->input('price'));
        $user->preferences = json_encode($request->input('preferences'));
        $user->discount = json_encode($request->input('discount'));
        $user->lang = $request->input('lang');


        $roleArray = array(
            'Callcenter',
            'Bediening',
            'Bedrijf',
            'Admin'
        );

        if ($request->has('role')) {
            foreach ($roleArray as $roleInfo)  {
                $role = Sentinel::findRoleByName($roleInfo);

                if ($role != null)  {
                    $role->users()->detach($user);
                }
            }

            switch ($request->input('role')) {
                case 5:
                    $role = Sentinel::findRoleByName('Callcenter');
                    $role->users()->attach($user);
                    break;

                case 4:
                    $role = Sentinel::findRoleByName('Bediening');
                    $role->users()->attach($user);
                    break;

                case 2:
                    $role = Sentinel::findRoleByName('Bedrijf');
                    $role->users()->attach($user);
                    break;

                case 3:
                    $role = Sentinel::findRoleByName('Admin');
                    $role->users()->attach($user);
                    break;
            }
        }

        if ($request->has('password')) {
            Sentinel::update($user, array('password' => $request->input('password')));
        }

        $user->save();

        Alert::success('Deze gebruiker is succesvol aangepast.')->persistent('Sluiten');
        return Redirect::to('admin/'.$this->slugController.'/update/'.$user->id);
    }

    public function deleteAction(Request $request)
    {
        if ($request->input('id') != '') {
            foreach ($request->input('id') as $id) {
                $data = Sentinel::findById($id);
                if ($data != '')  {
                    $data->delete();
                }
            }
        }

        Alert::success('De gekozen selectie is succesvol verwijderd.')->persistent("Sluiten");
        return Redirect::to('admin/'.$this->slugController);
    }

    public function login($id)
    {
        $user = Sentinel::findById($id);

        if (count($user) == 1) {
            Sentinel::login($user);

            Alert::success('U bent succesvol ingelogd op het account van '.$user->name)
                ->persistent('Sluiten');

            return Redirect::to('/');
        } else {
            Alert::error('Deze gebruiker bestaat niet.')->persistent("Sluiten");
            return Redirect::to('admin/'.$this->slugController);
        }
    }

    public function resetSaldo($id)
    {
        $payments = Payment::select(
            DB::raw('"" AS restaurant_is_paid'),
            'users.name AS userName',
            'payments.created_at as date',
            'payments.created_at as time',
            'payments.status AS name',
            'payments.amount AS amount',
            'payments.status AS status',
            DB::raw('IF(payments.type = "voordeelpas", "Voordeelpas gekocht", "Opwaardering") as type'),
            DB::raw('"UwVoordeelpas" as company'),
            DB::raw('date(date_add(payments.created_at, interval 90 day)) as expired_date')
        )
            ->leftJoin('users', 'users.id', '=', 'payments.user_id')
            ->where('payments.user_id', $id)
        ;

        $transactions = Transaction::select(
            DB::raw('"" AS restaurant_is_paid'),
            'users.name AS userName',
            'transactions.created_at as date',
            'transactions.created_at as time',
            'transactions.program_id AS name',
            'transactions.amount AS amount',
            'transactions.status AS status',
            DB::raw('"Transactie" as type'),
            DB::raw('affiliates.name as company'),
            DB::raw('date(date_add(transactions.created_at, interval 90 day)) as expired_date')
        )
            ->leftJoin('affiliates', 'transactions.program_id', '=', 'affiliates.program_id')
            ->leftJoin('users', 'users.id', '=', 'transactions.user_id')
            ->where('transactions.user_id', $id)
        ;

        $items = Reservation::select(
            'reservations.restaurant_is_paid AS restaurant_is_paid',
            'users.name AS userName',
            'reservations.created_at as date',
            'reservations.time as time',
            'companies.name AS name',
            'reservations.saldo AS amount',
            DB::raw('"" AS status'),
            DB::raw('"Reservering" as type'),
            DB::raw('companies.name as company'),
            DB::raw('date(date_add(reservations.created_at, interval 90 day)) as expired_date')
        )
            ->leftJoin('companies', 'reservations.company_id', '=', 'companies.id')
            ->leftJoin('users', 'reservations.user_id', '=', 'users.id')
            ->where('reservations.user_id', $id)
            ->unionALL($payments)
            ->unionALL($transactions)
            ->get()
        ;

        $newSaldo = 0;

        if (count($items) >= 1) {
            alert()->success('', 'Het spaartegoed van de opgegeven gebruiker is succesvol gereset.')->persistent('Sluiten');

            foreach ($items as $key => $value) {
                switch ($value->type) {
                    case 'Voordeelpas gekocht':
                        $newSaldo = $newSaldo - $value->amount;
                        break;

                    case 'Reservering':
                        $newSaldo = $newSaldo - $value->amount;
                        break;

                    case 'Opwaardering':
                        $newSaldo = $newSaldo + $value->amount;
                        break;

                    case 'Transactie':
                        $newSaldo = $newSaldo + $value->amount;
                        break;
                }
            }

            $user = Sentinel::findById($id);
            $user->saldo = $newSaldo;
            $user->save();
        } else {
            alert()->error('', 'Deze gebruik heeft geen saldo.')->persistent('Sluiten');
        }

        return Redirect::to('admin/users');

    }

   public function unsubscribe($id){
      $user = Sentinel::findById($id);
      if(!empty($user)){
        $user->newsletter=0;
        $user->save();
        Alert::success('Afgesloten Nieuwsbrief succesvol.')->persistent('Sluiten');
      }
      else {
          alert()->error('', 'Kan nu niet uitschrijven. Er is iets fout gegaan..')->persistent('Sluiten');
      }
      return Redirect::to('/');

    }
	
	public function extentionlist(Request $request)
    {
        $preferences = new Preference();
        $regio = $preferences->getRegio();

       
		$data  = DB::select(DB::raw("SELECT lower(substring_index(email, '@', -1)) AS domain, COUNT(*) AS email_count FROM guests_wifi GROUP BY substring_index(email, '@', -1) HAVING domain NOT IN (SELECT email_extension FROM guest_list_extension ) ")
		);
		if(count($data)>0){
			foreach($data as $d){
				
				DB::table('guest_list_extension')->insert(array('email_extension'   =>   $d->domain ));
			}
		}
		
		$data_ext  = DB::table("guest_list_extension");
		
		if ($request->has('q')) {
            $data_ext = $data_ext->where(
                'guest_list_extension.email_extension', 'LIKE', '%'.$request->input('q').'%'
            );
        }
		
		
		if ($request->has('sort') && $request->has('order') ) {
			if($request->input('sort')=="total_email"){ 
				if(count($data)>0){
					foreach($data as $d){ echo "b";
						$data_ext = $data_ext->orderBy('guests_wifi.'.$d->email_count, $request->input('order'));			
					}
				}
				session(['sort' => $request->input('sort'), 'order' => $request->input('order')]);
			}else{ 
				$data_ext = $data_ext->orderBy('guest_list_extension.'.$request->input('sort'), $request->input('order'));
				session(['sort' => $request->input('sort'), 'order' => $request->input('order')]);
			}
        } else {
            $data_ext = $data_ext->orderBy('guest_list_extension.id', 'desc');
        }
		
				
        $dataCount = $data_ext->count();
		$data_ext = $data_ext->paginate($request->input('limit', 15));
        $data_ext->setPath('list');

        # Redirect to last page when page don't exist
        if ($request->input('page') > $data_ext->lastPage()) {
            $lastPageQueryString = json_decode(json_encode($request->query()), true);
            $lastPageQueryString['page'] = $data_ext->lastPage();

            return Redirect::to($request->url().'?'.http_build_query($lastPageQueryString));
        }

        $queryString = $request->query();
		
        unset($queryString['source']);
        unset($queryString['has_saving']);
        unset($queryString['limit']);

		// get total of emails
		for($i=0; $i<count($data_ext); $i++){
			
			$data  = DB::select(DB::raw("SELECT COUNT(*) AS total FROM guests_wifi WHERE lower(substring_index(email, '@', -1)) IN (SELECT email_extension FROM guest_list_extension WHERE id='{$data_ext[$i]->id}')"));
			
			$data_ext[$i]->total_email = $data[0]->total;
		}
		
        return view('admin/'.$this->slugController.'/extensionlist', [
            'data' => $data_ext,
            'regio' => $regio['regio'],
            'countItems' => $dataCount,
            'slugController' => $this->slugController,  
			'queryString' => $queryString,			
            'paginationQueryString' => $request->query(),
            'limit' => $request->input('limit', 15),
            'section' => $this->section,
            'currentPage' => 'Overzicht',
            'companies' => $this->companies,
        ]);
    }
	public function extensioncreate()
    {
        return view('admin/'.$this->slugController.'/extensioncreate', [
            'section' => $this->section,
            'slugController' => $this->slugController,
            'section' => $this->section,
            'currentPage' => 'Nieuwe gebruiker',
            'roles' => $this->roles
        ]);
    }
	
	 public function extensioncreateAction(Request $request)
    {
        $this->validate($request, [
	         'extension' => 'required',
        	 
        ]);
        $extension = $request->input('extension');
        $status = $request->input('status');
		
		$data = DB::table('guest_list_extension')
						->where('email_extension', $extension)
						->first();
		
		if(count($data)==0){
		$id = DB::table('guest_list_extension')-> insertGetId(array(
				'email_extension' => $extension,        
				'id1' => $status,        
			));
			Alert::success('Deze gebruiker is succesvol aangemaakt.')->persistent('Sluiten');
        return Redirect::to('admin/'.$this->slugController.'/list/update/'.$id);
		}

         return Redirect::to('admin/'.$this->slugController.'/list/create/');
    }
	 public function extensionupdate($id)
    {
        
		
		$data = DB::table('guest_list_extension')
						->where('id', $id)
						->first();
        //removed the section duplicate key

        return view('admin/'.$this->slugController.'/extensionupdate', [
            'data' => $data,
            'section' => $this->section,
            'slugController' => $this->slugController,
            'currentPage' => 'Wijzig gebruiker',
            'roles' => $this->roles
        ]);
    }
	public function extensionupdateAction($id, Request $request)
    {
       

        $this->validate($request, [
	         'extension' => 'required',
        	 
        ]);
        $extension = $request->input('extension');
        $status = $request->input('status');
		$data = DB::table('guest_list_extension')
						->where('email_extension', $extension)
						->first();
		
		if(count($data)==0){
			DB::table('guest_list_extension')
			->where('id', $id)  
			->limit(1)  
			->update(array('email_extension' => $extension,        
				'id1' => $status));
		}

        Alert::success('Deze gebruiker is succesvol aangemaakt.')->persistent('Sluiten');
        return Redirect::to('admin/'.$this->slugController.'/list/update/'.$id);
    }
	
	public function guestwifi(Request $request)
    {
		
		
		$preferences = new Preference();
        $regio = $preferences->getRegio();
		$data  =DB::table('guests_wifi')->select(
				'guests_wifi.id',
				'guests_wifi.name',
				'guests_wifi.email',
				'guests_wifi.phone',
				'guests_wifi.created_at',
				'guests_wifi.updated_at',
				'companies.name as companyName',
				'companies.regio'
			)->leftJoin('companies', 'guests_wifi.company_id', '=', 'companies.id');
		
		
			$dataCount = $data->count();
			$data->groupBy('guests_wifi.id');
			
			if ($request->has('city')) {
				 $regioName = $request->input('city');

				if (isset($regio['regioNumber'][$regioName])) {
					$data = $data->whereNotNull(
						'companies.regio'
					)
						->where('companies.regio', 'REGEXP', '"([^"]*)'.$regio['regioNumber'][$regioName].'([^"]*)"')
					;
				}
			}
			
			if ($request->has('company')) {
				$companyName = $request->input('company');
				$data = $data->where('companies.slug', '=', $companyName);
			}
			
			if ($request->has('sort') && $request->has('order')) {
				if($request->input('sort')=="city"){
					$data = $data->orderBy('companies.'.$request->input('sort'), $request->input('order'));
				}else if($request->input('sort')=="company"){
					$data = $data->orderBy('companies.name', $request->input('order'));
				}else{
					$data = $data->orderBy('guests_wifi.'.$request->input('sort'), $request->input('order'));
				}
				session(['sort' => $request->input('sort'), 'order' => $request->input('order')]);
			} else {
				$data = $data->orderBy('guests_wifi.id', 'desc');
			}
			
			$data = $data->paginate($request->input('limit', 15));
			$data->setPath($this->slugController);

			
			if ($request->input('page') > $data->lastPage()) {
				$lastPageQueryString = json_decode(json_encode($request->query()), true);
				$lastPageQueryString['page'] = $data->lastPage();

				return Redirect::to($request->url().'?'.http_build_query($lastPageQueryString));
			}

			$queryString = $request->query();
			unset($queryString['source']);
			unset($queryString['has_saving']);
			unset($queryString['limit']);
			return view('admin/'.$this->slugController.'/guestwifi', [
			'data' => $data,
            'regio' => $regio['regio'],
            'countItems' => $dataCount,
            'slugController' => $this->slugController,
            'queryString' => $queryString,
			'paginationQueryString' => $request->query(),
			'limit' => $request->input('limit', 15),
			'section' => $this->section,
            'currentPage' => 'Overzicht',
           'companies' => $this->companies,
        ]);
	}
	public function guestthirdparty(Request $request)
    {
		$preferences = new Preference();
        $regio = $preferences->getRegio();
		$data  =DB::table('guests_third_party')->select(
				'guests_third_party.id',
				'guests_third_party.name',
				'guests_third_party.email',
				'guests_third_party.phone',
				'guests_third_party.created_at',
				'guests_third_party.updated_at',
				'companies.name as companyName',
				'companies.regio'
			)->leftJoin('companies', 'guests_third_party.restaurant_id', '=', 'companies.id');
			$dataCount = $data->count();

			$data = $data->paginate($request->input('limit', 15));
			$data->setPath($this->slugController);

			
			if ($request->input('page') > $data->lastPage()) {
				$lastPageQueryString = json_decode(json_encode($request->query()), true);
				$lastPageQueryString['page'] = $data->lastPage();

				return Redirect::to($request->url().'?'.http_build_query($lastPageQueryString));
			}

			$queryString = $request->query();
			unset($queryString['source']);
			unset($queryString['has_saving']);
			unset($queryString['limit']);
			return view('admin/'.$this->slugController.'/guestthirdparty', [
			'data' => $data,
            'regio' => $regio['regio'],
            'countItems' => $dataCount,
            'slugController' => $this->slugController,
            'queryString' => $queryString,
			'paginationQueryString' => $request->query(),
			'limit' => $request->input('limit', 15),
			'section' => $this->section,
            'currentPage' => 'Overzicht',
           'companies' => $this->companies,
        ]);
	}
}
