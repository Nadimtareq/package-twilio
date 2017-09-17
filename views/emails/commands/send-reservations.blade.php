@extends('emails.template.template')

@section('logo')
<a href="{{ url('/') }}" target="_blank">
	<img align="left" 
		 alt="Uw Voordeelpas" 
		 class="mcnImage" 
		 src="{{ public_path('images/vplogo.png') }}" 
		 style="max-width: 133px;padding-bottom: 0;display: inline !important;vertical-align: bottom;border: 0;height: auto;outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;" width="133">
</a>
@endsection

@section('content')
Beste admin,<br /><br />

Hierbij een overzicht van de reserveringen van {{ $name }}  op {{ date('d-m-Y', strtotime($date)) }}<br /<br />

---<br />
<strong>* Dit is een automatische e-mail van UWvoordeelpas.nl *</strong>
@endsection

@section('footer')
&copy; {{ date('Y') }} UWvoordeelpas B.V.
@endsection