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
	
	<script type="text/javascript">
		var pageId = 'appointmentCreate';
	</script>

	<div class="ui icon info message">
	  	<i class="info icon"></i>

	  	<div class="content">
	    	<div class="header">
     		 	OPGELET!
    		</div>
    		<p>
    			Zodra <strong>"Stuur een mail (inschrijfformulier)"</strong> wordt aangevinkt, maakt het systeem automatisch een nieuw account voor de eigenaar van dit bedrijf. Het bedrijf krijgt gegevens van zijn / haar opgestuurd naar het e-mailadres die wordt opgegeven. (Dit gebeurd alleen wanneer het account nog niet bestaat met het opgegeven e-mailadres)
    		</p>
	  	</div>
	 </div>

	<?php echo Form::open(array('method' => 'post', 'id' => 'appointmentCreate', 'class' => 'ui edit-changes form cstm')) ?>
	<div class="ui grid cstm">
		<div class="column">
			<div class="two fields">
			    <div class="field">
					<label>Bedrijf</label>
					<?php echo Form::select('company', array_add($companies, '', 'Selecteer'), $companyBySlug, array('id' => 'companySelectAppointment', 'class' => 'ui normal search dropdown'));  ?>
			    </div>

			    <div class="field">
					<label>Contactpersoon</label>
					<?php echo Form::text('contact_name', '', array('id' => 'appointmentContactName')); ?>
			    </div>
			</div>

			<div class="two fields">
				<div class="field">
					<label>Datum</label>
					<div class="ui icon input">
						<?php echo Form::text('date', date('Y-m-d'), array('class' => 'datepicker', 'placeholder' => 'Selecteer een datum', 'data-value' => date('Y-m-d'))); ?>
						<i class="calendar icon"></i>
					</div>
				</div>	
				
				<div class="field">
					<label>Tijd</label>
					<div class="ui icon input">
						<?php echo Form::text('time', date('H:i'), array('class' => 'timepicker', 'placeholder' => 'Selecteer een tijd', 'data-value' => date('H:i'))); ?>
						<i class="clock icon"></i>
					</div>
				</div>	
			</div>

			<div class="two fields">
				<div class="field">
					<label>Plaats</label>
					<?php echo Form::text('place', '', array('id' => 'appointmentPlace')); ?>
				</div>	

				<div class="field">
					<label>Status</label>
					<?php echo Form::text('status'); ?>
				</div>	
			</div>

			<div class="two fields">
				<div class="field">
					<div class="ui checkbox">
					   	 <?php echo Form::checkbox('send_mail', 1, array('checked' => 'checked')); ?>
					   	 <label>Stuur een mail (inschrijfformulier)</label>
					</div><br /><br />

					<div class="ui checkbox">
					   	 <?php echo Form::checkbox('send_information_mail', 1, array('checked' => 'checked')); ?>
					   	 <label>Stuur een mail (informatiemail)</label>
					</div><br /><br />

					<div class="ui checkbox">
					   	 <?php echo Form::checkbox('send_reminder', 1, array('checked' => 'checked')); ?>
					   	 <label>Stuur een herinnering <small>(Een dag voor de datum/tijd)</small></label>
					</div>
				</div>
				
				<div class="field">
					<div class="field">
						<label>E-mailadres</label>
						<?php echo Form::text('email', '', array('id' => 'appointmentEmail')); ?>
					</div>	
				</div>
			</div>

			<div class="field">
				<label>Opmerkingen</label>
				<?php echo Form::textarea('comment', '', array('id' => 'appointmentComment')); ?>
			</div>	
			<button class="ui button" type="submit"><i class="plus icon"></i> Aanmaken</button>
		</div>
	</div>	
	<?php echo Form::close(); ?>
</div>
<div class="clear"></div>
@stop