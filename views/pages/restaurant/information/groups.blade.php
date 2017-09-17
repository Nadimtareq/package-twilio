<?php echo Form::open(array('id' => 'reservationForm', 'url' => 'restaurant/reservation/'.$company->slug, 'method' => 'PUT', 'class' => 'ui form')) ?>
	<?php echo Form::hidden('group_reservation', 1); ?>
	<?php echo Form::hidden('setTimeBack', 0); ?>
	<?php echo Form::hidden('company_id', $company->id); ?>
	
	{!! isset($contentBlock[59]) ? $contentBlock[59] : '' !!}

	<div class="three fields">
		<div class="field">
			<label>Datum</label>
			<?php echo Form::text('date', '', array('class' => 'reservationDatepicker'));  ?>
		</div>	

		<div class="field">
			<label>Tijd</label>
			<div id="timeField-2" class="ui normal selection dropdown time timeRefresh">
				<input id="timeInput" name="time" type="hidden">
					  	
				<i class="time icon"></i>
				<div class="default text">Tijd</div>
				<i class="dropdown icon"></i>

				<div class="menu">
					<div class="item" data-value="<?php echo date('H:i', strtotime(Request::get('time'))); ?>"><?php echo date('H:i', strtotime(Request::get('time'))); ?></div>
				</div>
			</div>
		</div>

		<div class="field">
			<label>Personen</label>
			<?php echo Form::text('persons'); ?>
		</div>	
	</div>

	<div class="three fields">
		<div class="field">
			<label>Naam</label>
			<?php echo Form::text('name', (Sentinel::check() ? Sentinel::getUser()->name : '')) ?>
		</div>

		<div class="field">
			<label>E-mail</label>
			<?php echo Form::text('email',  (Sentinel::check() ? Sentinel::getUser()->email : '')) ?>
		</div>

		<div class="field">
			<label>Telefoon</label>
			<?php echo Form::text('phone',  (Sentinel::check() ? Sentinel::getUser()->phone : '')) ?>
		</div>
	</div>

	<div class="field">
		<label>Opmerking</label>
		<?php echo Form::textarea('comment'); ?>
	</div>

	<button type="submit" class="ui small blue button">Reserveren</button>
<?php echo Form::close(); ?>