@extends('emails.template.template')
@inject('calendarHelper', 'App\Helpers\CalendarHelper')

@section('logo')
@if (isset($info))
<a href="{{ url('restaurant/'.$info['slug'].$extraParamaters) }}" target="_blank">
    @if(trim($logo) != '')
    <img align="left" 
         alt="{{ $info['name'] }}" 
         class="mcnImage" 
         src="{{ url('public'.$logo) }}" 
         style="width: 280px; padding-bottom: 0;display: inline !important;vertical-align: bottom;border: 0;height: auto;outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;" width="200">
    @else 
    <img align="left" 
         alt="{{ $info['name'] }}" 
         class="mcnImage" 
         src="{{ url('images/vplogo.png') }}" 
         style="width: 280px; padding-bottom: 0;display: inline !important;vertical-align: bottom;border: 0;height: auto;outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;" width="200">
    @endif
</a>
@else
<a href="{{ url('/'.$extraParamaters) }}" target="_blank">
    <img align="left" 
         alt="UWvoordeelpas" 
         class="mcnImage" 
         src="https://www.uwvoordeelpas.nl/images/vplogo.png" 
         style="width: 280px; padding-bottom: 0;display: inline !important;vertical-align: bottom;border: 0;height: auto;outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;" width="200">
</a>
@endif
@endsection

@section('content')
{!! $template !!}

@if ($templateId == 'new-reservation-client')
<strong>Toevoegen aan agenda</strong><br/>

{!! $calendarHelper->displayCalendars(0, 'Reservering bij '.$info['name'], 'Reservering voor '.$info['name'].' op '.date('d-m-Y', strtotime($reservation->date)).' om '.date('H:i', strtotime($reservation->time)).' met '.$reservation->persons.' '.($reservation->persons == 1 ? 'persoon' : 'personen'), ($info['address'].', '.$info['zipcode'].', '.$info['city']), date('Y-m-d', strtotime($reservation->date)).' '.date('H:i:s', strtotime($reservation->time))) !!}
@endif
@endsection

@section('maps')
@if ($templateId != 'pay-invoice-company' && isset($info))
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
@endif
@endsection

@section('buttons')

@if ($templateId == 'reminder-review-client' OR $templateId == 'reminder-reservation-client')
<tr>
    <td colspan="2" style="text-align: center; padding-left: 10px; padding-right: 10px;">
        <table border="0" cellpadding="0" cellspacing="0" width="100%" class="emailButton" style="border-radius:3px; background-color:#6DC6DD;">
            <tr>
                <td align="center" valign="middle" class="emailButtonContent" style="padding-top:15px; padding-right:30px; padding-bottom:15px; padding-left:30px;">
                    <a href="{{ $authLinkLike }}" target="_blank" style="color:#FFFFFF; font-family:Helvetica, Arial, sans-serif; font-size:16px; font-weight:bold; text-decoration:none;">
                        Like Ons
                    </a>
                </td>
            </tr>
        </table>
    </td>
    <td colspan="2" style="text-align: center; padding-left: 10px; padding-right: 10px;">
        <table border="0" cellpadding="0" cellspacing="0" width="100%" class="emailButton" style="border-radius:3px; background-color: rgb(251, 189, 8);">
            <tr>
                <td align="center" valign="middle" class="emailButtonContent" style="padding-top:15px; padding-right:30px; padding-bottom:15px; padding-left:30px;">
                    <a href="{{ $authLinkReview }}" target="_blank" style="color:#FFFFFF; font-family:Helvetica, Arial, sans-serif; font-size:16px; font-weight:bold; text-decoration:none;">Schrijf een recensie</a>
                </td>
            </tr>
        </table>
    </td>
</tr>

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
@elseif ($templateId == 'new-reservation-company')
@if ($manual == 1)
<tr>
    <td id="templateColumns" valign="top">
        <!--[if gte mso 9]>
                        <table align="center" border="0" cellspacing="0" cellpadding="0" width="600" style="width:600px;">
                        <tr>
                        <td align="center" valign="top" width="300" style="width:300px;">
                <![endif]-->

        <table align="left" border="0" cellpadding="0" cellspacing="0" class="columnWrapper" width="300">
            <tbody>
                <tr>
                    <td class="columnContainer" valign="top">
                        <table border="0" cellpadding="0" cellspacing="0" class="mcnButtonBlock" style="min-width:100%;" width="100%">
                            <tbody class="mcnButtonBlockOuter">
                                <tr>
                                    <td align="center" class="mcnButtonBlockInner" style="padding-top:0; padding-right:18px; padding-bottom:18px; padding-left:18px;" valign="top">
                                        <table border="0" cellpadding="0" cellspacing="0" class="mcnButtonContentContainer" style="border-collapse: separate !important;border-radius: 3px;background-color: #2BAADF;" width="100%">
                                            <tbody>
                                                <tr>
                                                    <td align="center" class="mcnButtonContent" style="font-family: Arial,Helvetica, Neue,Helvetica, sans-serif; font-size: 16px; padding: 15px;" valign="middle">
                                                        <a class="mcnButton" href="{{ url('admin/reservations/edit-status/'.$reservationId.'?status=reserved-pending') }}" style="font-weight: bold;letter-spacing: normal;line-height: 100%;text-align: center;text-decoration: none;color: #FFFFFF;" target="_blank" title="Wijzigen">Accepteren</a>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
        <!--[if gte mso 9]>
                                                                                                                                                    </td>
                <td align="center" valign="top" width="300" style="width:300px;">
                                                                                                                                                    <![endif]-->

        <table align="left" border="0" cellpadding="0" cellspacing="0" class="columnWrapper" width="300">
            <tbody>
                <tr>
                    <td class="columnContainer" valign="top">
                        <table border="0" cellpadding="0" cellspacing="0" class="mcnButtonBlock" style="min-width:100%;" width="100%">
                            <tbody class="mcnButtonBlockOuter">
                                <tr>
                                    <td align="left" class="mcnButtonBlockInner" style="padding-top:0; padding-right:18px; padding-bottom:18px; padding-left:18px;" valign="top">
                                        <table border="0" cellpadding="0" cellspacing="0" class="mcnButtonContentContainer" style="border-collapse: separate !important;border-radius: 3px;background-color: #77B8C9;" width="100%">
                                            <tbody>
                                                <tr>
                                                    <td align="center" class="mcnButtonContent" style="font-family: Arial; font-size: 16px; padding: 15px;" valign="middle">
                                                        <a class="mcnButton" href="{{ url('admin/reservations/edit-status/'.$reservationId.'?status=refused') }}" style="font-weight: bold;letter-spacing: normal;line-height: 100%;text-align: center;text-decoration: none;color: #FFFFFF;" target="_blank" title="Annuleren">Weigeren</a>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
        <!--[if gte mso 9]>
                        </td>
                                                                                                                                                    </tr>
                                                                                                                                                    </table>
                                                                                                                                                    <![endif]-->
    </td>
</tr>
@endif
@else
<tr>
    <td id="templateColumns" valign="top">
        <!--[if gte mso 9]>
                        <table align="center" border="0" cellspacing="0" cellpadding="0" width="600" style="width:600px;">
                        <tr>
                        <td align="center" valign="top" width="300" style="width:300px;">
                        <![endif]-->

        <table align="left" border="0" cellpadding="0" cellspacing="0" class="columnWrapper" width="300">
            <tbody>
                <tr>
                    <td class="columnContainer" valign="top">
                        <table border="0" cellpadding="0" cellspacing="0" class="mcnButtonBlock" style="min-width:100%;" width="100%">
                            <tbody class="mcnButtonBlockOuter">
                                <tr>
                                    <td align="center" class="mcnButtonBlockInner" style="padding-top:0; padding-right:18px; padding-bottom:18px; padding-left:18px;" valign="top">
                                        <table border="0" cellpadding="0" cellspacing="0" class="mcnButtonContentContainer" style="border-collapse: separate !important;border-radius: 3px;background-color: #2BAADF;" width="100%">
                                            <tbody>
                                                <tr>
                                                    <td align="center" class="mcnButtonContent" style="font-family: Arial,Helvetica Neue, Helvetica, sans-serif; font-size: 16px; padding: 15px;" valign="middle">
                                                        <a class="mcnButton" href="{{ url('auth/set/'.$authLinkEdit) }}" style="font-weight: bold;letter-spacing: normal;line-height: 100%;text-align: center;text-decoration: none;color: #FFFFFF;" target="_blank" title="Inloggen">Inloggen</a>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
        <!--[if gte mso 9]>
                                                                                                                                                    </td>
                                                                                                                                                    <td align="center" valign="top" width="300" style="width:300px;">
                                                                                                                                                    <![endif]-->

        <table align="left" border="0" cellpadding="0" cellspacing="0" class="columnWrapper" width="300">
            <tbody>
                <tr>
                    <td class="columnContainer" valign="top">
                        <table border="0" cellpadding="0" cellspacing="0" class="mcnButtonBlock" style="min-width:100%;" width="100%">
                            <tbody class="mcnButtonBlockOuter">
                                <tr>
                                    <td align="left" class="mcnButtonBlockInner" style="padding-top:0; padding-right:18px; padding-bottom:18px; padding-left:18px;" valign="top">
                                        <table border="0" cellpadding="0" cellspacing="0" class="mcnButtonContentContainer" style="border-collapse: separate !important;border-radius: 3px;background-color: #77B8C9;" width="100%">
                                            <tbody>
                                                <tr>
                                                    <td align="center" class="mcnButtonContent" style="font-family: Arial; font-size: 16px; padding: 15px;" valign="middle">
                                                        <a class="mcnButton" href="{{ url('auth/set/'.$authLinkCancel) }}" style="font-weight: bold;letter-spacing: normal;line-height: 100%;text-align: center;text-decoration: none;color: #FFFFFF;" target="_blank" title="Tegoed Sparen">{{ $templateId == 'pay-invoice-company' ? 'Factuur betalen' : 'Tegoed sparen' }}</a>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
        <!--[if gte mso 9]>
                        </td>
                                                                                                                                                    </tr>
                                                                                                                                                    </table>
                                                                                                                                                    <![endif]-->
    </td>
</tr>
@endif
@endsection

@section('footer')
@if ($templateId != 'pay-invoice-company' && isset($info))
<a href="{{ url('restaurant/'.$info['slug'].$extraParamaters) }}" target="_blank" title="{{ $info['name'] }}">{{ $info['name'] }}</a> 
&nbsp;|&nbsp; 
<a href="{{ url('restaurant/'.$info['slug'].$extraParamaters) }}" target="_blank" title="{{ $info['address'] }} {{ $info['zipcode'] }} {{ $info['city'] }}">
    {{ $info['address'] }} {{ $info['zipcode'] }} {{ $info['city'] }}
</a> &nbsp;|&nbsp; 
<a href="{{ url('restaurant/'.$info['slug'].$extraParamaters) }}" target="_blank" title="{{ $info['phone'] }}">
    {{ $info['phone'] }}
</a><br><br />

U heeft deze e-mail ontvangen omdat u via ons reserveringssysteem van UWvoordeelpas.nl gereserveerd hebt. U kunt zich hier niet voor uitschrijven.<br><br>
U heeft een reservering gemaakt met:&nbsp; <a href="mailto:{{ $sendEmail }}" target="_blank" title="{{ $sendEmail }}">{{ $sendEmail }}</a>
@else
<a href="{{ url('/'.$extraParamaters) }}" target="_blank" title="UWvoordeelpas B.V.">
    UWvoordeelpas B.V.
</a> 
&nbsp;|&nbsp; 

<a href="{{ url('/'.$extraParamaters) }}" target="_blank" title="Broenshofweide 11, 5709 SE Helmond">
    Broenshofweide 11, 5709 SE Helmond
</a> &nbsp;|&nbsp; 

<a href="{{ url('/'.$extraParamaters) }}" target="_blank" title="+31 (0)492-778613">
    +31 (0)492-778613
</a>
@endif
@endsection