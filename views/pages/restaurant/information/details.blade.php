<!-- <h5 class="ui header thin"><div class="content">Details</div></h5>
{!! $company->details !!} -->

@if ($company->preferences != 'null' && $company->preferences != NULL && $company->preferences != '[""]')
	<h5>Voorkeuren</h5>

	@foreach (json_decode($company->preferences) as $id => $preferencesr)
		@if (isset($preferences[1][$preferencesr]))
	    	{{ ucfirst($preferences[1][$preferencesr]) }},
	    @endif
	@endforeach
@endif

@if ($company->kitchens != 'null' && $company->kitchens != NULL && $company->kitchens != '[""]')
	<h5>Keuken</h5>

	@foreach (json_decode($company->kitchens) as $id => $kitchen)
	    {{ ucfirst($preferences[2][$kitchen]) }},
	@endforeach
@endif

@if ($company->price != 'null' && $company->price != NULL && $company->price != '[""]')
	<h5>Soort</h5>

	@foreach (json_decode($company->price) as $id => $price)
	    {{ ucfirst($preferences[4][$price]) }},
	@endforeach
@endif

@if ($company->sustainability != 'null' && $company->sustainability != NULL && $company->sustainability != '[""]')
	<h5>Duurzaamheid</h5>

	@foreach (json_decode($company->sustainability) as $id => $sustainability)
	    {{ ucfirst($preferences[8][$sustainability]) }},
	@endforeach
@endif

@if ($company->discount != 'null' && $company->discount != NULL && $company->discount != '[""]')
	<h5>Korting</h5>
	{!! $company->discount_comment !!}

	@foreach (json_decode($company->discount) as $id => $discount)
		{{ $discount }}
		<!-- 	@if (isset($preferences[5][$discount]))
	    {{ ucfirst($preferences[5][$discount]) }}%
	    @endif -->
	@endforeach

	<h5>Kortingsdagen</h5>
	<?php $dayNames = Config::get('preferences.days'); ?>
	@if ($company->days != 'null' && $company->days != NULL && $company->days != '[""]')
		<?php $i = 0; ?>
		@foreach (json_decode($company->days) as $id => $days)
		<?php $i++; ?>
		    {{ $dayNames[$days] }} 
		    <?php echo ($i < count(json_decode($company->days)) ? '-' : ''); ?>
		@endforeach
	@endif
@endif
