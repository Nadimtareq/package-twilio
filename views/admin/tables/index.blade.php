@extends('template.theme')

@section('content')
<style>
    .btn{
        height:auto;
    }
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
        border-radius: 1px !important;
        -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.075) !important;
        box-shadow: inset 0 1px 1px rgba(0,0,0,.075) !important;
        -webkit-transition: border-color ease-in-out .15s,-webkit-box-shadow ease-in-out .15s !important;
        -o-transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s !important;
        transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s !important;
    }
    .table{
        font-size: 14px;
    }

    .stable{
        cursor: move;
    }
</style>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script type="text/javascript" src="{{ asset('js/jquery-sortable.js') }}"></script>
<script>
$(document).ready(function () {
// Sortable rows
    var group = $('.sorted_table').sortable({
        group: 'simple_with_animation',
        delay: 500,
        containerSelector: 'table',
        itemPath: '> tbody',
        itemSelector: 'tr',
        placeholder: '<tr class="placeholder"/>',
        onDrop: function ($item, container, _super) {
            var data = group.sortable("serialize").get();
            var jsonString = JSON.stringify(data, null, ' ');
            _super($item, container);
            sortPriority(jsonString)
            console.log(jsonString);

        }
    });
});


function sortPriority(data) {
// Using the core $.ajax() method
    $.ajax({
        // The URL for the request
        url: "{{ url('admin/tables/setpriority') }}",
        // The data to send (will be converted to a query string)
        data: {'priority':data,'_token':'{{ csrf_token() }}'},
        // Whether this is a POST or GET request
        type: "POST",
        // The type of data we expect back
        dataType: "json",
    })
            // Code to run if the request succeeds (is done);
            // The response is passed to the function
            .done(function (json) {
                $("<h1>").text(json.title).appendTo("body");
                $("<div class=\"content\">").html(json.html).appendTo("body");
            })
            // Code to run if the request fails; the raw request and
            // status codes are passed to the function
            .fail(function (xhr, status, errorThrown) {
                //alert("Sorry, there was a problem!");
                console.log("Error: " + errorThrown);
                console.log("Status: " + status);
                console.dir(xhr);
            })
            // Code to run regardless of success or failure;
            .always(function (xhr, status) {
                //alert("The request is complete!");
            });
}

</script>
<div class='container'>
    <div class='row'>
        <div class='col-lg-12'>
            <div  style='padding:15px 15px 15px 0px;'>
                <h4>Tables Management <a  href="{{ url("admin/tables/create") }}" class="btn btn-info btn-sm" style='font-size: 16px;'>Add New</a></h4>
                <hr/>
            </div>
        </div>
    </div>
	
		
	
    <div class="row">
        <div class="col-lg-12">
            <form action="" name="searchform" id="searchform" method="GET" >
                <table class="table table-bordered table-hover table-striped sorted_table" >
                    <thead>
                        <tr>
                            <th><a href="{{str_replace("tables/?","tables?",request()->fullUrlWithQuery(["orderby"=>(request()->input('orderby')=="table_number asc")?"table_number desc":"table_number asc"]))}}">TABLE NUMBER</th>
                            <th><a href="{{str_replace("tables/?","tables?",request()->fullUrlWithQuery(["orderby"=>(request()->input('orderby')=="seating asc")?"seating desc":"seating asc"]))}}">SEATING</th>
                            <th><a href="{{str_replace("tables/?","tables?",request()->fullUrlWithQuery(["orderby"=>(request()->input('orderby')=="priority asc")?"priority desc":"priority asc"]))}}">PRIORITY</th>
                            <th><a href="{{str_replace("tables/?","tables?",request()->fullUrlWithQuery(["orderby"=>(request()->input('orderby')=="duration asc")?"duration desc":"duration asc"]))}}">DURATION</th>
                            <?php if(Sentinel::getUser()->roles[0]->id == 1) { ?>
							<th>Company</th>
							<?php } ?>
                            <th>ACTIES</th>
                        </tr>
				<?php if(Sentinel::getUser()->roles[0]->id == 1) { ?>
                        <tr>
                            <th><input class="form-control" type="text" name="table_number" value="{{request()->input('table_number')}}" ></th>
                            <th><input class="form-control" type="text" name="seating" value="{{request()->input('seating')}}" ></th>
                            <th><input class="form-control" type="text" name="priority" value="{{request()->input('priority')}}" ></th>
                            <th><input class="form-control" type="text" name="duration" value="{{request()->input('duration')}}" ></th>
                            <th><?php echo Form::select('comp_id', $companies, "", array('class' => 'ui normal search dropdown'));  ?></th>
                            <th><input type="submit" value='Search'  class="btn btn-primary"></th>
                        </tr>
				<?php } ?>
                    </thead>
                    <tbody>
				<?php if(Sentinel::getUser()->roles[0]->id == 1) { ?>
                        @foreach($model as $key=>$val)
							
                        <tr class='stable' data-id='{{$val->id}}'>
                            <td>{{$val->table_number}}</td>
                            <td>{{$val->seating}}</td>
                            <td>{{$val->priority}}</td>
                            <td>{{$val->duration}}</td>
                            <td><?php if($val->Company->name){ echo $val->Company->name;}?></td>
                            <td>
                                <a href="{{ url("admin/tables/edit",$val->id) }}" class="btn btn-warning btn-sm">wijzigen</a>
                                <a href="{{ url("admin/tables/show",$val->id) }}" class="btn btn-info btn-sm">tonen</a>
                                <a onclick="return confirm('Do you really want to delete this record?');" href="{{ url("admin/tables/destroy",$val->id) }}" class="btn btn-danger btn-sm">verwijderen</a>
                            </td>
                        </tr>
                        @endforeach
				<?php }else{?>
					  @foreach($model as $key=>$val)
							
                        <tr class='stable' data-id='{{$val->id}}'>
                            <td>{{$val->table_number}}</td>
                            <td>{{$val->seating}}</td>
                            <td>{{$val->priority}}</td>
                            <td>{{$val->duration}}</td>
                          
                            <td>
                                <a href="{{ url("admin/tables/edit",$val->id) }}" class="btn btn-warning btn-sm">wijzigen</a>
                                <a href="{{ url("admin/tables/show",$val->id) }}" class="btn btn-info btn-sm">tonen</a>
                                <a onclick="return confirm('Do you really want to delete this record?');" href="{{ url("admin/tables/destroy",$val->id) }}" class="btn btn-danger btn-sm">verwijderen</a>
                            </td>
                        </tr>
                        @endforeach
				<?php } ?>
                    </tbody>
                </table>
            </form>
			<?php if(Sentinel::getUser()->roles[0]->id == 1) { ?>
            {{ $model->appends(request()->input())->links() }}
			<?php }?>
        </div>
    </div>
</div>
	
@stop