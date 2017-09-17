@extends('template.theme')

@section('scripts')
    @include('admin.template.editor')

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
	<div class="ui grid">
		<div class="sixteen wide column">
			<div class="field">
				<label>Naam</label>
				<?php echo Form::text('name', $data->name); ?>
			</div>	

			@if ($userAdmin)
			<div class="field">
				<label>Bedrijf</label>
				<?php echo Form::select('company_id', $companies, $data->company_id, array('class' => 'ui normal search dropdown')); ?>
			</div>
			@endif

			<div class="two fields">
				<div class="field">
					<label>Datum van</label>

					<div class="ui icon input">
						<?php 
						echo Form::text(
							'date_from', 
							$data->date_from,
							array(
								'class' => 'datepicker', 
								'placeholder' => 'Selecteer een datum',
								'data-value' => $data->date_from
							)
						);
						?>
						<i class="calendar icon"></i>
					</div>
				</div>	

				<div class="field">
					<label>Datum tot</label>

					<div class="ui icon input">
						<?php 
						echo Form::text(
							'date_to', 
							$data->date_to,
							array(
								'class' => 'datepicker', 
								'placeholder' => 'Selecteer een datum',
								'data-value' => $data->date_to
							)
						);
						?>
						<i class="calendar icon"></i>
					</div>
				</div>	
			</div>	

			<div class="two fields">
				<div class="field">
					<label>Tijd van</label>

					<div class="ui icon input">
						<?php 
						echo Form::text(
							'time_from', 
							date('H:i', strtotime($data->time_from)),
							array(
								'class' => 'timepicker', 
								'placeholder' => 'Selecteer een tijd'
							)
						);
						?>
						<i class="clock icon"></i>
					</div>
				</div>	

				<div class="field">
					<label>Tijd tot</label>

					<div class="ui icon input">
						<?php 
						echo Form::text(
							'time_to', 
							date('H:i', strtotime($data->time_to)),
							array(
								'class' => 'timepicker', 
								'placeholder' => 'Selecteer een tijd'
							)
						);
						?>
						<i class="clock icon"></i>
					</div>
				</div>	
			</div>	

			<div class="field">
				<label>Aantal beschikbaar</label>
				<?php echo Form::number('total_amount', $data->total_amount, array('min' => 1)); ?>
			</div>	

			<div class="field">
				<label>Omschrijving</label>
				<?php echo Form::textarea('content', $data->description); ?>
			</div>

			<button class="ui button" type="submit"><i class="pencil icon"></i> Wijzigen</button>
		</div>
	</div>
	<?php echo Form::close(); ?>
</div>
<div class="clear"></div>
@stop