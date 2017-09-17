@extends('template.theme')

@section('slider')
<br>
@stop

@section('scripts')
<script type="text/javascript">
	var redirectTo = '';
	swal({
		  title: "Beste " + $('input[name="nameUser"]').val(),
		  text: "<span style='text-align: center; display: block;'>U bent bijna klaar om te gaan sparen! <br><br> Om te kunnen sparen moet u akkoord gaan met de <a href='" + baseUrl+ "algemene-voorwaarden'' target='_blank'>Algemene Voorwaarden</a>.</span>",
		  type: "info",
		  html: true,
		  showCancelButton: true,
		  confirmButtonColor: "#DD6B55",
		  cancelButtonText: "Annuleren",
		  confirmButtonText: "Ik ga akkoord",
		  closeOnConfirm: false
	},
	function(){
		Redirect(currentUrl + '?confirm=1');  
	});
</script>
@stop

<input type="hidden" name="redirectTo" value="{{ Request::has('redirect_to') ? Request::input('redirect_to') : '' }}">
<input type="hidden" name="nameUser" value="{{ $name }}">