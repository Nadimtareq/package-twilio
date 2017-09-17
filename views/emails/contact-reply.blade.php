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

	Hallo {{ $contact->name }}, <br>
	Je hebt een nieuw antwoord op je feedback bericht: <br>

	<div style="text-align: center; padding: 0 8%; color: #585858;font-size: 15px;">"{{ $contact->content }}"</div>
	<br>
	<br>
	<div style="background: #eeeeee; border: solid 1px #DDDDDD;padding: 5%;font-size: 17px;">
	{{ $reply }} <br>

		<a href="{{ $conversation_link }}" style="background: #30309a;color: white;padding: 7px 10px;text-decoration: none;border: solid 2px #556fff;font-size: 14px;line-height: 50px;">
			antwoord
		</a>
	</div>
	<br>
	Om te antwoorden klik hier:

	<a href="{{ $conversation_link }}">{{ $conversation_link }}</a>
	<br>
	<br>
<strong>Met vriendelijke groet,</strong><br />
UWvoordeelpas.nl
@endsection

@section('footer')
&copy; {{ date('Y') }} UWvoordeelpas B.V.
@endsection