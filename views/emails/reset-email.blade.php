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

U heeft uw e-mailadres gewijzigd op Uwvoordeelpas.nl. U hoeft alleen nog op de activatielink hieronder te klikken ter verificatie van uw nieuwe e-mailadres.<br /><br />

<a href="<?php echo URL::to('account/activate-email/'.$code); ?>">
	<?php echo URL::to('account/activate-email/'.$code); ?>
</a><br /><br />

<strong>Met vriendelijke groet,</strong><br />
UWvoordeelpas.nl
@endsection

@section('footer')
&copy; {{ date('Y') }} UWvoordeelpas B.V.
@endsection