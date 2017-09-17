<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Preference;
use App\User;
use Alert;
use Mail;
use App;
use DB;
use Config;
use Illuminate\Http\Request;
use Redirect;

class PaymentsController extends Controller 
{
    public function __construct(Request $request) 
    {
        $this->slugController = 'payments';
        $this->limit = $request->input('limit', 15);
    }

    public function index(Request $request) 
    {
        $payments = Payment::select(
            'payments.id',
            'payments.created_at',
            'payments.amount',
            'payments.type',
            'payments.mollie_id',
            'payments.status',
            'payments.payment_type',
            'users.id as user_id',
            'users.name',
            'users.email'
            )
        ->leftJoin('users', 'payments.user_id', '=', 'users.id')
        ;

        $totalAmountForQuery = 0;

        $paymentsCount = $payments->count();
        if ($paymentsCount > 0) {
            $records_for_cnt_amounts = $payments->get();
            foreach ($records_for_cnt_amounts as $pay) {
                if(isset($pay['amount']) && !empty($pay['amount'])){
                    $totalAmountForQuery += $pay['amount'];
                }
            }
        }


        # Filter by column
        if ($request->has('sort') && $request->has('order')) {
            $payments = $payments->orderBy($request->input('sort'), $request->input('order'));

            session(['sort' => $request->input('sort'), 'order' => $request->input('order')]);
        } else {
            $payments = $payments->orderBy('payments.created_at', 'desc');
        }

        if ($request->has('source')) {
            switch ($request->input('source')) {
                case 'wifi':
                $payments = $payments
                ->leftJoin('guests_wifi', 'guests_wifi.email', '=', 'users.email') 
                ->whereNotNull('guests_wifi.email')
                ->whereNotNull('guests_wifi.name')
                ;
                break;
                
                default:
                $payments = $payments
                ->leftJoin('reservations', 'reservations.user_id', '=', 'users.id')  
                ->where('reservations.source', '=', $request->input('source'))
                ;
                break;
            }


            $totalAmountForQuery = 0;

            $paymentsCount = $payments->count();
            if ($paymentsCount > 0) {
                $records_for_cnt_amounts = $payments->get();
                foreach ($records_for_cnt_amounts as $pay) {
                    if(isset($pay['amount']) && !empty($pay['amount'])){
                        $totalAmountForQuery += $pay['amount'];
                    }
                }
            }
        }

        # Filter by search term
        if ($request->has('q')) {
            $payments = $payments->where(function ($query) use($request) {
                $query
                ->where('payments.mollie_id', 'LIKE', '%'.$request->input('q').'%' )
                ->orWhere('payments.created_at', 'LIKE', '%'.$request->input('q').'%')
                ->orWhere('payments.status', 'LIKE', '%'.$request->input('q').'%')
                ->orWhere('payments.amount', 'LIKE', '%'.$request->input('q').'%')
                ->orWhere('users.name', 'LIKE', '%'.$request->input('q').'%')
                ;
            });


            $totalAmountForQuery = 0;

            $paymentsCount = $payments->count();
            if ($paymentsCount > 0) {
                $records_for_cnt_amounts = $payments->get();
                foreach ($records_for_cnt_amounts as $pay) {
                    if(isset($pay['amount']) && !empty($pay['amount'])){
                        $totalAmountForQuery += $pay['amount'];
                    }
                }
            }
        }

        if ($request->has('city')) {
            $preferences = new Preference();
            $regio = $preferences->getRegio();

            $regioName = $request->input('city');

            if (isset($regio['regioNumber'][$regioName])) {
                $payments = $payments->whereNotNull(
                    'users.city'
                    )
                ->where('users.city', 'REGEXP', '"([^"]*)'.$regio['regioNumber'][$regioName].'([^"]*)"')
                ;
            }

            $totalAmountForQuery = 0;

            $paymentsCount = $payments->count();
            if ($paymentsCount > 0) {
                $records_for_cnt_amounts = $payments->get();
                foreach ($records_for_cnt_amounts as $pay) {
                    if(isset($pay['amount']) && !empty($pay['amount'])){
                        $totalAmountForQuery += $pay['amount'];
                    }
                }
            }
        }

        # Filter by month and year
        if ($request->has('month') && $request->has('year')) {  
            $payments = $payments
            ->whereMonth('payments.created_at', '=', $request->input('month'))
            ->whereYear('payments.created_at', '=', $request->input('year'));

            $totalAmountForQuery = 0;

            $paymentsCount = $payments->count();
            if ($paymentsCount > 0) {
                $records_for_cnt_amounts = $payments->get();
                foreach ($records_for_cnt_amounts as $pay) {
                    if(isset($pay['amount']) && !empty($pay['amount'])){
                        $totalAmountForQuery += $pay['amount'];
                    }
                }
            }
        }

        # Filter by ststus
        if ($request->has('status')) {  
            $payments = $payments->where('payments.status', '=', $request->input('status'));


            $totalAmountForQuery = 0;

            $paymentsCount = $payments->count();
            if ($paymentsCount > 0) {
                $records_for_cnt_amounts = $payments->get();
                foreach ($records_for_cnt_amounts as $pay) {
                    if(isset($pay['amount']) && !empty($pay['amount'])){
                        $totalAmountForQuery += $pay['amount'];
                    }
                }
            }
        }

        $payments = $payments
        ->where('payments.mollie_id', '!=', '')
        ->where('status', '=', 'paid')
        ->whereIn('type', ['mollie','opwaardering'])
        // ->where('type', '=', 'opwaardering')
        ->orWhere('type', 'LIKE', '%invoice_%')
        ->paginate($this->limit)
        ;

        # Redirect to last page when page don't exist
        if ($request->input('page') > $payments->lastPage()) { 
            $lastPageQueryString = json_decode(json_encode($request->query()), true);
            $lastPageQueryString['page'] = $payments->lastPage();

            return Redirect::to($request->url().'?'.http_build_query($lastPageQueryString));
        }
        
        $monthsYears = Payment::select(
            DB::raw('month(created_at) as months, year(created_at) as years')
            )
        ->groupBy('years', 'months')
        ->orderBy('months', 'asc')
        ->get()
        ->toArray()
        ;

        $monthConvert = Config::get('preferences.months');

        $month = array();
        $years = array();
        $monthConvert = Config::get('preferences.months');

        foreach($monthsYears as $key => $monthYear) {
            $month[$monthYear['months']] = $monthConvert[$monthYear['months']];
            $years[$monthYear['years']] = $monthYear['years'];
        }

        $queryString = $request->query();
        unset($queryString['city']);
        unset($queryString['status']);
        unset($queryString['source']);
        unset($queryString['limit']);

        return view('admin.payments.index', array(
            'payments' => $payments,
            'months' => isset($month) ? $month : '',
            'years' => isset($years) ? $years : '',
            'monthsYears' => $monthsYears,            
            'queryString'=> $queryString,
            'slugController' => $this->slugController,
            'paginationQueryString' => $request->query(),
            'limit' => $this->limit,
            'currentPage' => 'Betalingen',
            'section' => 'Overzicht',
            'totalAmountForQuery' => $totalAmountForQuery
            ));
    }

    public function indexAction(Request $request)
    {
        $this->validate($request, array(
            'id' => 'required'
            ));

        Payment::whereIn('id', $request->input('id'))->delete();

        Alert::success('De gekozen selectie is succesvol verwijderd.')->persistent('Sluiten');
        return Redirect::to('admin/'.$this->slugController);
    }

    public function update($id)
    {
        $data = Payment::find($id);

        return view('admin/'.$this->slugController.'/update', [
            'data' => $data,
            'slugController' => $this->slugController,
            'section' => 'Betalingen', 
            'currentPage' => 'Wijzig betaling'
            ]);
    }

    public function updateAction(Request $request, $id)
    {
        $data = Payment::find($id);

        if ($data) {
            $data->status = $request->input('status');
            $data->amount = $request->input('amount');
            $data->payment_type = $request->input('payment_type');
            $data->save();

            Alert::success('Deze betaling is succesvol aangepast.')->persistent('Sluiten');   
            return Redirect::to('admin/'.$this->slugController.'/update/'.$data->id);

        }
    }
}
