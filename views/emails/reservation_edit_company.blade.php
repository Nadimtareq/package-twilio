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
Beste <?php echo $data->companyName; ?>,<br /><br />

<p>Onlangs heeft gebruiker <?php echo $data->name; ?> bij bedrijf <strong><?php echo $data->companyName; ?></strong> een reservering van <?php echo $reservationDate->formatLocalized('%d %B %Y'); ?>  om {{ date('H:i', strtotime($data->time)) }} gewijzigd.</p>
<p>Klik <a href="<?php echo URL::to('reservation/edit/'.$data->id); ?>">hier</a> om uw reservering te wijzigen</p>

<strong>Met vriendelijke groet,</strong><br />
UWvoordeelpas.nl
@endsection

@section('footer')
&copy; {{ date('Y') }} UWvoordeelpas B.V.
@endsection