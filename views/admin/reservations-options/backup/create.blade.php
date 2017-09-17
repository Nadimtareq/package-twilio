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
				<?php echo Form::text('name'); ?>
			</div>	

			@if ($userAdmin)
			<div class="field">
				<label>Bedrijf</label>
				<?php echo Form::select('company_id', $companies, ($slug != NULL ? $company['id'] : NULL), array('class' => 'ui normal search dropdown')); ?>
			</div>
			@endif

			<div class="two fields">
				<div class="field">
					<label>Datum van</label>

					<div class="ui icon input">
						<?php 
						echo Form::text(
							'date_from', 
							'',
							array(
								'class' => 'datepicker', 
								'placeholder' => 'Selecteer een datum'
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
							'',
							array(
								'class' => 'datepicker', 
								'placeholder' => 'Selecteer een datum'
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
							'',
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
							'',
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
				<?php echo Form::number('total_amount', 1, array('min' => 1)); ?>
			</div>	

			<div class="field">
				<label>Omschrijving</label>
				<?php echo Form::textarea('content'); ?>
			</div>

			<button class="ui button" type="submit"><i class="plus icon"></i> Aanmaken</button>
		</div>
	</div>
	<?php echo Form::close(); ?>
</div>
<div class="clear"></div>
@stop