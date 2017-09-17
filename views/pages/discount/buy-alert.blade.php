@extends('template.theme')

@section('slider')
<br>
@stop

{{--*/ $pageTitle = 'Voordeelpas' /*--}}

@section('scripts')
<script type="text/javascript">
	var redirectTo = $('input[name="redirectTo"]').val() != '' ? '&redirect_to=' + $('input[name="redirectTo"]').val() : '';
	swal({
		  title: "Weet u het zeker?",
		  text: "Voor slechts €14,95 bent in het bezit van een UWvoordeelpas waarmee u 12 maanden lang tot wel 25% korting krijgt bij veel aangesloten restaurants!",
		  type: "warning",
		  showCancelButton: true,
		  confirmButtonColor: "#DD6B55",
		  cancelButtonText: "Annuleren",
		  confirmButtonText: "Kopen",
		  closeOnConfirm: false
	},
	function(){
		Redirect(baseUrl + 'voordeelpas/buy/direct?confirm=1' + redirectTo);  
	});
</script>
@stop

<input type="hidden" name="redirectTo" value="{{ Request::has('redirect_to') ? Request::input('redirect_to') : '' }}">