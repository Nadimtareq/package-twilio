 @php
   $isGrey = ( Route::getCurrentRoute()->uri() != '/'); 
   $headerImg = request()->has("layout") ? true : false;
 @endphp
 @if(isset($userAuth->lang) && !empty($userAuth->lang))
	 {{-- */$lang=$userAuth->lang;/* --}}
 @elseif(Session::has('language'))
	 {{-- */$lang=Session::get('language');/* --}}
 @else
	 {{-- */$lang='nl';/* --}}
 @endif
 @if($headerImg)
 	<header> <!--   -->
@else
		 <header id="navigation" class="root-sec white nav header">
@endif
@if($headerImg)
     	<div class="header">
@endif
			<div class="container">
				<div class="row">
					<div class="col-sm-12">
						<div class="nav-inner">
							<nav class="primary-nav">
								@include('template.sidemenu')

								<div class="clearfix nav-wrapper">
									@if($userAuth)
										<a href="{{ url('/home') }}" class="brand-logo">
									@else
										<a href="{{ url('/') }}" class="brand-logo">
									@endif
									<img src="{{ (($isGrey) ) ? asset('images/logo_grey.png') : asset('images/logo.png') }}" alt="" class="responsive-img">
									</a>
									<ul class="inline-menu side-nav" id="mobile-demo">
										 @if($userAuth)
									<li><a href="{{ url('account/reservations/saldo') }}" class="">Uw saldo: &euro; {{$userInfo->saldo }} </a></li>
											<li data-content="Uitloggen"><a href="{{ url('logout') }}"><i class="sign out icon"></i>Uitloggen</a></li>
										@else
											<li><a id="registerButton" class="register button item" href="#" title="Aanmelden"><div class="header_icons"><img src="{{ asset('images/register_icon.png') }}" alt="question"></div></a>
											</li>
											<li><a class="login button" data-type="login" href="#" title="Inloggen"><div class="header_icons"><img src="{{ asset('images/login_icon.png') }}" alt="question"></div> </a></li>
										@endif
										<li>
										  <a href="{{ url('/faq') }}" class="question" title="Help"><div class="header_icons"><img src="{{ asset('images/help_icon.png') }}" alt="question"></div> </a>
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
										<li>
											<a id="#" class="item search-full-open"><i class="mdi-action-search"></i> </a>											
										</li>
									</ul>


							</div>
						</nav>
					</div>
				</div>
			</div>
		</div>
	@if($headerImg)
		</div>
 	@endif

<!-- .container end -->
</header>
@yield("header_picture")

@push('inner_scripts')
<script type="text/javascript">

$(".lang-button").click(function(){
	
	swal({
  title: "Are you sure..",
	text: "..you want to set your default language as English?",
  type: 'warning',
  showCancelButton: true,
  confirmButtonColor: '#3085d6',
  cancelButtonColor: '#d33',
  confirmButtonText: 'Yes!',
  cancelButtonText: 'No!'
  
}).then(function () {
  swal(
    'Deleted!',
    'Your file has been deleted.',
    'success'
  )
}, function (dismiss) {
  // dismiss can be 'cancel', 'overlay',
  // 'close', and 'timer'
  if (dismiss === 'cancel') {
    swal(
      'Cancelled',
      'Your imaginary file is safe :)',
      'error'
    )
  }
})

});


</script>
@endpush
