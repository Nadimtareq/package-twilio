@extends('template.theme')

@section('scripts')
	<script type="text/javascript">
		$(document).ready(function() {
		    closeBrowser();  
		});
	</script>
@stop

@section('content')
<div class="content">
    @include('admin.template.breadcrumb')

	<?php echo Form::open(array('method' => 'post', 'class' => 'ui edit-changes form', 'files' => TRUE)) ?>
	<div class="two fields">
		<div class="field">
			<label>Naam</label>
			<?php echo Form::text('name') ?>
		</div>

		<div class="field">
			<label>Bedrijf</label>
			<?php echo Form::select('company', $companies, null, array('class' => 'ui normal search dropdown'));  ?>
		</div>
	</div>	

	<div class="two fields">
		<div class="field">
			<label>Prijs</label>
			<?php echo Form::number('price') ?>
		</div>	

		<div class="field">
			<label>BTW</label>
			<?php echo Form::number('tax') ?>
		</div>	
	</div>	

	<div class="three fields">
		<div class="field">
			  <label>Startdatum</label>
			 <?php 
			 echo Form::text(
			 	'start_date',
			 	'',
			 	array(
			 		'class' => 'datepicker', 
			 		'placeholder' => 'Selecteer een datum'
			 	)
			 ); ?>
		</div>

		<div class="field">
			  <label>Einddatum</label>
			 <?php 
			 echo Form::text(
			 	'end_date',
			 	'',
			 	array(
			 		'class' => 'datepicker', 
			 		'placeholder' => 'Selecteer een datum'
			 	)
			 ); ?>
		</div>

		<div class="field">
			<label>Perodiek</label>
			<?php 
			echo Form::select(
				'period',  
				array(
					0 => 'Eenmalig', 
					7 => 'Wekelijks',
					14 => 'Tweewekelijks',
					21 => 'Driewekelijks',
					28 => 'Maandelijks',
				), 
				0, 
				array(
					'class' => 'ui normal dropdown'
				)
			); 
			?>
		</div>
	</div>

	<div class="field">
		<?php echo Form::textarea('content') ?>
	</div>

	<button class="ui button" type="submit"><i class="plus icon"></i> Aanmaken</button>
	<?php echo Form::close(); ?>
</div>
<div class="clear"></div>
@stop