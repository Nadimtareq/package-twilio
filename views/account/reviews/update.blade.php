@extends('template.theme')

@section('content')
<div class="content">
	<div class="ui breadcrumb">
		<a href="{{ url('/') }}" class="section">Home</a>
		<i class="right chevron icon divider"></i>

		<a href="#" class="sidebar open" data-activates="slide-out">Menu</a>
	    <i class="right chevron icon divider"></i>

		<div class="active section">Wijzig recensie</div>
	</div>

    <div class="ui divider"></div>

	<?php echo Form::open(array('method' => 'post','id' => 'reviews', 'class' => 'ui star form')) ?>
		<?php echo Form::hidden('food', $data->food); ?>
		<?php echo Form::hidden('service', $data->service); ?>
		<?php echo Form::hidden('decor', $data->decor); ?>

		<div class="inline fields">
			<div class="field">
				<label>Eten</label>
				<span id="food" class="ui star rating" data-rating="{{ $data->food }}"></span>
			</div>				

			<div class="field">
				<label>Service</label>
				<span id="service" class="ui star rating" data-rating="{{ $data->service }}"></span>
			</div>		

			<div class="field">
				<label>Decor</label>
				<span id="decor" class="ui star rating" data-rating="{{ $data->decor }}"></span>
			</div>	
		</div>	

		<div class="field">
			<label>Recensie</label>
			<?php echo Form::textarea('content', $data->content); ?>
		</div>

		<button type="submit" class="ui small blue button">Wijzigen</button>
	<?php echo Form::close(); ?>
</div>
@stop