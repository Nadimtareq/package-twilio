@extends('emails.template.newsletter')

@section('logo')

@endsection

@section('content')
	{!! $template !!}
@endsection

@section('buttons')
	<tr>
	    <td style="text-align: center; padding-left:10px;">
	    	<img src="https://cdn1.iconfinder.com/data/icons/marketing-outlined/60/shop-trolly-cart-store-128.png" width="120px" /><br />
	        <h4 style="text-align: center; font-size: 12px; font-family: arial;">1. Shopt u ook online?</h4><br /><br />
	    </td>
	    <td style="text-align: center;">
	    	<img src="https://cdn1.iconfinder.com/data/icons/marketing-outlined/60/euro-paper-money-cash-128.png" width="120px" /><br />
	        <h4 style="text-align: center; font-size: 12px; font-family: arial;">2. Spaar bij 1500+ Webshops!</h4><br /><br />
	    </td>
	    <td style="text-align: center;">
	    	<img src="https://cdn1.iconfinder.com/data/icons/marketing-outlined/60/calendar-agenda-days-time-mark-128.png" width="120px" /><br />
	        <h4 style="text-align: center; font-size: 12px; font-family: arial;">3. Reserveer met uw spaartegoed!</h4><br /><br />
	    </td>
	    <td style="text-align: center;">
	    	<img src="https://cdn1.iconfinder.com/data/icons/marketing-outlined/60/wallet-money-card-id-purse-pouch-128.png"width="120px" /><br />
	        <h4 style="text-align: center; font-size: 12px; font-family: arial;">4. Geniet van uw spaartegoed!</h4><br /><br />
	    </td>
	</tr>
@endsection

@section('maps')
	<table border="0" cellpadding="0" cellspacing="0" class="mcnCodeBlock" style="border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;" width="100%">
		<tbody class="mcnTextBlockOuter">
			<tr>
				<td class="mcnTextBlockInner" style="mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;" valign="top">
					<a href="https://www.google.com/maps/dir//{{ $info['address'] }}+{{ $info['zipcode'] }}+{{ $info['city'] }}/">
						<img alt="Google Map van {{ $info['address'] }}" src="http://maps.googleapis.com/maps/api/staticmap?center={{ $info['address'] }}+{{ $info['zipcode'] }}+{{ $info['city'] }}&amp;zoom=13&amp;scale=false&amp;size=600x300&amp;maptype=roadmap&amp;format=png&amp;visual_refresh=true&amp;markers=size:small%7Ccolor:0x0080ff%7Clabel:1%7C{{ $info['address'] }}+{{ $info['zipcode'] }}+{{ $info['city'] }}" style="border: 0;height: auto;outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;" width="600">
					</a>
				</td>
			</tr>
		</tbody>
	</table>
@endsection

@section('footer')
	<div style="text-align: center">
	@if (isset($info['slug']))
		<a href="{{ url('restaurant/'.$info['slug'].'?utm_source=newsletter&utm_campaign=restaurant&utm_medium=email&utm_content='.$info['slug']) }}" target="_blank" title="{{ $info['name'] }}">{{ $info['name'] }}</a> 
		&nbsp;|&nbsp; 
		<a href="{{ url('restaurant/'.$info['slug'].'?utm_source=newsletter&utm_campaign=restaurant&utm_medium=email&utm_content='.$info['slug']) }}" target="_blank" title="{{ $info['address'] }} {{ $info['zipcode'] }} {{ $info['city'] }}">
			{{ $info['address'] }} {{ $info['zipcode'] }} {{ $info['city'] }}
		</a> &nbsp;|&nbsp; 
		<a href="{{ url('restaurant/'.$info['slug'].'?utm_source=newsletter&utm_campaign=restaurant&utm_medium=email&utm_content='.$info['slug'])  }}" target="_blank" title="{{ $info['phone'] }}">
		{{ $info['phone'] }}
		</a><br><br />

		Geen nieuwsbrief meer ontvangen?  Klik hier om u af te melden<br><br>

	@endif
	</div>
@endsection