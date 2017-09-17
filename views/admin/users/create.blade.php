@extends('template.theme')

@inject('preference', 'App\Models\Preference')

@section('content')
<div class="content">
    @include('admin.template.breadcrumb')

	<?php echo Form::open(array('url' => 'admin/'.$slugController.'/create', 'method' => 'post', 'class' => 'ui edit-changes form')) ?>
		<div class="field">
			<label>Rol</label>
			<?php 
			echo Form::select(
				'role',
				array(
					1 => 'Lid', 
					2 => 'Bedrijf',
					3 => 'Admin',
					4 => 'Bediening',
					5 => 'Callcenter'
				), 
				1, 
				array('class' => 'multipleSelect')
			); 
			?>
		</div>

		<div class="fields">
			<div class="four wide field">
			   	<label>Aanhef</label>
				<?php echo Form::select('gender',  array(1 => 'Dhr', 2 => 'Mvr'), '', array('class' => 'multipleSelect')); ?>
			</div>

			<div class="twelve wide field">
			    <label>Naam</label>
			    <?php echo Form::text('name') ?>
			</div>
		</div>

		<div class="field">
			<label>E-mailadres</label>
			<?php echo Form::text('email') ?>
		</div>

		<div class="field">
			<label>Telefoonnummer</label>
			<?php echo Form::text('phone') ?>
		</div>

		<div class="field">
			<label>Geboortedatum</label>
			<?php echo Form::text('birthday_at', '', array('class' => 'ajax-datepicker')); ?>
		</div>

		<div class="field">
			<label>Saldo</label>
			<div class="ui left labeled input">
			  	<div class="ui label">&euro;</div>
				<?php echo Form::text('saldo') ?>
			</div>
		</div>
		
		<h4 class="ui dividing header  no_blue">Voorkeuren</h4>
			<div class="field">
				<label>Nieuwsbrief</label>
				<?php
				$city = array();
				$city[''] = 'Stad';

				foreach ($preference->where('category_id', 9)->get() as $data) {
					$city[$data->id] = $data->name;
				}

				echo Form::select('city[]', $city, '', array('multiple' => true, 'class' => 'ui normal fluid search dropdown')); 
				?>
			</div>

			<div class="two fields">
				<div class="field">
					<label>Voorkeuren</label>
					<?php echo Form::select('preferences[]', (isset($preference[1]) ? $preference[1] : array()), '', array('multiple' => true, 'class' => 'multipleSelect'));  ?>
				</div>

				<div class="field">
					<label>Duurzaamheid</label>
					<?php echo Form::select('sustainability[]', (isset($preference[8]) ? $preference[8] : array()), '', array('multiple' => true, 'class' => 'multipleSelect'));  ?>
				</div>
			</div>

			<div class="two fields">
				<div class="field">
					<label>Keuken</label>
					<?php echo Form::select('kitchens[]', (isset($preference[2]) ? $preference[2] : array()), '', array('multiple' => true, 'class' => 'multipleSelect'));  ?>
				</div>		  		  

				<div class="field">
					<label>Allergie&euml;n</label>
					<?php echo Form::select('allergies[]', (isset($preference[3]) ? $preference[3] : array()), '', array('multiple' => true, 'class' => 'multipleSelect'));  ?>
				</div>		  		  	
			</div>

			<div class="two fields">
				<div class="field">
					<label>Faciliteiten</label>
					<?php echo Form::select('facilities[]', (isset($preference[7]) ? $preference[7] : array()), '', array('multiple' => true, 'class' => 'multipleSelect'));  ?>
				</div>		  		  

				<div class="field">
					<label>Kinderen</label>
					<?php echo Form::select('kids[]', (isset($preference[6]) ? $preference[6] : array()), '', array('multiple' => true, 'class' => 'multipleSelect'));  ?>
				</div>		  		  	
			</div>
		  	
			<div class="two fields">
				<div class="field">
					<label>Korting</label>
					<?php echo Form::select('discount[]', (isset($preference[5]) ? $preference[5] : array()), '', array('multiple' => true, 'class' => 'multipleSelect')); ?>
				</div>		  		  

				<div class="field">
					<label>Prijs</label>
					<?php echo Form::select('price[]', (isset($preference[4]) ? $preference[4] : array()), '', array('multiple' => true, 'class' => 'multipleSelect')); ?>
				</div>		  		  	
			</div>
		<h4 class="ui dividing header  no_blue">Wachtwoord</h4>

		<div class="field">
		    <label>Wachtwoord</label>
		    <?php echo Form::password('password') ?>
		</div>

		<div class="field">
		  <label>Wachtwoord controle</label>
		  <?php echo Form::password('password_confirmation') ?>
		</div>

		 <button class="ui tiny button" type="submit"><i class="plus icon"></i> Aanmaken</button>
	<?php echo Form::close(); ?>
</div>
<div class="clear"></div>
@stop