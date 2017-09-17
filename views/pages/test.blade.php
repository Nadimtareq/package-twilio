@extends('template.theme')

@section('content')
<div class="container">
	@for ($i = 0; $i < 15; $i++)
	<div class="owl-wrapper">
	    <div id="owl-example-{{ $i }}" class="owl-carousel">
	        @foreach ($allTimesArray as $time)

	      	<div>
	      		<a href="http://localhost/laravel/restaurant/reservation/shahiems?date=20161109&amp;time=2345&amp;persons=2" data-redirect="http://localhost/laravel/restaurant/reservation/shahiems?date=20161109&amp;time=2345&amp;persons=2" data-type-redirect="1" class="ui fluid blue mini  button guestClick">
                     {{ $time }}
                </a>
            </div>
            @endforeach
	    </div>

	    <div class="customNavigation">
	      <a class="btn prev">Previous</a>
	      <a class="btn next">Next</a>
	      <a class="btn play">Autoplay</a>
	      <a class="btn stop">Stop</a>
	    </div>
	</div>
	@endfor
</div>
@stop