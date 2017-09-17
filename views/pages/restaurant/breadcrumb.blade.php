<div class="clear"></div>
<a href="{{ url('/') }}" class="section">Home</a>
<i class="right chevron icon divider"></i>

<a href="{{ url('search?q='.$company->city) }}" class="section">
 	  <div class="ui normal scrolling bread pointing dropdown item">
 	      <div class="text">{{ $company->city }}</div>

      	<div class="menu">
        	 @foreach($preference[9] as $city)
        	 <a href="{{ url('search?q='.$city) }}" class="item" data-text="today">{{ $city }}</a>
        	 @endforeach
      	</div>
    </div>
</a>
<i class="right chevron icon divider"></i>

@if ($company->kitchens != 'null'  && $company->kitchens != NULL && $company->kitchens != '[""]')
<a href="{{ url('search?q='.$company->city) }}" class="section">
	<?php 
	$kitchens = json_decode($company->kitchens); 
	?>
  	<div class="ui normal scrolling bread pointing dropdown item">
      	<div class="text">{{ ucfirst($kitchens[0]) }}</div>
      	<div class="menu">
        	@foreach($preference[4] as $city)
        	<a href="{{ url('search?q='.$city) }}" class="item" data-text="today">{{ $city }}</a>
        	@endforeach
      	</div>
    </div>
</a>
<i class="right chevron icon divider"></i>
@endif

<div class="active section"><h1>{{ $company->name }}</h1></div>