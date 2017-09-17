@extends('template.theme')

@section('scripts')
<script type="text/javascript">
$(document).ready(function() {
  	$('.removeButton').click(function() {
		swal({   
			title: "Weet u het zeker?",   
			text: "Weet u zeker dat u uw reservering(en) wil annuleren?",   
			type: "warning",   
			showCancelButton: true,   
			confirmButtonColor: "#DD6B55",  
			cancelButtonText: "Nee",   
			confirmButtonText: "Ja, ik weet het zeker!",   
			closeOnConfirm: false 
		}, 
		function() { 
			$('<input />').attr('type', 'hidden')
              .attr('name', 'type')
              .attr('value', 'cancel')
              .appendTo('#reservationForm');

			$('#reservationForm').submit(); 
		});

		return false;
	});
});
</script>
@stop

@section('content')
<script type="text/javascript">
	var activateAjax = 'reservation';
	var errorResEdit = '{{ count($errors) }}';
	var oldTimeValue = '<?php echo date('H:i', strtotime($reservation->time)); ?>';
</script>
<?php $reservationDate = \Carbon\Carbon::create(date('Y', strtotime($reservation->date)), date('m', strtotime($reservation->date)), date('d', strtotime($reservation->date)));?>

<div class="container">
	<div class="ui breadcrumb">
		<a href="{{ url('/') }}" class="section">Home</a>
		<i class="right chevron icon divider"></i>

		<a href="#" class="sidebar open" data-activates="slide-out">Menu</a>
    	<i class="right chevron icon divider"></i>

		@if($userInfo->id == $reservation->companyOwner)
   	 	<a href="{{ url('admin/reservations/clients/'.$reservation->company_id.'/'.date('Ymd', strtotime($reservation->date))) }}">Reserveringen</a>
   	 	@else
   	 	<a href="{{ url('account/reservations') }}">Reserveringen</a>
   	 	@endif

		<i class="right arrow icon divider"></i>
		<div class="active section">Wijzig reservering</div>
	</div>

	<div class="ui divider"></div> 
	
	<?php echo Form::open(array('id' => 'reservationForm', 'url' => URL::full(), 'method' => 'post', 'class' => 'ui form')) ?>
		<?php echo Form::hidden('date_hidden', date('Y-m-d', strtotime($reservation->date))); ?>
		<?php echo Form::hidden('company_id', $reservation->company_id); ?>
		<?php echo Form::hidden('old_time', date('H:i', strtotime($reservation->time))); ?>

		@if ($userInfo->id != $reservation->user_id)
		<?php echo Form::hidden('user_id', $reservation->user_id); ?>
		@endif 

		@if (Request::has('company_page'))
			@if (Sentinel::inRole('admin') OR Sentinel::inRole('bedrijf'))
				{{ Form::hidden('companyPage', 1) }}
			@endif
		@endif

		<?php echo Form::hidden('setTimeBack', 0); ?>
	 		<div class="three fields">
				<div class="field">
				    <label>Datum</label>
				    <?php echo Form::text('date', $reservation->date, array('class' => 'reservationDatepicker'));  ?>
				</div>	

				<div class="field">
				    <label>Tijd</label>
					<div id="timeField" class="ui normal selection dropdown time timeRefresh">
					  	<input id="timeInput" name="time" type="hidden" value="<?php echo date('H:i', strtotime($reservation->time)); ?>">
					  	
					  	<i class="time icon"></i>
					  	<div class="default text">Tijd</div>
					  	<i class="dropdown icon"></i>

					  	<div class="menu">
					     	 <div class="item" data-value="<?php echo date('H:i', strtotime($reservation->time)); ?>">
					     	 	<?php echo date('H:i', strtotime($reservation->time)); ?>
					     	 </div>
					  	</div>
					</div>
				</div>

				<div class="field">
				    <label>Personen</label>
				    <div id="personsField" class="ui normal compact selection dropdown persons searchReservation">
						<input type="hidden" name="persons" value="<?php echo $reservation->persons; ?>">
						<i class="male icon"></i>
									
						<div class="default text">Personen</div>
						<i class="dropdown icon"></i>
						<div class="menu">
							@for($i = 1; $i <= 10; $i++)
							<div class="item" data-value="{{ $i }}">{{ $i.' '.($i == 1 ? 'persoon' : 'personen') }}</div>
							@endfor
						</div>
					</div>
			</div>	
		</div>

		<div class="two fields">
			<div class="field">
				<label>Spaartegoed</label>
				<?php echo Form::number('saldo', $reservation->saldo, array('min' => 0, 'max' => 500)); ?>
				<?php echo Form::hidden('old_saldo', $reservation->saldo, array('min' => 0, 'max' => 500)); ?>
			</div>	
			
			<div class="field">
				<label>Voorkeuren</label>
				<?php echo Form::select('preferences[]', array_combine(json_decode($reservation->companyPreferences), array_map('ucfirst', json_decode($reservation->companyPreferences))), json_decode($reservation->preferences), array('class' => 'multipleSelect', 'data-placeholder' => 'Allergieen',  'multiple' => 'multiple')); ?>
			</div>	

			<div class="field">
				<label>Allergie&euml;n</label>
				<?php echo Form::select('allergies[]', array_combine(json_decode($reservation->companyAllergies), array_map('ucfirst', json_decode($reservation->companyAllergies))), json_decode($reservation->allergies), array('class' => 'multipleSelect', 'data-placeholder' => 'Allergieen',  'multiple' => 'multiple')); ?>
			</div>	
		</div>

		<div class="two fields">
			<div class="field">
				<label>Naam</label>
				<?php echo Form::text('name', $reservation->name);  ?>
			</div>	

			<div class="field">
				<label>Telefoonnummer</label>
				<?php echo Form::text('phone', $reservation->phone);  ?>
			</div>	

			<div class="field">
				<label>E-mailadres</label>
				<?php echo Form::text('email', $reservation->email);  ?>
			</div>
		</div>
			
		<div class="field">
			<label>Opmerking</label>
			<?php echo Form::textarea('comment', $reservation->comment);  ?>
		</div>

		@if($userAuth == TRUE)
			@if($userInfo->terms_active == 0)
	        <div class="field">
				<div class="ui checkbox">
					<?php echo Form::checkbox('av', 1); ?>
					<label>Ik ga akkoord met de <a href="{{ url('algemene-voorwaarden') }}" target="_blank">voorwaarden</a></label>
				</div>  
	         </div>
	         @else
	            <?php echo Form::hidden('av', 1); ?>
	        @endif
	    @else
	     	<div class="field">
				<div class="ui checkbox">
					<?php echo Form::checkbox('av', 1); ?>
					<label>Ik ga akkoord met de <a href="{{ url('algemene-voorwaarden') }}" target="_blank">voorwaarden</a></label>
				</div>  
	         </div>
	    @endif

	<button class="ui button" type="submit" name="type" value="edit"><i class="plus icon"></i> Wijzigen</button>
	<button class="ui grey button removeButton" type="submit" name="type" value="cancel"><i class="minus circle icon"></i> Annuleren</button>
	<?php echo Form::close(); ?>
</div>
<div class="clear"></div>
@stop