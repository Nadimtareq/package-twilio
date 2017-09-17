@extends('template.theme')

@section('content')
<div class="content">
    @include('admin.template.breadcrumb')

    @if($userAdmin)
	<div class="ui normal icon search selection fluid dropdown">
       	<div class="text">Filter op bedrijf</div>
        <i class="dropdown icon"></i>

		<div class="menu">
            @foreach($companies as $data)
            <a class="item" href="{{ url('admin/widgets/'.$data->slug) }}">{{ $data->name }}</a>
            @endforeach
       </div>
    </div>
    @endif
	<div class="row">
		<div class="col-md-6 col-lg-6 ">
		<h3>Reserveer kalender</h3>
    <div class="ui form">
	     <script type="text/javascript">
        $(document).ready(function() {
            var clipboard = new Clipboard('#clipboard12');
            clipboard.on('success', function(e) {
                e.clearSelection();
                swal({
                    title: "link gekopieerd",
                    text: "Uw link is succesvol gekopieerd.",
                    type: "success",
                    confirmButtonColor: "#6498eb",
                    closeOnConfirm: true
                });
            });
        });
		$(document).ready(function() {
            var clipboard = new Clipboard('#clipboard123');
            clipboard.on('success', function(e) {
                e.clearSelection();
                swal({
                    title: "link gekopieerd",
                    text: "Uw link is succesvol gekopieerd.",
                    type: "success",
                    confirmButtonColor: "#6498eb",
                    closeOnConfirm: true
                });
            });
        });
    </script>
		    <div class="field">
				
			  	<textarea id="bk_cal"><iframe src="{{ url('widget/calendar/restaurant/'.$company->slug) }}" width="500" height="550" frameborder="0"></iframe></textarea><br />
			  	<!--Change By Team AIT Date -28/07/2017 -->
			  	<a href="javascript:;" class="ui green button mini" data-clipboard-target="#bk_cal" id="clipboard12">
                    <i class="clipboard icon"></i>
                    kopieer link
                </a>
				<table class="ui table">
		    		<tr>
			    		<td><strong>Width:</strong></td>
			    		<td>Bepaal de breedte van de widget</td>
			    	</tr>
			    	<tr>
			    		<td><strong>Height:</strong></td>
			    		<td>Bepaal de hoogte van de widget</td>
			    	</td>
			    	<tr>
			    		<td><strong>Frameborder:</strong></td>
			    		<td>1 = Rand weergeven om de widget, 0 = Geen rand weergeven om de widget</td>
					</tr>
				</table>



               </div>
		    <div class="field">
		    	<h5>Voorbeeld</h5>
			  	<!--Change By Team AIT Date -28/07/2017 -->
				<iframe src="{{ url('widget/calendar/restaurant/'.$company->slug) }}"
						width="100%"
						height="650"
						frameborder="0">
				</iframe>
			</div>
		
	</div>
		</div>
		<div class="col-md-6 col-lg-6 ">
		<h3>Reserveer kalender 2</h3>
	<div class="ui form">
		
			<div class="field">
				<textarea id="bk_cal2"><iframe src="{{ url('widget/calendar2/restaurant/'.$company->slug) }}" width="500" height="550" frameborder="0"></iframe></textarea><br />
				<!--Change By Team AIT Date -28/07/2017 -->
				<a href="javascript:;" class="ui green button mini" data-clipboard-target="#bk_cal2" id="clipboard123">
                    <i class="clipboard icon"></i>
                    kopieer link
                </a>
				<table class="ui table">
					<tr>
						<td><strong>Width:</strong></td>
						<td>Bepaal de breedte van de widget</td>
					</tr>
					<tr>
						<td><strong>Height:</strong></td>
						<td>Bepaal de hoogte van de widget</td>
						</td>
					<tr>
						<td><strong>Frameborder:</strong></td>
						<td>1 = Rand weergeven om de widget, 0 = Geen rand weergeven om de widget</td>
					</tr>
				</table>
				




			</div>
			<div class="field">
				<h5>Voorbeeld</h5>
				<!--Change By Team AIT Date -28/07/2017 -->
				<iframe src="{{ url('widget/calendar2/restaurant/'.$company->slug) }}"
						width="100%"
						height="650"
						frameborder="0">
				</iframe>
			</div>
		
	</div>
		</div>
	</div>
    





	
</div>
<div class="clear"></div>
@stop
