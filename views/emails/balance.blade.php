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

Bedankt voor het ophogen van uw spaartegoed. U kunt dit gebruiken voor het kopen van een voordeelpas of gebruiken bij het reserveren voor een restaurant.<br /><br />

<strong>Met vriendelijke groet,</strong><br />
UWvoordeelpas.nl
@endsection

@section('footer')
&copy; {{ date('Y') }} UWvoordeelpas B.V.
@endsection