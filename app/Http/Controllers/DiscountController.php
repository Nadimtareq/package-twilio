<?php
namespace App\Http\Controllers;

use Alert;
use App;
use App\Models\Payment;
use App\Models\Barcode;
use App\Models\BarcodeUser;
use App\Models\Company;
use Redirect;
use Illuminate\Http\Request;
use App\Http\Requests\DiscountBuyRequest;
use Sentinel;

class DiscountController extends Controller 
{

    public function buy(Request $request) 
    {
        if ($request->has('restaurant')) {
            $company = Company::select(
                'companies.id'
            )
                ->where('slug', '=', $request->input('restaurant'))
                ->with('media')
                ->first()
            ;

            if ($company) {
                $media = $company->getMedia();
            }
        }

        if (!Sentinel::check()) {
            return view('pages/discount/buy', array(
                'company' => (isset($company) ? $company : NULL),
                'media' => (isset($media) ? $media : NULL)
            ));
        } else {
            $checkDiscountCard = BarcodeUser::leftJoin('barcodes', 'barcodes.code', '=', 'barcodes_users.code')
                ->where('barcodes_users.user_id', '=', Sentinel::getUser()->id)
                ->where(function ($query) use($request) {
                     $query
                        ->whereNull('barcodes.expire_date')
                        ->whereRaw('date(date_add(barcodes_users.created_at, interval 1 year)) >= "'.date('Y-m-d').'"')
                        ->orWhereRaw('barcodes.expire_date >= "'.date('Y-m-d').'"')
                    ;
                })
                ->first()
            ;

                return view('pages/discount/buy', array(
                    'company' => (isset($company) ? $company : NULL),
                    'media' => (isset($media) ? $media : NULL)
                ));
           
        }
    }

    public function buyDirect(Request $request) 
    {
        $checkDiscountCard = BarcodeUser::leftJoin('barcodes', 'barcodes.code', '=', 'barcodes_users.code')
            ->where('barcodes_users.user_id', '=', Sentinel::getUser()->id)
            ->where(function ($query) use($request) {
                 $query
                    ->whereNull('barcodes.expire_date')
                    ->whereRaw('date(date_add(barcodes_users.created_at, interval 1 year)) >= "'.date('Y-m-d').'"')
                    ->orWhereRaw('barcodes.expire_date >= "'.date('Y-m-d').'"')
                ;
            })
            ->first()
        ;

        if (count($checkDiscountCard) == 0) {
            if (Sentinel::getUser()->saldo >= 14.95) {
                if ($request->has('confirm')) {
                    $payment = new Payment();
                    $payment->status = 'paid';
                    $payment->type = 'voordeelpas';
                    $payment->user_id = Sentinel::getUser()->id;
                    $payment->amount = 14.95;
                    $payment->save();

                    $user = Sentinel::getUser();
                    $user->saldo = $user->saldo - 14.95;
                    $user->terms_active = 1;
                    $user->save();

                    $getBarcode = Barcode::where(
                        'is_active', '=', '1'
                    )
                        ->where('company_id', 0)
                        ->orderByRaw('RAND()')
                        ->take(1)
                        ->first()
                    ;

                    if (count($getBarcode) >= 1) {
                        $getBarcode->is_active = 0;
                        $getBarcode->save();

                        $barcodeUser = new BarcodeUser();
                        $barcodeUser->user_id = Sentinel::getUser()->id;
                        $barcodeUser->barcode_id = $getBarcode->id;
                        $barcodeUser->code = $getBarcode->code;
                        $barcodeUser->is_active = 1;
                        $barcodeUser->save();
                    }

                    Alert::success('U heeft succesvol een voordeelpas aangeschaft.')->persistent('Sluiten');

                    return Redirect::to(($request->has('redirect_to') ? urldecode($request->input('redirect_to')) : 'voordeelpas/buy'));
                } else {
                    return view('pages/discount/buy-alert');
                }
            } else {
                return Redirect::to('payment/charge?buy=voordeelpas&direct=1');
            }
        } else {
            alert()->error('', 'U heeft al een voordeelpas aangeschaft.')->persistent('Sluiten');
            return Redirect::to('voordeelpas/buy');
        }
    }

    public function buyAction(DiscountBuyRequest $request) 
    {
        $this->validate($request, []);

        $checkDiscountCard = BarcodeUser::leftJoin('barcodes', 'barcodes.code', '=', 'barcodes_users.code')
            ->where('barcodes_users.user_id', '=', Sentinel::getUser()->id)
            ->where(function ($query) use($request) {
                 $query
                    ->whereNull('barcodes.expire_date')
                    ->whereRaw('date(date_add(barcodes_users.created_at, interval 1 year)) >= "'.date('Y-m-d').'"')
                    ->orWhereRaw('barcodes.expire_date >= "'.date('Y-m-d').'"')
                ;
            })
                ->first()
        ;

        if (count($checkDiscountCard) == 0) {
            if (Sentinel::getUser()->saldo >= 14.95) {
                $payment = new Payment();
                $payment->status = 'paid';
                $payment->type = 'voordeelpas';
                $payment->user_id = Sentinel::getUser()->id;
                $payment->amount = 14.95;
                $payment->save();

                $user = Sentinel::getUser();
                $user->saldo = $user->saldo - 14.95;
                $user->terms_active = 1;
                $user->save();

                $getBarcode = Barcode::where('is_active', '=', '1')
                    ->orderByRaw('RAND()')
                    ->take(1)
                    ->first()
                ;

                if (count($getBarcode) >= 1) {
                    $getBarcode->is_active = 0;
                    $getBarcode->save();

                    $barcodeUser = new BarcodeUser();
                    $barcodeUser->user_id = Sentinel::getUser()->id;
                    $barcodeUser->barcode_id = $getBarcode->id;
                    $barcodeUser->code = $getBarcode->code;
                    $barcodeUser->is_active = 1;
                    $barcodeUser->save();
                }

                Alert::success('U heeft succesvol een voordeelpas aangeschaft.')->persistent('Sluiten');

                return Redirect::to('voordeelpas/buy');
            } else {
                return Redirect::to('payment/charge?buy=voordeelpas');
            }
        } else {
            alert()->error('', 'U heeft al een voordeelpas aangeschaft.')->persistent('Sluiten');
            return Redirect::to('voordeelpas/buy');
        }
    }
}