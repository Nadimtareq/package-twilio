<?php

namespace App\Http\Controllers\admin;
use Alert;
use App;
use Redirect;
use Sentinel;
use App\Models\Unwanted;
use Illuminate\Http\Request;
use DB;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class UnwantedController extends Controller
{
    //
    public function __construct()
    {
        $this->slugController = 'unwanted';
        $this->section = 'ongewenste';
    }

    public function index(Request $request){
		
        $data = Unwanted::select(
            '*'
        );

        if ($request->has('q')) {
            $data = $data->where('title', 'LIKE', '%'.$request->input('q').'%');
        }

        if ($request->has('sort') && $request->has('order')) {
            $data = $data->orderBy($request->input('sort'), $request->input('order'));

            session(['sort' => $request->input('sort'), 'order' => $request->input('order')]);
        } else {
            $data = $data->orderBy('id', 'desc');
        }
        $data = $data->paginate($request->input('limit', 15));
        $queryString = $request->query();
        return view('admin/'.$this->slugController.'/index', [
            'data' => $data,
            'slugController' => $this->slugController,
            'paginationQueryString' => $request->query(),
            'queryString' => $queryString,
            'limit' => $request->input('limit', 15),
            'section' => $this->section,
//            'category' => Config::get('preferences.faq'),
            'currentPage' => 'Unwanted_word'
        ]);
    }
    public function create()
    {
        return view('admin/'.$this->slugController.'/create', [
//            'users' => $this->users,
            'slugController' => $this->slugController,
            'section' => $this->section,
            'currentPage' => 'toevoegen ongewenste woord'
        ]);
    }

    public function createAction(Request $request)
    {
        $rules = [
            'word' => 'required',
        ];


        $this->validate($request, $rules);

        $data = new Unwanted;
        $data->word = $request->input('word');
        $data->short = $request->input('short');
        $data->save();


        Alert::success('Dit verboden woord is succesvol aangemaakt.')->persistent('Sluiten');
        return Redirect::to('admin/'.$this->slugController);

    }
    public function update($id)
    {
        $data = Unwanted::where('id','=', $id);



        $data = $data->first();

        if ($data) {
            return view('admin/'.$this->slugController.'/update', [
                'data' => $data,
                'slugController' => $this->slugController,
                'section' => $this->section,
                'currentPage' => 'Wijzig Ongewenste Woord'
            ]);
        } else {
            Alert::error('Dit bedrijf bestaat helaas niet.')->persistent("Sluiten");
            return Redirect::to('admin/'.$this->slugController);
        }

    }

    public function updateAction(Request $request, $id)
    {
        $data = Unwanted::where('id','=', $id);



        $data = $data->first();

        if ($data) {
            $rules = [
                'word' => 'required',
            ];

            $this->validate($request, $rules);
            $data->word = $request->word;
			 $data->short = $request->input('short');
            $data->save();

            Alert::success('Dit bedrijf is succesvol aangepast.')->persistent('Sluiten');
            return Redirect::to('admin/'.$this->slugController);
        } else {
            Alert::error('Dit bedrijf bestaat helaas niet.')->persistent("Sluiten");
            return Redirect::to('admin/'.$this->slugController);
        }
    }

    public function deleteAction(Request $request)
    {
        $data = Unwanted::whereIn('id', $request->input('id'));

        if(Sentinel::inRole('admin') == FALSE) {
            $data = $data->where('user_id', Sentinel::getUser()->id);
        }

        switch ($request->input('action')) {
            case 'remove':
                $data->delete();

                Alert::success('De gekozen selectie is succesvol verwijderd.')->persistent("Sluiten");
                return Redirect::to('admin/'.$this->slugController);
                break;
        }
    }
}
