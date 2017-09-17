<div class="maps">
	<div id="map" 
	    data-kitchen="{{ is_array(json_decode($company->kitchens)) ? str_slug(json_decode($company->kitchens)[0]) : '' }}" 
	    data-url="{{ url('restaurant/'.$company->slug) }}" 
	    data-name="{{ $company->name }}" 
	    data-address="{{ $company->address }}" 
	    data-city="{{ $company->city }}" 
	    data-zipcode="{{ $company->zipcode }}"></div>
</div>