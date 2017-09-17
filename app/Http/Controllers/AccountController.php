<?php

namespace App\Http\Controllers;

use Alert;
use App;
use App\Http\Requests\AccountUpdateRequest;
use App\Http\Requests\BarcodeRequest;
use App\Http\Requests\ReviewRequest;
use App\Models\Barcode;
use App\Models\Giftcard;
use App\Models\Company;
use App\Models\CompanyReservation;
use App\Models\BarcodeUser;
use App\Models\Payment;
use App\Models\Review;
use App\Models\Reservation;
use App\Models\Guest;
use App\Models\RoleUser;
use App\Models\FavoriteCompany;
use App\Models\FutureDeal;
use App\Models\MailTemplate;
use App\Models\Transaction;
use App\Models\Table;
use App\Models\ReservationOption;
use App\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\FutureDealReserve;
use Config;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
//use Sentinel;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use URL;
use DB;
use DateTime;
use Mail;
use Redirect;
use App\Models\GiftcardUse;
use App\Helpers\MoneyHelper;

class AccountController extends Controller {

    public function __construct(Request $request) {
        setlocale(LC_ALL, 'nl_NL.ISO8859-1');
        setlocale(LC_TIME, 'nl_NL.ISO8859-1');
        setlocale(LC_TIME, 'Dutch');

        $this->queryString = $request->query();

        if (isset($this->queryString['type'])) {
            unset($this->queryString['type']);
        }
        $this->slugController = 'all-future-deals';
        $this->limit = $request->input('limit', 5);
        unset($this->queryString['limit']);
		$this->companies = Company::where('no_show', '=', 0)->get();
    }

    public function settings() {
        $user = Sentinel::getUser();
        return view('account.settings');
    }

    public function settingsAction(AccountUpdateRequest $request) {
        $this->validate($request, []);
        $user = Sentinel::getUser();
        $user->name = $request->input('name');
        $user->phone = $request->input('phone');
        $user->gender = $request->input('gender');
        $user->birthday_at = $request->input('birthday_at');
        $user->city = json_encode($request->input('regio'));
        $user->facilities = json_encode($request->input('facilities'));
        $user->kitchens = json_encode($request->input('kitchens'));
        $user->allergies = json_encode($request->input('allergies'));
        $user->sustainability = json_encode($request->input('sustainability'));
        $user->kids = $request->input('kids');
        $user->newsletter = $request->get('letter');
        $user->price = json_encode($request->input('price'));
        $user->preferences = json_encode($request->input('preferences'));
        $user->discount = json_encode($request->input('discount'));
        $user->lang = $request->input('lang');

        if ($request->input('email') != Sentinel::getUser()->email) {
            $code = str_random(10);
            $user->new_email = $request->input('email');
            $user->new_email_code = $code;

            Mail::send('emails.reset-email', ['user' => $user, 'code' => $code],
                    function($message) use ($user, $request) {
                $message->to($request->input('email'))->subject('Nieuw e-mailadres');
            });

            $request->session()->flash('success_email_message',
                    'Er is een mail gestuurd naar uw nieuwe e-mailadres om uw e-mailadres te activeren.');
        }

        if ($request->has('password')) {
            Sentinel::update($user,
                    array('password' => $request->input('password')));
        }

        $user->save();

        Alert::success('Uw gegevens zijn succesvol gewijzigd.')->persistent('Sluiten');

        return Redirect::to('account');
    }

    public function barcodes() {
        $data = Barcode::select(
                        'barcodes.id', 'barcodes_users.user_id',
                        'barcodes.expire_date', 'barcodes.code',
                        'barcodes_users.is_active',
                        'barcodes_users.created_at as activatedOn',
                        'barcodes.created_at', 'users.name', 'users.phone',
                        'users.email', 'companies.name as companyName'
                )
                ->leftJoin('barcodes_users', 'barcodes.id', '=',
                        'barcodes_users.barcode_id')
                ->leftJoin('users', 'barcodes_users.user_id', '=', 'users.id')
                ->leftJoin('companies', 'companies.id', '=',
                        'barcodes.company_id')
                ->where('barcodes_users.user_id', Sentinel::getUser()->id)
                ->get()
        ;

        return view('account/barcodes', [
            'data' => $data
        ]);
    }

    public function reviews() {
        $data = Review::select(
                        'reviews.*', 'companies.name as companySlug',
                        'companies.name as companyName'
                )
                ->where('reviews.user_id', Sentinel::getUser()->id)
                ->leftJoin('companies', 'companies.id', '=',
                        'reviews.company_id')
                ->get()
        ;

        return view('account/reviews', [
            'data' => $data
        ]);
    }

    public function reviewsUpdate($id) {
        $data = Review::select(
                        'reviews.*', 'companies.name as companySlug',
                        'companies.name as companyName'
                )
                ->where('reviews.user_id', Sentinel::getUser()->id)
                ->where('reviews.id', $id)
                ->leftJoin('companies', 'companies.id', '=',
                        'reviews.company_id')
                ->first()
        ;

        if ($data->is_approved == 1) {
            alert()->error('',
                    'Uw recensie is inmiddels goedgekeurd, u kunt uw recensie niet meer wijzigen.')->persistent('Sluiten');
            return Redirect::to('account/reviews');
        }

        if (count($data) == 1) {
            return view('account/reviews/update',
                    [
                'data' => $data
            ]);
        } else {
            App::abort(404);
        }
    }

    public function reviewsUpdateAction(ReviewRequest $request, $id) {
        $this->validate($request, []);

        $data = Review::select(
                        'reviews.*', 'companies.slug as companySlug',
                        'companies.name as companyName'
                )
                ->leftJoin('companies', 'companies.id', '=',
                        'reviews.company_id')
                ->where('reviews.user_id', Sentinel::getUser()->id)
                ->where('reviews.id', $id)
                ->first()
        ;

        if ($data->is_approved == 1) {
            alert()->error('',
                    'Uw recensie is inmiddels goedgekeurd, u kunt uw recensie niet meer wijzigen.')->persistent('Sluiten');
            return Redirect::to('account/reviews');
        }

        if (count($data) == 1) {
            $data->content = $request->input('content');
            $data->food = $request->input('food');
            $data->service = $request->input('service');
            $data->decor = $request->input('decor');
            $data->save();

            Alert::success('Uw recensie is succesvol gewijzigd')->persistent('Sluiten');

            return Redirect::to('restaurant/' . $data->companySlug . '#reviews');
        } else {
            App::abort(404);
        }
    }

    public function reviewsDeleteAction(Request $request) {
        foreach ($request->get('id') as $key => $value) {
            $delete = Review::where('user_id', Sentinel::getUser()->id)->find($value);
            $delete->delete();
        }

        return Redirect::to('account/reviews');
    }

    public function reservationsByCompany($companySlug, $userId,
            Request $request) {
        $companyOwner = Company::isCompanyUserBySlug($companySlug,
                        Sentinel::getUser()->id);

        if ($companyOwner['is_owner'] == TRUE || Sentinel::inRole('admin')) {
            $data = Reservation::select(
                            'companies.name as company', 'companies.slug',
                            'companies.discount', 'reservations.*',
                            DB::raw('(
                        SELECT
                            count(barcodes_users.id)
                        FROM
                            barcodes_users
                        LEFT JOIN barcodes ON barcodes.code = barcodes_users.code
                        WHERE
                            barcodes_users.user_id = reservations.user_id
                        AND
                            (barcodes.expire_date is null
                        AND 
                            date(date_add(barcodes_users.created_at, interval 1 year)) >= "' . date('Y-m-d') . '"
                        OR
                            barcodes.expire_date >= "' . date('Y-m-d') . '")
                    ) as barcode')
                    )
                    ->leftJoin('companies', 'reservations.company_id', '=',
                            'companies.id')
                    ->where('companies.slug', $companySlug)
                    ->where('reservations.user_id', $userId)
            ;

            if ($request->has('sort') && $request->has('order')) {
                $data = $data->orderBy($request->input('sort'),
                        $request->input('order'));

                session(['sort' => $request->input('sort'), 'order' => $request->input('order')]);
            } else {
                $data = $data->orderBy('reservations.created_at', 'desc');
            }

            $data = $data->paginate(15);

            return view('account/reservations',
                    [
                'reservationDate' => $data,
                'paginationQueryString' => $request->query()
            ]);
        } else {
            User::getRoleErrorPopup();
            return Redirect::to('/');
        }
    }

    public function saldo(Request $request, $userId = null) {
        $payments = Payment::select(
                        DB::raw('"" AS restaurant_is_paid'),
                        'users.name AS userName', 'payments.created_at as date',
                        'payments.created_at as time',
                        'payments.status AS name', 'payments.amount AS amount',
                        'payments.status AS status',
                        'payments.type AS type',
                        //DB::raw('IF(payments.type = "voordeelpas", "Voordeelpas gekocht", "Opwaardering") as type'),
                        DB::raw('"UwVoordeelpas" as company'),
                        DB::raw('date(date_add(payments.created_at, interval 90 day)) as expired_date')
                )
                ->leftJoin('users', 'users.id', '=', 'payments.user_id')
                ->whereIn('payments.type',
                        array('mollie', 'voordeelpas', 'Cadeaubon voordeel', 'Cadeaubon aankoop'))
                ->where('payments.user_id',
                Sentinel::inRole('admin') && $userId != null ? $userId : Sentinel::getUser()->id)
        ;

        if ($request->has('month') && $request->has('year')) {
            $payments = $payments
                    ->whereMonth('payments.created_at', '=',
                            $request->input('month'))
                    ->whereYear('payments.created_at', '=',
                    $request->input('year'))
            ;
        }

        if ($request->has('sort') && $request->has('order') && $request->input('type') ==
                'payments') {
            $payments = $payments->orderBy($request->input('sort'),
                    $request->input('order'));
            session(['sort' => $request->input('sort'), 'order' => $request->input('order')]);
        } else {
            $payments = $payments->orderBy('payments.created_at', 'desc');
        }

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
                ->leftJoin('affiliates', 'transactions.program_id', '=',
                        'affiliates.program_id')
                ->leftJoin('users', 'users.id', '=', 'transactions.user_id')
                ->groupBy('transactions.id')
                ->where('transactions.user_id',
                Sentinel::inRole('admin') && $userId != null ? $userId : Sentinel::getUser()->id)
        ;

        if ($request->has('month') && $request->has('year')) {
            $transactions = $transactions
                    ->whereMonth('transactions.created_at', '=',
                            $request->input('month'))
                    ->whereYear('transactions.created_at', '=',
                    $request->input('year'))
            ;
        }

        if ($request->has('sort') && $request->has('order') && $request->input('type') ==
                'transactions') {
            $transactions = $transactions->orderBy($request->input('sort'),
                    $request->input('order'));
            session(['sort' => $request->input('sort'), 'order' => $request->input('order')]);
        } else {
            $transactions = $transactions->orderBy('transactions.created_at',
                    'desc');
        }

        $items = Reservation::select(
                        'reservations.restaurant_is_paid AS restaurant_is_paid',
                        'users.name AS userName',
                        DB::raw('CONCAT(reservations.date, " ", reservations.time) AS date'),
                        'reservations.time as time', 'companies.name AS name',
                        'reservations.saldo AS amount', DB::raw('"" AS status'),
                        DB::raw('"Reservering" as type'),
                        DB::raw('companies.name as company'),
                        DB::raw('date(date_add(reservations.created_at, interval 90 day)) as expired_date')
                )
                ->leftJoin('companies', 'reservations.company_id', '=',
                        'companies.id')
                ->leftJoin('users', 'reservations.user_id', '=', 'users.id')
                ->where('reservations.user_id',
                        Sentinel::inRole('admin') && $userId != null ? $userId : Sentinel::getUser()->id)
                ->unionALL($payments)
                ->unionALL($transactions);

        if ($request->has('month') && $request->has('year')) {
            $items = $items
                    ->whereMonth('reservations.created_at', '=',
                            $request->input('month'))
                    ->whereYear('reservations.created_at', '=',
                    $request->input('year'))
            ;
        }

        if ($request->has('sort') && $request->has('order')) {
            $items = $items->orderBy($request->input('sort'),
                    $request->input('order'));
            session(['sort' => $request->input('sort'), 'order' => $request->input('order')]);
        } else {
            $items = $items->orderBy('date', 'desc');
        }

        if ($request->input('type') == 'reservations') {
            $items = $items
                    ->get()
                    ->toArray()
            ;
        }

        if ($request->input('type') == 'payments') {
            $items = $payments
                    ->get()
                    ->toArray()
            ;
        }

        if ($request->input('type') == 'transactions') {
            $items = $transactions
                    ->get()
                    ->toArray()
            ;
        }

        if (!$request->has('type')) {
            $items = $items
                    ->get()
                    ->toArray()
            ;
        }

        $monthsYears = CompanyReservation::select(
                        DB::raw('month(date) as months, year(date) as years')
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

        foreach ($monthsYears as $key => $monthYear) {
            $month[$monthYear['months']] = $monthConvert[$monthYear['months']];
            $years[$monthYear['years']] = $monthYear['years'];
        }

        $slice = array_slice($items,
                $request->input('limit', 15) * ($request->input('page', 1) - 1),
                $request->input('limit', 15));
        $pagination = new LengthAwarePaginator($slice, count($items),
                $request->input('limit', 15));
        $pagination->setPath(URL::to('/account/reservations/saldo'));
        //dd($pagination);
        return view('account/saldo',
                [
            'data' => $pagination,
            'limit' => $request->input('limit', 15),
            'queryString' => $this->queryString,
            'paginationQueryString' => $request->query(),
            'months' => isset($month) ? $month : '',
            'years' => isset($years) ? $years : '',
            'monthsYears' => $monthsYears,
        ]);
    }

    public function barcodeAction(BarcodeRequest $request) {
        $user = Sentinel::getUser();
        $this->validate($request, []);

        if (Sentinel::inRole('barcode_user') == FALSE) {
            $role = Sentinel::findRoleByName('Barcode');
            $role->users()->attach($user);
        }

        $barcodeInfo = Barcode::where('code', $request->input('code'))
                ->where('is_active', 1)
                ->first()
        ;

        if (count($barcodeInfo) == 1) {
            $barcode = new BarcodeUser;
            $barcode->barcode_id = $barcodeInfo->id;
            $barcode->user_id = Sentinel::getUser()->id;
            $barcode->code = $request->input('code');
            $barcode->company_id = $barcodeInfo->company_id;
            $barcode->is_active = 1;
            $barcode->save();

            $request->session()->flash('success_message',
                    'Uw opgegeven barcode is succesvol ingevoerd.');
        }

        return Redirect::to('account/barcodes');
    }

    public function deleteAccount() {
        $user = Sentinel::getUser();

        Reservation::where('user_id', $user->id)->delete();
        User::where('id', $user->id)->delete();
        BarcodeUser::where('user_id', $user->id)->delete();
        FavoriteCompany::where('user_id', $user->id)->delete();
        Guest::where('user_id', $user->id)->delete();
        RoleUser::where('user_id', $user->id)->delete();
        Review::where('user_id', $user->id)->delete();

        Sentinel::logout();

        return Redirect::to('/');
    }

    public function reservations(Request $request) {
        $data = Reservation::select(
                        DB::raw(
                                'DATE_SUB(CONCAT(reservations.date, " ", reservations.time), INTERVAL company_reservations.cancel_before_time MINUTE) as cancelBeforeTime'
                        ),
                        DB::raw(
                                'DATE_SUB(CONCAT(reservations.date, " ", reservations.time), INTERVAL company_reservations.update_before_time MINUTE) as updateBeforeTime'
                        ), 'companies.name as company', 'companies.slug',
                        'company_reservations.cancel_before_time',
                        'reservations_options.name as dealname',
                        'reservations.*'
                )
                ->leftJoin('companies', 'reservations.company_id', '=',
                        'companies.id')
                ->leftJoin('company_reservations', 'company_reservations.id',
                        '=', 'reservations.reservation_id')
                ->leftJoin('reservations_options', 'reservations.option_id',
                        '=', 'reservations_options.id')
                ->groupBy('reservations.id')
                ->where('reservations.user_id', Sentinel::getUser()->id)
        ;

        if ($request->has('sort') && $request->has('order')) {
            $data = $data->orderBy($request->input('sort'),
                    $request->input('order'));

            session(['sort' => $request->input('sort'), 'order' => $request->input('order')]);
        } else {
            $data = $data->orderBy('reservations.created_at', 'desc');
        }

        $data = $data->paginate(15);

        return view('account/reservations',
                [
            'reservationDate' => $data,
            'paginationQueryString' => $request->query()
        ]);
    }

    public function reservationsAction(Request $request) {
        $past = 0;

        $reservations = Reservation::select(
                        DB::raw('DATE_SUB(CONCAT(reservations.date, " ", reservations.time), INTERVAL company_reservations.cancel_before_time MINUTE) as cancelBeforeTime'),
                        'companies.id as companyId',
                        'companies.name as companyName',
                        'companies.email as companyEmail',
                        'companies.contact_name as companyCName',
                        'company_reservations.cancel_before_time',
                        'reservations.status', 'reservations.is_cancelled',
                        'reservations.id as resId', 'reservations.name',
                        'reservations.email', 'reservations.date',
                        'reservations.saldo', 'reservations.time'
                )
                ->leftJoin('companies', 'reservations.company_id', '=',
                        'companies.id')
                ->leftJoin('company_reservations', 'company_reservations.id',
                        '=', 'reservations.reservation_id')
                ->where('reservations.user_id', Sentinel::getUser()->id)
                ->whereIn('reservations.id', $request->get('id'))
                ->get()
        ;

        if ($request->has('id')) {
            foreach ($reservations as $reservation) {
                if (
                        new DateTime() < new DateTime($reservation->date . '' . $reservation->time) &&
                        new DateTime() < new DateTime($reservation->cancelBeforeTime)
                ) {
                    Reservation::cancel($reservation);
                } else {
                    $past += 1;
                }
            }
        }

        if ($past >= 1) {
            alert()->error('',
                    'Een van de opgegeven data kunt u niet meer annuleren.')->persistent('Sluiten');
        }

        return Redirect::to('account/reservations');
    }

    public function getAllfuturedeals(Request $request) {
        $data = array();
        $user = (Sentinel::check()) ? Sentinel::getUser() : NULL;
        $data = DB::table('future_deals')->select('future_deals.id as future_deal_id',
                        'future_deals.user_id as user_id',
                        'future_deals.deal_price as future_deal_price',
                        'future_deals.persons as total_persons',
                        'future_deals.persons_remain as remain_persons',
                        'future_deals.created_at',
                        'future_deals.expired_at as expired_at')
                ->addSelect('companies.id as company_id',
                        'companies.name as company_name',
                        'companies.slug as company_slug',
                        'companies.description as company_disc',
                        'companies.city')
                ->addSelect('reservations_options.name as deal_name')
                ->addSelect('users.name as user_name')
                ->addSelect('media.id as media_id', 'media.file_name',
                        'media.disk', 'media.name as media_name')
                ->leftJoin('reservations_options', 'future_deals.deal_id', '=',
                        'reservations_options.id')
                ->leftJoin('users', 'users.id', '=', 'future_deals.user_id')
                ->leftJoin('companies', 'reservations_options.company_id', '=',
                        'companies.id')
                ->leftJoin('media',
                function ($join) {
            $join->on('companies.id', '=', 'media.model_id')
            ->where('media.model_type', '=', 'App\Models\Company')
            ->where('media.collection_name', '=', 'default');
        });

        # Filter by month and year
        if ($request->has('month') && $request->has('year')) {
            $data = $data->whereMonth('future_deals.expired_at', '=',
                            $request->input('month'))
                    ->whereYear('future_deals.expired_at', '=',
                    $request->input('year'))
            ;
        }
		if ($request->has('company')) {
            $data = $data->where('companies.id', '=',
                            $request->input('company'));
            
        }
        $data = $data->groupby('future_deals.id')->orderBy('future_deals.expired_at',
                        'desc')
                ->paginate($this->limit);

        $monthsYears = FutureDeal::select(
                        DB::raw('month(expired_at) as months, year(expired_at) as years')
                )
                ->groupBy('years', 'months')
                ->orderBy('months', 'asc')
                ->get()
                ->toArray()
        ;

        //    dd($monthsYears);
        $month = array();
        $years = array();
        $monthConvert = Config::get('preferences.months');

        foreach ($monthsYears as $key => $monthYear) {
            $month[$monthYear['months']] = $monthConvert[$monthYear['months']];
            $years[$monthYear['years']] = $monthYear['years'];
        }

        $queryString = $request->query();
        unset($queryString['limit']);
        return view('admin/featuredeals/all-future-deal',
                [
            'futureDeals' => $data,
            'currentPage' => 'All Feature deals',
            'months' => isset($month) ? $month : '',
            'years' => isset($years) ? $years : '',
            'monthsYears' => $monthsYears,
            'paginationQueryString' => $request->query(),
            'queryString' => $queryString,
            'limit' => $this->limit,
            'slugController' => $this->slugController,
			'companies' => $this->companies,
        ]);
    }

    public function futuredeals(Request $request) {
        $data = array();
        $user = (Sentinel::check()) ? Sentinel::getUser() : NULL;
        if ($user) {

            $data = DB::table('future_deals')->select(
                            'future_deals.id as future_deal_id',
                            'future_deals.deal_price as future_deal_price',
                            'future_deals.persons as total_persons',
                            'future_deals.persons_remain as remain_persons',
                            'future_deals.persons as persons',
                            'future_deals.expired_at as expired_at',
                            'reservations_options.date_to as date_to'
                    )
                    ->addSelect('companies.id as company_id',
                            'companies.name as company_name',
                            'companies.slug as company_slug',
                            'companies.description as company_disc',
                            'companies.city')
                    ->addSelect('reservations_options.name as deal_name')
                    ->addSelect('media.id as media_id', 'media.file_name',
                            'media.disk', 'media.name as media_name')
                    ->leftJoin('reservations_options', 'future_deals.deal_id',
                            '=', 'reservations_options.id')
                    ->leftJoin('companies', 'reservations_options.company_id',
                            '=', 'companies.id')
                    ->leftJoin('media',
                            function ($join) {
                        $join->on('companies.id', '=', 'media.model_id')
                        ->where('media.model_type', '=', 'App\Models\Company')
                        ->where('media.collection_name', '=', 'default');
                    })
                    ->where('future_deals.user_id', $user->id)
                    ->groupby('future_deals.id')->orderBy('future_deals.created_at',
                            'desc')
                    ->get()
            ;
        }
        return view('account/future-deal', ['futureDeals' => $data,]);
    }

    public function reserveFutureDeal(Request $request, $deal_id) {
        $data = array();
        $user = (Sentinel::check()) ? Sentinel::getUser() : NULL;
        if ($user) {
            $futureDeal = FutureDeal::where('id', $deal_id)->where('user_id',
                            $user->id)->first();
            if ($futureDeal) {
                $deal = ReservationOption::find($futureDeal->deal_id);
                $company = Company::find($deal->company_id);
                $mediaItems = $company->getMedia('default');
                return view('account/reserve-future-deal',
                        [
                    'company' => $company,
                    'user' => $user,
                    'futureDeal' => $futureDeal,
                    'deal' => $deal,
                    'mediaItems' => $mediaItems        
                ]);
            } else {
                App::abort(404);
            }
        }
    }

    public function processReserveFutureDeal(FutureDealReserve $request, $deal_id) {
        setlocale(LC_ALL, 'nl_NL', 'Dutch');
        $this->validate($request, []);
        $input_persons = $request->input('persons');
        $time = date('H:i', strtotime($request->input('time')));
        $date = date('Y-m-d', strtotime($request->input('date')));
        $user = (Sentinel::check()) ? Sentinel::getUser() : NULL;
        $futureDeal = FutureDeal::where('id', $deal_id)->where('user_id',
        $user->id)->where('expired_at', '>=', date('Y-m-d'))->first();
        
        if ($futureDeal) {
            $user_saldo = (float) MoneyHelper::getAmount($user->saldo);
            $deal_saldo = $futureDeal->extra_pay;
            if ($deal_saldo > $user_saldo) {
                $enough_balance = false;
                //$rest_amount = $deal_saldo - $user_saldo;
            } else {
                $enough_balance = true;
                $user->saldo = $user_saldo - $deal_saldo;
            }

            //return "Pending amount: ".$futureDeal->extra_pay;

            if($futureDeal->status =="pending"){
                if(!$enough_balance){
                    alert()->error('Fonds alsjeblieft uw account om de reservering te voltooien','Onvoldoende saldo')->html()->persistent('Sluiten');
                    return Redirect::to('/payment/charge');
                }else{
                    $futureDeal->extra_pay = 0;
                    $futureDeal->status = "purchased";
                    $futureDeal->save();
                    $user->save();
                    // return "You have Enough Balance";
                }
            }
            $deal = ReservationOption::find($futureDeal->deal_id);
            $company = Company::find($deal->company_id);
            if ($input_persons <= $futureDeal->persons_remain) {
                $reservationTimes = CompanyReservation::getReservationTimesArray(
                    [
                        'company_id' => array($company->id),
                        'date' => $date,
                        'selectPersons' => $input_persons,
                        'groupReservations' => ($request->has('group_reservation') ? 1 : NULL)
                    ]
                );

                if (isset($reservationTimes[$time])) {

                    $persons = $request->input('persons');

                    $tblNo = Table::where('comp_id', $company->id)->where('seating',
                                    $persons)->where('status', '0')->orderBy('priority',
                                    'asc')->take(1)->first();


                    if ($tblNo) {
                        $tableNumber = $tblNo['id'];
                    } else {
                        $tableNumber = 0;
                    }
                    $resTime = date('H:i', strtotime($request->input('time'))) . ':00';

                    $resDate = date('Y-m-d', strtotime($request->input('date')));

                    $data = new Reservation;
                    $data->date = $resDate;
                    $data->time = $resTime;
                    $data->persons = $persons;
                    $data->company_id = $company->id;
                    $data->user_id = $user->id;
                    $data->reservation_id = $reservationTimes[$time][$company->id]['reservationId'];
                    $data->name = $request->input('name');
                    $data->email = $request->input('email');
                    $data->phone = $request->input('phone');
                    $data->option_id = $deal->id;
                    $data->comment = $request->input('comment');
                    $data->saldo = (float) ($futureDeal->deal_base_price * $input_persons);
                    $data->allergies = json_encode($request->input('allergies'));
                    $data->preferences = json_encode($request->input('preferences'));
                    $data->status = 'reserved';
                    $data->table_nr = $tableNumber;
                    $data->save();

                    if ($tblNo) {
                        $checkInTime = strtotime($resDate . " " . $resTime);

                        $checkOutTime = $checkInTime + ($tblNo['duration'] * 60);
                        $release_time = date("Y-m-d H:i:s", $checkOutTime);

                        $table = Table::find($tblNo['id']);
                        $table->status = '1';
                        $table->release_time = $release_time;
                        $table->save();
                    }

                    $total_reserved_persons = (int) ($futureDeal->persons_reserved + $input_persons);
                    $total_remain_persons = (int) ($futureDeal->persons - $total_reserved_persons);

                    $futureDeal->persons_reserved = $total_reserved_persons;
                    $futureDeal->persons_remain = $total_remain_persons;

                    if ($total_remain_persons == 0) {
                        $futureDeal->status = 'full_reserved';
                    } else {
                        $futureDeal->status = 'partially_reserved';
                    }
                    $futureDeal->save();

                    $mailtemplate = new MailTemplate();

                    $mailtemplate->sendMail(array(
                        'email' => Sentinel::getUser()->email,
                        'date' => date('d-m-Y', strtotime($data->date)),
                        'persons' => $persons,
                        'template_id' => 'new-review-client',
                        'company_id' => $company->id,
                        'replacements' => array(
                            '%name%' => Sentinel::getUser()->name,
                            '%saldo%' => '',
                            '%phone%' => Sentinel::getUser()->phone,
                            '%email%' => Sentinel::getUser()->email,
                            '%date%' => date('d-m-Y', strtotime($data->date)),
                            '%time%' => date('H:i', strtotime($data->time)),
                            '%persons%' => '',
                            '%comment%' => '',
                            '%allergies%' => '',
                            '%preferences%' => ''
                        )
                    ));
                    return redirect('account/reservations');
                }
            }
        } else {
            App::abort(404);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function giftcards(Request $request) {
        $data_amount = Giftcard::where(['company_id' => 0])
                ->whereRaw('used_no < max_usage')
                ->where('is_active', 1)
                ->lists('amount', 'id');
		/** new listing code starts here 270717 **/
		$data = Giftcard::select(
            'giftcards.id',
            'giftcards.code',
            'giftcards.is_active',
            'giftcards.amount',
            'giftcards.max_usage',
            'giftcards.used_no',
            'giftcards.created_at as created',
            'companies.name as companyName'
        )
            ->leftJoin('companies', 'companies.id', '=', 'giftcards.company_id')
        ;
		$data->where('companies.user_id', '=', Sentinel::getUser()->id);

        if ($request->has('status')) {
            $data->where('giftcards.is_active', '=', $request->input('status'));
        }

        if ($request->has('q')) {
            $data = $data->where('giftcards.code', 'LIKE', '%'.$request->input('q').'%');
        }

        if ($request->has('sort') && $request->has('order')) {
            $data = $data->orderBy($request->input('sort'), $request->input('order'));

            session(['sort' => $request->input('sort'), 'order' => $request->input('order')]);
        } else {
            $data = $data->orderBy('giftcards.id', 'desc');
        }
        
        if ($request->has('company')) {
            $data = $data->where('companies.slug', '=', $request->input('company'));
        }

        $data = $data->paginate($request->input('limit', 15));
        $data->setPath('giftcards');
		/** new listing code starts ends 270717 **/
		
        return view('account/giftcards', [
            'data' => $data_amount,
			'listing' => $data
        ]);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function buyGiftcard(Request $request) {
        $this->validate($request, [
            'code' => 'required'
        ]);
        $code = Giftcard::where('id', $request->input('code'))->first();

        if (Sentinel::getUser()->saldo >= $code->amount) {
            $company = Company::where(['user_id' => Sentinel::getUser()->id])->first();
            if (count($company) > 0) {
                $code->company_id = $company['id'];
            }
            $code->buy_date = date('d-m-Y');
            $code->save();
            $payment = new Payment();
            $payment->status = 'paid';
            $payment->type = 'Cadeaubon aankoop';
            $payment->user_id = Sentinel::getUser()->id;
            $payment->amount = $code->amount;
            $payment->save();

            $user = Sentinel::getUser();
            $user->saldo = $user->saldo - $code->amount;
            $user->terms_active = 1;
            $user->save();

            Mail::send('emails.send-giftcard',
                    ['user' => Sentinel::getUser()->name, 'code' => $code],
                    function($message) use ($user, $request) {
                $message->to(Sentinel::getUser()->email)->subject('Cadeaubon');
            });
            Alert::success('U heeft succesvol een giftcard besteld.')->persistent('Sluiten');

            return Redirect::to(($request->has('redirect_to') ? urldecode($request->input('redirect_to'))
                                        : 'account/giftcards'));
        } else {
            //Amount that should be charged to the user

            $remaining_amount=$code->amount - Sentinel::getUser()->saldo;
            Sentinel::update(Sentinel::getUser(), ['saldo' => 0]);


//            return Redirect::to('payment/charge');
            return redirect()->action('PaymentController@initiateIdealPayment')->with('amount', $remaining_amount);
        }
    }
	
	public function usedGiftCard(Request $request,$id) {
        $data = GiftcardUse::select('giftcard_use.created_at','giftcards.code','giftcards.amount','users.name')
                ->leftjoin('giftcards','giftcards.id', '=', 'giftcard_use.giftcard_id')
                ->leftjoin('users','users.id', '=', 'giftcard_use.user_id')
                ->where(['giftcard_id' => $id]);
        
        $data = $data->paginate($request->input('limit', 15));

        # Redirect to last page when page don't exist
        if ($request->input('page') > $data->lastPage()) { 
            $lastPageQueryString = json_decode(json_encode($request->query()), true);
            $lastPageQueryString['page'] = $data->lastPage();

            return Redirect::to($request->url().'?'.http_build_query($lastPageQueryString));
        }

        $queryString = $request->query();
        unset($queryString['limit']);

        return view('account/used', [
            'data' => $data, 
            'slugController' => 'giftcards',
            'queryString' => $queryString,
            'paginationQueryString' => $request->query(),
            'limit' => $request->input('limit', 15),
            'section' => 'Giftcards', 
            'currentPage' => 'Gebruik giftcard'
        ]);
    }
	
	public function updateGiftCard($id)
    {
        $data = Giftcard::leftJoin('companies', 'companies.id', '=', 'giftcards.company_id');

        //$data = $data->where('companies.user_id', Sentinel::getUser()->id);

        $data = $data->find($id);

        return view('account/update', [
            'data' => $data,
            'companies' => Company::lists('name', 'id'),
            'slugController' => 'giftcards',
            'section' => 'Giftcards', 
            'currentPage' => 'Wijzig giftcard'
        ]);
    }
	public function updateGiftCardAction(Request $request, $id)
    {
        $this->validate($request, [
            'code' => 'required|unique:giftcards,code,'.$id,
        ]);

        $data = Giftcard::find($id);
        $data->code = $request->input('code');
        $data->company_id = $request->input('company');
        $data->is_active = $request->input('is_active');
        $data->max_usage = $request->input('max_usage'); 
        $data->amount = $request->input('amount'); 
        $data->save();

        Alert::success('Deze giftcard is succesvol gewijzigd.')->persistent('Sluiten');

        return Redirect::to('account/giftcards/updateGiftCard/'.$data->id);
    }
}
