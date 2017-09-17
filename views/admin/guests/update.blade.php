@extends('template.theme')

@inject('cityPref', 'App\Models\Preference')

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
		<?php echo Form::open(array('url' => 'admin/'.$slugController.'/update/'.$data->id, 'method' => 'post', 'class' => 'ui edit-changes form')) ?>
		<div class="fields">
			<div class="four wide field">
			   	<label>Aanhef</label>
				<?php echo Form::select('gender',  array(1 => 'Dhr', 2 => 'Mvr'), $data->gender, array('class' => 'multipleSelect')); ?>
			</div>

			<div class="twelve wide field">
			    <label>Naam</label>
			    <?php echo Form::text('name', $data->name) ?>
			</div>
		</div>

		<div class="field">
			<label>E-mailadres</label>
			<?php echo Form::text('email', $data->email) ?>
		</div>

		<div class="field">
			<label>Telefoonnummer</label>
			<?php echo Form::text('phone', $data->phone) ?>
		</div>

		<h4 class="ui dividing header">Voorkeuren</h4>

		<div class="field">
			<label>Nieuwsbrief</label>
			<?php
			$city = array();

			foreach($cityPref->where('category_id', 9)->get() as $result)
			{
				$city[$result->id] = $result->name;
			}

			echo Form::select('city', $city, $data->city, array('class' => 'multipleSelect')); 
			?>
		</div>

		<div class="two fields">
			<div class="field">
				<label>Voorkeuren</label>
				<?php
				echo Form::select('preferences[]', (isset($preference[1]) ? $preference[1] : array()), json_decode($data->preferences), array('multiple' => true, 'class' => 'multipleSelect')); 
				?>
			</div>

			<div class="field">
				<label>Duurzaamheid</label>
				<?php
				echo Form::select('sustainability[]', (isset($preference[8]) ? $preference[8] : array()), json_decode($data->sustainability), array('multiple' => true, 'class' => 'multipleSelect')); 
				?>
			</div>
		</div>

		<div class="two fields">
			<div class="field">
				<label>Keuken</label>
				<?php
				echo Form::select('kitchens[]',  (isset($preference[2]) ? $preference[2] : array()), json_decode($data->kitchens), array('multiple' => true, 'class' => 'multipleSelect')); 
				?>
			</div>		  		  

			<div class="field">
				<label>Allergie&euml;n</label>
				<?php
				echo Form::select('allergies[]', (isset($preference[3]) ? $preference[3] : array()), json_decode($data->allergies), array('multiple' => true, 'class' => 'multipleSelect')); 
				?>
			</div>		  		  	
		</div>

		<div class="two fields">
			<div class="field">
				<label>Faciliteiten</label>
				<?php
				echo Form::select('facilities[]', (isset($preference[7]) ? $preference[7] : array()), json_decode($data->facilities), array('multiple' => true, 'class' => 'multipleSelect')); 
				?>
			</div>		  		  

			<div class="field">
				<label>Kinderen</label>
				<?php
				echo Form::select('kids[]', (isset($preference[6]) ? $preference[6] : array()), json_decode($data->kids), array('multiple' => true, 'class' => 'multipleSelect')); 
				?>
			</div>		  		  	
		</div>
	  	
		<div class="two fields">
			<div class="field">
				<label>Korting</label>
				<?php
				echo Form::select('discount[]', (isset($preference[5]) ? $preference[5] : array()), json_decode($data->discount), array('multiple' => true, 'class' => 'multipleSelect')); 
				?>
			</div>		  		  

			<div class="field">
				<label>Prijs</label>
				<?php
				echo Form::select('price[]', (isset($preference[4]) ? $preference[4] : array()), json_decode($data->price), array('multiple' => true, 'class' => 'multipleSelect')); 
				?>
			</div>		  		  	
		</div>

		<h4 class="ui dividing header">Wachtwoord <small>(optioneel)</small></h4>

		<div class="field">
		    <label>Wachtwoord</label>
		    <?php echo Form::password('password') ?>
		</div>

		<div class="field">
		  <label>Wachtwoord controle</label>
		  <?php echo Form::password('password_confirmation') ?>
		</div>

		 <button class="ui tiny button" type="submit"><i class="pencil icon"></i> Wijzigen</button>
	<?php echo Form::close(); ?>
</div>
<div class="clear"></div>
@stop