@if($user)
	<h4>SCHRIJF EEN BEOORDELING</h4>

	<?php echo Form::open(array('url' => 'restaurant/reviews/'.$company->slug, 'method' => 'post','id' => 'reviews', 'class' => 'ui star form')) ?>
	
	<?php echo Form::hidden('food', 1); ?>
	<?php echo Form::hidden('service', 1); ?>
	<?php echo Form::hidden('decor', 1); ?>

	<div class="inline fields">
		<div class="field">
			<label>Eten</label>
			<span id="food" class="ui star rating" data-rating="1"></span>
		</div>				

		<div class="field">
			<label>Service</label>
			<span id="service" class="ui star rating" data-rating="1"></span>
		</div>		

		<div class="field">
			<label>Decor</label>
			<span id="decor" class="ui star rating" data-rating="1"></span>
		</div>	
	</div>	

	<div class="field">
		<label>Recensie</label>
		<?php echo Form::textarea('content'); ?>
	</div>

	<button type="submit" class="ui small blue button">VERZENDEN</button>
	<?php echo Form::close(); ?>
@else
	<p><strong>U moet eerst ingelogd zijn om te kunnen reageren.</strong></p>
	<a href="{{ url('restaurant/'.$company->slug) }}"
	   data-redirect="{{ url('restaurant/'.$company->slug) }}"
	   data-type="login"
	   class="ui login button">
	   Inloggen
	</a><br />
@endif