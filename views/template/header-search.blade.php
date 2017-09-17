@if(isset($userAuth->lang) && !empty($userAuth->lang))
	 {{-- */$lang=$userAuth->lang;/* --}}
 @elseif(Session::has('language'))
	 {{-- */$lang=Session::get('language');/* --}}
 @else
	 {{-- */$lang='nl';/* --}}
 @endif
<header id="navigation" class="root-sec white nav header">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="nav-inner">
                    <nav class="primary-nav">
                        <div class="clearfix nav-wrapper">
                        @include('template.sidemenu')
                        <!-- <a href="{{ url('/')}}" class="left brand-logo menu-smooth-scroll" data-section="#home">
									   <img src="{{asset('images/logo.png')}}" alt="">
									</a>
									
									 <div class="mobile-profile pp-container">
										<a href="{{ url('/')}}">
											<img src="{{ asset('images/logo.png') }}" alt="">
										 </a>
									 </div> -->
                            <a href="#" data-activates="mobile-top" class="button-collapse"> <i
                                        class="material-icons notranslate material-icons2">menu</i></a>
                            <div class="brand-logo">
                                @if($userAuth)
                                    <a href="{{ url('/home') }}">
                                @else
                                    <a href="{{ url('/') }}">
                                @endif
                                <img src="{{ asset('images/logo.png') }}" alt="" class="responsive-img">
                                </a>
                            </div>

                            <ul class="right side-nav" id="mobile-top"> <!-- center-menu- inline-menu -->
                                <form action="<?php echo url('search'); ?>" method="GET" class="form">
                                    <li>
                                        <div class="input-field">
                                            <div id="usersCompaniesSearch2" class="search form focus">
                                                <label class="label-icon" for="search" style="color:#ffffff;"><i
                                                            class="material-icons notranslate">pin_drop</i></label>
                                                <input id="search" name="q" type="search"
                                                       value="{{ Request::segment(1) == 'search' ? Request::get('q') : '' }}"
                                                       placeholder="{{ trans('app.keyword') }}" class="prompt"
                                                       autocomplete="off">
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <label for="datepicker" style="color:#ffffff;">
                                        <!--<img src="{{asset('images/m2.png') }}" alt="m2">-->
                                            <i class="material-icons notranslate">date_range</i>
                                            <input id="datepicker" placeholder="Datum" name="date"
                                                   class="datepicker1 quantity" data-filter-todate="yes"
                                                   data-time="#sltime"
                                                   type="text" {{ (Request::has('date')) ?  'value='.Request::has('date') : '' }}>
                                        </label>
                                    </li>
                                    <li>
                                        <!--<img src="images/m3.png" alt="m3">-->
                                        <i class="material-icons notranslate">watch_later</i>
                                        <select id="sltime" name="sltime" class="quantity option-white-bg">
                                            @php
                                                // Check time
                                                if (Request::segment(1) == 'search' && Request::has('sltime'))
                                                    $current_time=date('H:i', strtotime(Request::get('sltime')));
                                                else
                                                    $current_time = (isset($disabled[0])) ? $disabled[0] : '';

                                                $datetime = new DateTime();
                                            @endphp


                                            @foreach ($getTimes as $time)
                                                @php
                                                    $timed = date_create_from_format('H:i',$time);
                                                @endphp
                                                @if ($time >= '00:00' && $time >= '08:00' && $timed->getTimestamp() >= $datetime->getTimestamp())
                                                    <option value="{{ $time }}" data-value="{{ $time }}"
                                                            data-dd="0">{{ $time }}</option>
                                                @else
                                                    <option value="{{ $time }}" data-value="{{ $time }}" data-dd="0"
                                                            style="display:none">{{ $time }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </li>
                                    <li>
                                        <!--<img src="images/m4.png" alt="m4">-->
                                        <i class="material-icons notranslate">person</i>
                                        @php
                                            $current_p = ((Request::get('persons') != '') ? Request::get('persons') : (($userAuth && $userInfo->kids != 'null' && $userInfo->kids != NULL && $userInfo->kids != '[""]') ? $userInfo->kids : 2))
                                        @endphp

                                        <select name="persons" class="quantity quantity-expand option-white-bg">
                                            <!-- <option value="0" disabled="disabled">Pers</option> -->
                                            @for ($i = 1; $i <= 10; $i++)
                                                <option value="{{ $i }}"
                                                        data-value="{{ $i }}" {{ ($i == $current_p ) ? "selected" : "" }}>{{ $i }} {{ $i == 1 ? 'persoon' : 'personen' }}</option>
                                            @endfor
                                        </select>
                                    </li>
                                     <li class="mobile-center">
                                        <button class="zoek" id="searchDesktop" type="submit" style="font-weight: bold;">zoek</button>
                                    </li> 
                                    @if($userAuth)
                                        <li>
                                            <a href="{{ url('account/reservations/saldo') }}" class="">Uw saldo:
                                                &euro; {{$userInfo->saldo }} </a>
                                        </li>
                                        <li data-content="Uitloggen">
                                            <a href="{{ url('logout') }}">
                                                <i class="sign out icon"></i>Uitloggen
                                            </a>
                                        </li>
                                    @else
                                        <li>
                                            <a id="registerButton" class="register button item" href="#">
                                                <div class="header_icons">
                                                    <img src="{{ asset('images/register_icon.png') }}" alt="question">
                                                </div>
                                            </a>
                                        </li>
                                        <li>
                                            <a class="login button" data-type="login" href="#">
                                                <div class="header_icons">
                                                    <img src="{{ asset('images/login_icon.png') }}" alt="question">
                                                </div>
                                            </a>
                                        </li>
                                    @endif
                                    <li>
                                        <a href="{{ url('/faq') }}" class="question">
                                            <div class="header_icons">
                                                <img src="{{ asset('images/help_icon.png') }}" alt="question">
                                            </div>
                                        </a>
                                    </li>
                                    <li>
											<a class="dropdown-button blog-submenu-init notranslate" id="language" href="#!" data-activates="dropdown1">
												<img src="{{ asset('images/flags/') }}/{{ $lang }}.png" alt="flag"> {{ strtoupper($lang) }} <i class="fa fa-angle-down" aria-hidden="true"></i>
											</a>
											<ul id="dropdown1" class="inline-menu submenu-ul dropdown-content notranslate">
												 <li><a href="{{ url('setlang/nl?redirect='.Request::url()) }}" data-value="nl" class="item {{ isset($userInfo->id) ? 'change-login-user-language' : '' }}"><i class="nl flag"></i> NL</a></li>
												 <li><a href="{{ url('setlang/en?redirect='.Request::url()) }}" data-value="en" class="item {{ isset($userInfo->id) ? 'change-login-user-language' : '' }}"><i class="gb flag"></i> EN</a></li>
												 <li><a href="{{ url('setlang/be?redirect='.Request::url()) }}" data-value="be" class="item {{ isset($userInfo->id) ? 'change-login-user-language' : '' }}"><i class="be flag"></i> BE</a></li>
												 <li><a href="{{ url('setlang/de?redirect='.Request::url()) }}" data-value="de" class="item {{ isset($userInfo->id) ? 'change-login-user-language' : '' }}"><i class="de flag"></i> DE</a></li>
												 <li><a href="{{ url('setlang/fr?redirect='.Request::url()) }}" data-value="fr" class="item {{ isset($userInfo->id) ? 'change-login-user-language' : '' }}"><i class="fr flag"></i> FR</a></li>
											</ul>
										</li>
                                </form>
                            </ul>
                        </div>
                    </nav>
                </div>
                <!-- menu end -->
            </div>
        </div>
    </div>

<!-- .container end -->
</header>
 {{--  @yield("header_picture")  --}}
	    