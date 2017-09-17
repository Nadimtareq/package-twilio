@extends('emails.template.template')

@section('logo')
<a href="{{ url('/') }}" target="_blank">
	<img align="left" 
		 alt="Uw Voordeelpas" 
		 class="mcnImage" 
		 src="{{ url('images/vplogo.png') }}" 
		 style="max-width: 133px;padding-bottom: 0;display: inline !important;vertical-align: bottom;border: 0;height: auto;outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;" width="190">
</a>
@endsection

Beste User,<br /><br />
{{--Op {{ date('d-m-Y H:i:s') }} heeft {{ $request->input('name') }} met IP adres: <strong>{{ Request::getClientIp() }}</strong> een bericht gestuurd via het contactformulier op Uwvoordeelpas.nl.<br /><br />--}}
{{--{{}}--}}
<h5>Bericht</h5>
{{ $body }}<br /><br />