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
    @if($appointment != null)
    	@include('admin.template.breadcrumb')
    
		<?php echo Form::open(array('method' => 'post', 'class' => 'ui edit-changes form')) ?>
		<div class="ui grid">
			<div class="column">
			
			    <div class="field">
					<label>Bedrijf</label>
					<?php echo Form::select('company', array_add($companies, 0, 'Selecteer een bedrijf'), $appointment->company_id, array('class' => 'ui normal search dropdown'));  ?>
			    </div>      

				<div class="two fields">
					<div class="field">
						<label>Datum</label>
						<div class="ui icon input">
							<?php echo Form::text('date', date('Y-m-d', strtotime($appointment->appointment_at)), array('class' => 'datepicker', 'placeholder' => 'Selecteer een datum')); ?>
							<i class="calendar icon"></i>
						</div>
					</div>	
					
					<div class="field">
						<label>Tijd</label>
						<div class="ui icon input">
							<?php echo Form::text('time', date('H:i', strtotime($appointment->appointment_at)), array('class' => 'timepicker', 'placeholder' => 'Selecteer een tijd')); ?>
							<i class="clock icon"></i>
						</div>
					</div>	
				</div>

				<div class="two fields">
					<div class="field">
						<label>Plaats</label>
						<?php echo Form::text('place', $appointment->place); ?>
					</div>	

					<div class="field">
						<label>Status</label>
						<?php echo Form::text('status', $appointment->status); ?>
					</div>	
				</div>

				<div class="two fields">
					<div class="field">
						<div class="ui checkbox">
						   	 <?php echo Form::checkbox('send_reminder', 1, $appointment->send_reminder); ?>
						   	 <label>Stuur een herinnering <small>(Een dag voor de datum/tijd)</small></label>
						</div>
					</div><br /><br />
					

					<div class="field">
						<div class="field">
							<label>E-mailadres</label>
							<?php echo Form::text('email', $appointment->email); ?>
						</div>	
					</div>
				</div>

				<div class="field">
					<label>Opmerkingen</label>
					<?php echo Form::textarea('comment', $appointment->comment); ?>
				</div>	
			</div>
		</div><br />

		<button class="ui button" type="submit"><i class="save icon"></i> Opslaan</button>
		<?php echo Form::close(); ?>
	@else
		<div class="ui error message">Het formulier met record ID <strong>{{ Request::segment(4) }}</strong> is niet gevonden.</div>
	@endif
</div>
<div class="clear"></div>
@stop