@extends('template.theme')

@section('content')
<?php

use App\Models\Company;

?>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

<div class='container'>
    <div class='row'>
        <div class='col-lg-12'>
            <div style='padding:15px 15px 15px 0px;'>
                <h4>VIEW TABLE  <a  href="{{ url("admin/tables") }}" class="btn btn-info btn-sm" style='font-size: 16px;'>BACK</a></h4>
                <hr/>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <table class="table table-bordered">
                <tr>
                    <th>TABLE ID</th>
                    <td>{{$model->id}}</td>
                </tr>
                <tr>
                    <th>TABLE NUMBER</th>
                    <td>{{$model->table_number}}</td>
                </tr>
                <tr>
                    <th>SEATING</th>
                    <td>{{$model->seating}} Persons</td>
                </tr>
                <tr>
                    <th>DESCRIPTION</th>
                    <td>{{$model->description}}</td>
                </tr>
                <tr>
                    <th>PRIORITY</th>
                    <td>{{$model->priority}}</td>
                </tr>
                <tr>
                    <th>DESCRIPTION</th>
                    <td>{{$model->duration}} Mins</td>
                </tr>
					<?php if(Sentinel::getUser()->roles[0]->id == 1) { ?>
                <tr>
                    <th>COMPANY</th>
                    <td>{{Company::find($model->comp_id)->name}}</td>
                </tr>
					<?php } ?>
            </table>
        </div>
    </div>
</div>
@stop