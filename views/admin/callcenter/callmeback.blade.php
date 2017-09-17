@extends('template.theme')

@inject('preference', 'App\Models\Preference')

@section('content')
<div class="content">
	@include('admin.template.breadcrumb')

	<?php echo Form::open(array('method' => 'post', 'class' => 'ui form')) ?>
		<div class="three fields">
				<div class="field">
				    <label>Bedrijfsnaam</label>
				    <?php echo Form::text('name') ?>
				</div>

				<div class="field">
				    <label>E-mailadres</label>
				    <?php echo Form::text('email') ?>
				 </div>

				<div class="field">
				    <label>Telefoonnummer</label>
				    <?php echo Form::text('phone') ?>
				</div>
			</div>

			<h4>Wanneer mogen wij u terug bellen?</h4>
			<div class="two fields">
				<div class="field">
				    <label>Datum</label>
					<div class="ui icon input">
						<?php echo Form::text('date', '', array('class' => 'datepicker', 'placeholder' => 'Selecteer een datum')); ?>
						<i class="calendar icon"></i>
					</div>
				</div>

				<div class="field">
				    <label>Tijd</label>
					<div class="ui icon input">
						<?php echo Form::text('time', '', array('class' => 'timepicker', 'placeholder' => 'Selecteer een tijd')); ?>
						<i class="clock icon"></i>
					</div>
				</div>
			</div>

			<button class="ui button" type="submit"><i class="phone icon"></i> Bel mij terug</button>

	<?php echo Form::close(); ?>
</div>
<div class="clear"></div>
@stop
