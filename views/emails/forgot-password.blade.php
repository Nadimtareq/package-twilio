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
Beste <?php echo $user->name; ?>,<br /><br />

U heeft aangegeven dat u het wachtwoord van uw account op UWvoordeelpas.nl bent vergeten.<br /><br />
U kunt een nieuw wachtwoord instellen door op de onderstaande link te klikken.<br /><br />

<a href="<?php echo URL::to('activate-password/'.$code); ?>"><?php echo URL::to('activate-password/'.$code); ?></a><br /><br />

Heeft u niet zelf aangegeven dat u uw wachtwoord wilt wijzigen? Dan kunt u deze e-mail negeren.<br /><br />

<strong>Met vriendelijke groet,</strong><br />
UWvoordeelpas.nl
@endsection

@section('footer')
&copy; {{ date('Y') }} UWvoordeelpas B.V.
@endsection