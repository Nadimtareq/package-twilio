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
Geachte heer/mevrouw, <br /><br />

Uit onze administratie blijkt dat er geen betaling plaats heeft kunnen voor de volgende factuur: {{ $invoice->invoicenumber }}<br /><br />
Wij verzoeken u om uw betaling te voldoen door middel van iDeal. Hiervoor kunt u onderstande link gebruiken:<br /><br />

<a href="{{ url('/payments/pay-invoice/'.$oInvoice->hash) }}">{{ url('/payments/pay-invoice/'.$oInvoice->hash) }}</a><br /><br />

<strong>Met vriendelijke groet,</strong><br />
UWvoordeelpas.nl
@endsection

@section('footer')
&copy; {{ date('Y') }} UWvoordeelpas B.V.
@endsection