@extends('template.theme')

@inject('cityPref', 'App\Models\Preference')

@section('content')
<div class="content">

    @if (isset($data))
    @include('admin.template.breadcrumb')
	<?php echo Form::open(array('url' => 'admin/'.$slugController.'/update/'.$data->id, 'method' => 'post', 'class' => 'ui edit-changes form')) ?>
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
				$data->default_role_id, 
				array('class' => 'multipleSelect dropdown ui normal search fluid')
			); 
			?>
		</div>
		<div class="fields">
			<div class="four wide field">
			   	<label>Aanhef</label>
				<?php echo Form::select('gender',  array(1 => 'Dhr', 2 => 'Mvr'), $data->gender, array('class' => 'multipleSelect dropdown ui normal search fluid')); ?>
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

		<div class="two fields">
			<div class="field">
				<label>Telefoonnummer</label>
				<?php echo Form::text('phone', $data->phone) ?>
			</div>

			<div class="field">
				<label>Login pogingen</label>
				<?php
				echo Form::selectRange(
					'attempts',
					0,
					10,
					$data->attempts,
					array('class' => 'multipleSelect dropdown ui normal search fluid')
				);
				?>
			</div>
		</div>
		<div class="two fields">
			<div class="field">
				<label>Geboortedatum</label>
				<?php echo Form::text('birthday_at', '', array('class' => 'bdy-datepicker', 'data-value' => $data->birthday_at)); ?>
			</div>
			<div class="field">
                <?php
                $languages = [
                    'nl' => 'NL',
                    'en' => 'EN',
                    'be' => 'BE',
                    'de' => 'DE',
                    'fr' => 'FR'
                ];
                ?>
				<label>Wil je de taal veranderen?</label>
				<select name="lang" class="ui normal fluid search dropdown notranslate">
					<option value="" selected="selected" >Language</option>
					@foreach($languages as $key => $item)
						<option value="{{$key}}" @if($key == $data->lang)selected="selected"@endif >{{$item}}</option>
					@endforeach
				</select>
			</div>
		</div>

		<div class="field">
			<label>Saldo</label>
			<div class="ui left labeled input">
			  	<div class="ui label">&euro;</div>
				<?php echo Form::text('saldo', $data->saldo) ?>
			</div>
		</div>

		<h4 class="ui dividing header no_blue">Voorkeuren</h4>

		<div class="field">
			<label>Nieuwsbrief</label>
			<?php
			$regio = array();

			foreach($cityPref->where('category_id', 9)->get() as $result) {
                $regio[$result->id] = $result->name;
			}
            $city = [];
            $cityExist = json_decode($data->city);
            if ($cityExist) {
                $city = $cityExist;
            }
			?>

			<select name="city[]" id="city" class="ui normal fluid search dropdown" multiple>
				@foreach($regio as $reg_key => $item)
					<option value="{{$reg_key}}" @if(in_array($reg_key, $city))selected="selected"@endif>{{$item}}</option>
				@endforeach
			</select>
		</div>

		<div class="two fields">
			<div class="field">
				<label>Voorkeuren</label>
				<?php
				echo Form::select('preferences[]', (isset($preference[1]) ? $preference[1] : array()), json_decode($data->preferences), array('multiple' => true, 'class' => 'multipleSelect dropdown ui normal search fluid')); 
				?>
			</div>

			<div class="field">
				<label>Duurzaamheid</label>
				<?php
				echo Form::select('sustainability[]', (isset($preference[8]) ? $preference[8] : array()), json_decode($data->sustainability), array('multiple' => true, 'class' => 'multipleSelect dropdown ui normal search fluid')); 
				?>
			</div>
		</div>

		<div class="two fields">
			<div class="field">
				<label>Keuken</label>
				<?php
				echo Form::select('kitchens[]',  (isset($preference[2]) ? $preference[2] : array()), json_decode($data->kitchens), array('multiple' => true, 'class' => 'multipleSelect dropdown ui normal search fluid')); 
				?>
			</div>		  		  

			<div class="field">
				<label>Allergie&euml;n</label>
				<?php
				echo Form::select('allergies[]', (isset($preference[3]) ? $preference[3] : array()), json_decode($data->allergies), array('multiple' => true, 'class' => 'multipleSelect dropdown ui normal search fluid')); 
				?>
			</div>		  		  	
		</div>

		<div class="two fields">
			<div class="field">
				<label>Faciliteiten</label>
				<?php
				echo Form::select('facilities[]', (isset($preference[7]) ? $preference[7] : array()), json_decode($data->facilities), array('multiple' => true, 'class' => 'multipleSelect dropdown ui normal search fluid')); 
				?>
			</div>		  		  

			<div class="field">
				<label>Kinderen</label>
				<?php
				echo Form::select('kids[]', (isset($preference[6]) ? $preference[6] : array()), json_decode($data->kids), array('multiple' => true, 'class' => 'multipleSelect dropdown ui normal search fluid')); 
				?>
			</div>		  		  	
		</div>
	  	
		<div class="two fields">
			<div class="field">
				<label>Korting</label>
				<?php
				echo Form::select('discount[]', (isset($preference[5]) ? $preference[5] : array()), json_decode($data->discount), array('multiple' => true, 'class' => 'multipleSelect dropdown ui normal search fluid')); 
				?>
			</div>		  		  

			<div class="field">
				<label>Soort</label>
				<?php
				echo Form::select('price[]', (isset($preference[4]) ? $preference[4] : array()), json_decode($data->price), array('multiple' => true, 'class' => 'multipleSelect dropdown ui normal search fluid')); 
				?>
			</div>		  		  	
		</div>

		<h4 class="ui dividing header no_blue">Wachtwoord <small>(optioneel)</small></h4>

		<div class="field">
		    <label>Wachtwoord</label>
		    <?php echo Form::password('password') ?>
		</div>

		<div class="field">
		  <label>Wachtwoord controle</label>
		  <?php echo Form::password('password_confirmation') ?>
		</div>

		 <button class="ui tiny button" type="submit"><i class="pencil icon"></i> Wijzigen</button>
		 <a href="{{ url('admin/ban/create/'.$data->id) }}" class="ui tiny red button" type="submit"><i class="ban icon"></i> Verbannen</a>
	<?php echo Form::close(); ?>
	@endif
</div>
<div class="clear"></div>
@stop
