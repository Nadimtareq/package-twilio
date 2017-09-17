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

@section('content')
Beste <?php echo $data->name; ?>,<br /><br />

Bedankt voor uw aanmelding bij Uwvoordeelpas.nl. 
Vanaf nu kunt u profiteren van de voordelen van een Uwvoordeelpas-account.<br /><br />

@if (isset($randomPassword))
Met het onderstaande wachtwoord kunt u inloggen:<br /><br />

<strong>Wachtwoord:</strong> {{ $randomPassword }}<br /><br />
@endif

Met vriendelijke groet,<br />
Uwvoordeelpas.nl
@endsection

@section('footer')
&copy; {{ date('Y') }} UWvoordeelpas B.V.
@endsection