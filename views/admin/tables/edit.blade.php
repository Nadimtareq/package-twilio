@extends('template.theme')

@section('content')
<?php

use App\Models\Company;
?>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<style>
    .form-control {
        display: block !important;
        width: 100% !important;
        height: 34px !important;
        padding: 6px 12px !important;
        font-size: 14px !important;
        line-height: 1.42857143 !important;
        color: #555 !important;
        background-color: #fff !important;
        background-image: none !important;
        border: 1px solid #ccc !important;
        border-radius: 4px !important;
        -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.075) !important;
        box-shadow: inset 0 1px 1px rgba(0,0,0,.075) !important;
        -webkit-transition: border-color ease-in-out .15s,-webkit-box-shadow ease-in-out .15s !important;
        -o-transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s !important;
        transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s !important;
    }
</style>
<div class='container'>
    <div class='row'>
        <div class='col-lg-12'>
            <div style='padding:15px 15px 15px 0px;'>
                <h4>UPDATE TABLE  <a  href="{{ url("admin/tables") }}" class="btn btn-info btn-sm" style='font-size: 16px;'>BACK</a></h4>
                <hr/>
            </div>
        </div>
    </div>

    @if($errors->any())
    <div class="alert alert-danger" style='margin:15px 0px;padding:15px;font-size:17px;color:red;background: #FFCDD2;'>
        @foreach($errors->all() as $error)
        <p>{{ $error }}</p>
        @endforeach
    </div>
    @endif

    @if(Session::has('flash_message'))
    <div class="alert alert-success" style='margin:15px 0px;padding:8px;font-size:17px;color:green;background: #A5DC86;'>
        {{ Session::get('flash_message') }}
    </div>
    @endif

    {!! Form::model($model, [
    "method" => "POST", "url" => ["admin/tables/update", $model->id]]) !!}
    <div class='row'>
        <div class='col-lg-3'>

            {!! Form::hidden("id", null, ["class" => "form-control"]) !!}

            <div class="form-group">
                {!! Form::label("table_number", "tafelnummer", ["class" => "control-label"]) !!}
                {!! Form::text("table_number", null, ["class" => "form-control"]) !!}
            </div>
        </div>
        <div class='col-lg-3'>
            <div class="form-group">
                {!! Form::label("seating", "personen ( No of persons )", ["class" => "control-label"]) !!}
                {!! Form::text("seating", null, ["class" => "form-control"]) !!}
            </div>
        </div>
        <div class='col-lg-3'>
            <div class="form-group">
                {!! Form::label("priority", "Prioriteit ( less number has high priority )", ["class" => "control-label"]) !!}
                {!! Form::text("priority", null, ["class" => "form-control"]) !!}
            </div>
        </div>
    </div>
    <div class='row'>
		<?php if(Sentinel::getUser()->roles[0]->id == 1) { ?>
        <div class='col-lg-3'>
            <div class='form-group'>
                {!! Form::label('comp_id', 'COMPANY', ['class' => 'control-label']) !!}
                {!! Form::select('comp_id',$company ,0,array('class' => 'form-control')) !!}
            </div>
        </div>
		<?php  } ?>
        <div class='col-lg-3'>
            <div class="form-group">
                {!! Form::label("duration", "duur in min ( In Minutes )", ["class" => "control-label"]) !!}
                {!! Form::text("duration", null, ["class" => "form-control"]) !!}
            </div>
        </div>
		<?php if(Sentinel::getUser()->roles[0]->id != 1) { ?>
		<div class='col-lg-6'>
            <div class="form-group">
                {!! Form::label("description", "omschrijving", ["class" => "control-label"]) !!}
                {!! Form::textarea("description", null, ["class" => "form-control"]) !!}
            </div>
        </div>
		<?php  } ?>
    </div>
    <div class='row'>
	<?php if(Sentinel::getUser()->roles[0]->id == 1) { ?>
        <div class='col-lg-9'>
            <div class="form-group">
                {!! Form::label("description", "omschrijving", ["class" => "control-label"]) !!}
                {!! Form::textarea("description", null, ["class" => "form-control"]) !!}
            </div>
        </div>
	<?php  } ?>
	
        <div class='col-lg-9'>
            <div class="form-group">
                {!! Form::label("status", "status", ["class" => "control-label"]) !!}
                {!! Form::select('status', array(''=>'Select','0'=>'Blue','1'=>'Yellow', '2'=>'Red', '3'=>'Green'),$status, array('class' => 'form-control')) !!}
            </div>
        </div>
	
        <div class='col-lg-12'>
            <div class="form-group">{!! Form::submit('Aanpassen', ['class' => 'btn btn-primary']) !!}
            </div>
        </div>
    </div>

    {!! Form::close() !!}
</div>
@stop