@if(isset($userAuth->lang) && !empty($userAuth->lang))
    {{-- */$lang=$userAuth->lang;/* --}}
@elseif(Session::has('language'))
    {{-- */$lang=Session::get('language');/* --}}
@else
    {{-- */$lang='nl';/* --}}
@endif
<a href="#" data-activates="slide-out" class="button-collapse2"><i class="material-icons notranslate material-icons2">menu</i></a>

<ul id="slide-out" class="side-nav2 right-aligned" style="overflow:auto" >
    <li><a href="javascript:void(0);" id="closeMenu"><i class="material-icons notranslate">close</i> Sluiten</a></li>
    <li><a href="{{ url('news') }}"><i class="material-icons notranslate">assignment</i> Nieuws</a></li>
    <li><a href="{{ url('tegoed-sparen') }}"><i class="material-icons notranslate">monetization_on</i> Tegoed sparen</a></li>
    <li><a href="{{ url('voordeelpas/buy') }}"><i class="material-icons notranslate">credit_card</i> Voordeelpas</a></li>
     @if($userCompany OR $userWaiter)
        <li><a href="{{ url('faq/3/restaurateurs') }}"><i class="material-icons notranslate">help</i> Veelgestelde vragen</a></li>
    @else
        <li><a href="{{ url('faq/2/restaurateurs') }}"><i class="material-icons notranslate">help</i> Veelgestelde vragen</a></li>
    @endif
    <li><a href="#" class="item search-full-open"><i class="material-icons notranslate">search</i> Zoeken</a></li>
    <li><a href="{{ url('account/giftcards') }}"><i class="material-icons notranslate">assignment</i> Koop een cadeaubon</a></li>
    @if($userAuth)
        @if( $userCompany != 1 && $userWaiter != 1 )
        <li class="fixed-row">
           <a class="active">
              {{ ($userInfo->name != '' ? strtoupper($userInfo->name) : 'Gebruiker') }}
            </a>
        </li>
        <li class="fixed-row">
           <a href="{{URL::to('account')}}">
              {{trans('app.profile')}}-{{trans('app.status')}}:&nbsp;&nbsp;{{Account::getProfileStatus()}}
            </a>
        </li>
        <li>
            <a href="{{ url('account/reservations/saldo') }}" class="item">
                <i class="material-icons notranslate">euro_symbol</i><strong>Spaartegoed:</strong> {{$userInfo->saldo }}
            </a>
        </li>
        <li><a href="{{ url('payment/charge') }}" ><i class="material-icons notranslate">euro_symbol</i> Saldo opwaarderen</a></li>
        <li><a href="{{ url('account') }}" ><i class="material-icons notranslate">code</i> Mijn gegevens</a></li>
        <li><a href="{{ url('account/reviews') }}" ><i class="material-icons notranslate">thumb_up</i> Mijn recensies</a></li>
        <li><a href="{{ url('account/reservations') }}" ><i class="material-icons notranslate">local_dining</i> Mijn reserveringen</a></li>
        <li><a href="{{ url('account/future-deals') }}" ><i class="material-icons notranslate">reorder</i> Mijn vouchers</a></li>
        <li><a href="{{ url('?extension_download_btn=1') }}"><i class="material-icons notranslate">star</i>Installeer spaarhulp</a></li>
        <li><a href="{{ url('reference_code') }}"><i class="material-icons notranslate">euro_symbol</i>Geld verdienen</a></li>
        <li><a href="{{ url('account/barcodes') }}" ><i class="material-icons notranslate">reorder</i> Mijn voordeelpas</a></li>
        <li><a href="{{ url('account/favorite/companies') }}" ><i class="material-icons notranslate">favorite_border</i> Mijn favoriete restaurants</a></li>
        <li><a href="{{ url('logout') }}" ><i class="material-icons notranslate">touch_app</i> Uitloggen</a></li>
        @endif

        @inject('companyReservation', 'App\Models\CompanyReservation')
        @include('template/navigation/company')
        @include('template/navigation/callcenter')
        @include('template/navigation/admin')
        <li class="divider"> </li>
        @else
        <li><a id="registerButton2" class="register button item" href="#" ><i class="material-icons notranslate">vpn_key</i> Aanmelden</a></li>
        <li><a class="login button" data-type="login" href="#"><i class="material-icons notranslate">exit_to_app</i> Inloggen</a></li>
        <li><a href="{{ url('hoe-werkt-het') }}"><i class="material-icons notranslate">description</i> Hoe werkt het?</a></li>
        <li><a href="{{ url('algemene-voorwaarden') }}"><i class="material-icons notranslate">book</i> Voorwaarden</a></li>
    @endif

    <li class="sidebar-dropdown notranslate">
        <a href="javascript:void(0)" class="dropbtn">
            <img src="{{ asset('images/flags/') }}/{{ $lang }}.png" alt="flag"> {{ strtoupper($lang) }} <i class="fa fa-angle-down" aria-hidden="true"></i>
        </a>
        <div class="sidebar-dropdown-content">
            <a href="{{ url('setlang/nl?redirect='.Request::url()) }}" data-value="nl" class="item {{ isset($userInfo->id) ? 'change-login-user-language' : '' }}"><i class="nl flag"></i> NL</a>
            <a href="{{ url('setlang/en?redirect='.Request::url()) }}" data-value="en" class="item {{ isset($userInfo->id) ? 'change-login-user-language' : '' }}"><i class="gb flag"></i> EN</a>
            <a href="{{ url('setlang/be?redirect='.Request::url()) }}" data-value="be" class="item {{ isset($userInfo->id) ? 'change-login-user-language' : '' }}"><i class="be flag"></i> BE</a>
            <a href="{{ url('setlang/de?redirect='.Request::url()) }}" data-value="de" class="item {{ isset($userInfo->id) ? 'change-login-user-language' : '' }}"><i class="de flag"></i> DE</a>
            <a href="{{ url('setlang/fr?redirect='.Request::url()) }}" data-value="fr" class="item {{ isset($userInfo->id) ? 'change-login-user-language' : '' }}"><i class="fr flag"></i> FR</a>
        </div>
        <br/>
        <br/>
        <br/>
        <br/>
        <br/>
        <br/>
        <br/>
        <br/>
        <br/>
    </li>

</ul>

