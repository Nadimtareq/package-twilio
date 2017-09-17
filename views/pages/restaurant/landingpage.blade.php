<!DOCTYPE html>
<html lang="nl">
<head>
    <title>UwVoordeelpas</title>

    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('public/images/icons/apple-icon-180x180.png') }}">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('public/images/icons/android-icon-192x192.png') }}">
    <link rel="shortcut icon" type="image/png" sizes="16x16" href="{{ asset('public/images/icons/favicon-16x16.png') }}">

    <link rel="stylesheet" href="{{ asset('public/css/app.css?rand='.str_random(40)) }}">

    <!--
    <link rel="stylesheet" href="{{ asset('public/css/materialize.css?rand='.str_random(40)) }}">
    -->
    @yield('styles')

    <meta name="_token" content="{!! csrf_token() !!}"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=yes">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">
</head>
<body>
	<div class="container">
		<h1 class="ui header thin center aligned" style="padding-left: 30px; padding-top: 20px;">Komt u ook graag een keer gratis bij ons eten?</h1>

		<div class="ui grid">
			<div class="four wide computer sixteen wide mobile column" style="text-align: center;">
				<a href="{{ url($restaurantUrl) }}">
					<img src="https://cdn1.iconfinder.com/data/icons/marketing-outlined/60/shop-trolly-cart-store-128.png">
				</a>
				<h5>1. Shopt u ook online?</h5>
			</div>

			<div class="four wide computer sixteen wide mobile column" style="text-align: center;">
				<a href="{{ url($restaurantUrl) }}">
					<img src="https://cdn1.iconfinder.com/data/icons/marketing-outlined/60/euro-paper-money-cash-128.png">
				</a>

				<h5>2. Spaar bij 1500+ Webshops!</h5>
			</div>

			<div class="four wide computer sixteen wide mobile column" style="text-align: center;"><a href="{{ url($restaurantUrl) }}"> <img src="https://cdn1.iconfinder.com/data/icons/marketing-outlined/60/calendar-agenda-days-time-mark-128.png"> </a>
				<h5>3. Reserveer met uw spaartegoed!</h5>
			</div>

			<div class="four wide computer sixteen wide mobile column" style="text-align: center;"><a href="{{ url($restaurantUrl) }}"> <img src="https://cdn1.iconfinder.com/data/icons/marketing-outlined/60/wallet-money-card-id-purse-pouch-128.png"> </a>
				<h5>4. Geniet van uw spaartegoed!</h5>
			</div>
		</div>

		<div style="width: 500px; margin: 40px auto;">
			<div class="fb-page"
				 data-href="{{ trim($company['facebook']) != '' ? $company['facebook'] : (isset($websiteSettings['facebook']) ? $websiteSettings['facebook'] : 'https://www.facebook.com/Uwvoordeelpas-321703168185624/?fref=ts') }}"
				 data-small-header="false"
				 data-adapt-container-width="true"
				 data-hide-cover="false"
				 data-show-facepile="false">
				 <blockquote cite="{{ trim($company['facebook']) != '' ? $company['facebook'] : (isset($websiteSettings['facebook']) ? $websiteSettings['facebook'] : 'https://www.facebook.com/Uwvoordeelpas-321703168185624/?fref=ts') }}" class="fb-xfbml-parse-ignore"><a href="https://www.facebook.com/Uwvoordeelpas-321703168185624/?fref=ts">Uwvoordeelpas</a></blockquote>
			</div>
		</div>
	</div>

	<div id="fb-root"></div>

<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/nl_NL/sdk.js#xfbml=1&version=v2.8";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
</body>
</html>