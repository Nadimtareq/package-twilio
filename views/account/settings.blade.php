@extends('template.theme')

@inject('preference', 'App\Models\Preference')

@section('scripts')
<script type="text/javascript">
$(document).ready(function() {
	@if(session('success_email_message'))
		swal({ html:true, title: "Bedankt!", text: '{{ session('success_email_message') }}', type: "success", confirmButtonText: "OK" });
	@elseif(session('success_message'))
		swal({ html:true, title: "Bedankt!", text: '{{ session('success_message') }}', type: "success", confirmButtonText: "OK" });
	@endif

	$('#removeButton').click(function() {
  		swal({
			title: "Weet u het zeker?",
			text: "Weet u zeker dat u definitief uw account wil verwijderen?",
			type: "warning",
                        html:true,
			showCancelButton: true,
			confirmButtonColor: "#DD6B55",
			cancelButtonText: "Nee",
			confirmButtonText: "Ja, ik weet het zeker!",
		}, function() {
			$('#deleteForm').submit();
			return true;
		});

		return false;
	});
});
</script>
@stop

@section('content')
<div class="container">
	<div class="ui breadcrumb">
		<a href="{{ url('/') }}" class="section">Home</a>
		<i class="right chevron icon divider"></i>
		<a href="#" class="sidebar open" data-activates="slide-out">Menu</a>
	    <i class="right chevron icon divider"></i>
		<div class="active section">Account gegevens wijzigen</div>
	</div>
	<div class="ui divider"></div>

	<?php echo Form::open(array('id' => 'formList', 'url' => 'account', 'method' => 'post', 'class' => 'ui form')) ?>
		<input id="actionMan" type="hidden" name="action">

		<div class="fields">
			<div class="four wide field">
				<label>Aanhef</label>
				<?php echo Form::select('gender',  array(1 => 'Dhr', 2 => 'Mvr'), Sentinel::getUser()->gender, array('class' => 'ui normal fluid dropdown')); ?>
			</div>

			<div class="twelve wide field">
				<label>Naam</label>
				<?php echo Form::text('name', Sentinel::getUser()->name) ?>
			</div>
		</div>

		<div class="field">
			<label>E-mailadres</label>
			<?php echo Form::text('email', Sentinel::getUser()->email) ?>
		</div>

		<div class="field">
			<label>Telefoonnummer</label>
			<?php echo Form::text('phone', Sentinel::getUser()->phone) ?>
		</div>

		<div class="field">
			<label>Geboortedatum</label>
			<?php echo Form::text('birthday_at', '', array('class' => 'bdy-datepicker', 'data-value' => Sentinel::getUser()->birthday_at)); ?>
		</div>

		<h4 class="ui dividing">Wachtwoord <small>(optioneel)</small></h4>

		<div class="field">
			<label>Wachtwoord</label>
			<?php echo Form::password('password') ?>
		</div>

		<div class="field">
		  <label>Wachtwoord controle</label>
		  <?php echo Form::password('password_confirmation') ?>
		</div>

		<h4 class="ui dividing" id="preferences">Voorkeuren</h4>
		Geef uw voorkeuren aan, en ons systeem filtert hierop uw zoekresultaat.<br /><br />

		<div class="field">
			<label>Nieuwsbrief</label>
			<?php
			$regio = array();
			$regio[''] = 'Regio';

			foreach($preference->where('category_id', 9)->get() as $data) {
				$regio[$data->id] = $data->name;
			}
			$city = [];
			$cityExist = json_decode(Sentinel::getUser()->city,1);
			if ($cityExist) {
                $city = $cityExist;
            }
			?>
            <select name="regio[]" id="regio" class="ui normal fluid search dropdown" multiple="multiple">
                @foreach($regio as $reg_key => $item)
                    <option value="{{$reg_key}}" @if(in_array($reg_key,$city))selected="selected"@endif>{{$item}}</option>
				@endforeach
			</select>
		</div>

		<div class="two fields">
			<div class="field">
				<label>Voorkeuren</label>
				<?php
				$preferences     = array();
				$preferences[''] = 'Voorkeuren';

				foreach($preference->where('category_id', 1)->get() as $data)
				{
					$preferences[str_slug($data->name)] = $data->name;
				}

				echo Form::select('preferences[]', $preferences, json_decode(Sentinel::getUser()->preferences), array('multiple' => true, 'class' => 'ui normal fluid search dropdown'));
				?>
			</div>

			<div class="field">
				<label>Duurzaamheid</label>
				<?php
				$sustainability = array();
				$sustainability[''] = 'Duurzaamheid';

				foreach($preference->where('category_id', 8)->get() as $data)
				{
					$sustainability[str_slug($data->name)] = $data->name;
				}

				echo Form::select('sustainability[]', $sustainability, json_decode(Sentinel::getUser()->sustainability), array('multiple' => true, 'class' => 'ui normal fluid search dropdown'));
				?>
			</div>
		</div>

		<div class="two fields">
			<div class="field">
				<label>Keuken</label>
				<?php
				$kitchens = array();
				$kitchens[''] = 'Keuken';

				foreach($preference->where('category_id', 2)->get() as $data)
				{
					$kitchens[str_slug($data->name)] = $data->name;
				}

				echo Form::select('kitchens[]', $kitchens, json_decode(Sentinel::getUser()->kitchens), array('multiple' => true, 'class' => 'ui normal fluid search dropdown'));
				?>
			</div>

			<div class="field">
				<label>Allergie&euml;n</label>
				<?php
				$allergies = array();
				$allergies[''] = 'Allergie&euml;n';

				foreach($preference->where('category_id', 3)->get() as $data)
				{
					$allergies[str_slug($data->name)] = $data->name;
				}

				echo Form::select('allergies[]', $allergies, json_decode(Sentinel::getUser()->allergies), array('multiple' => true, 'class' => 'ui normal fluid search dropdown'));
				?>
			</div>
		</div>

		<div class="two fields">
			<div class="field">
				<label>Faciliteiten</label>
				<?php
				$facilities = array();
				$facilities[''] = 'Faciliteiten';

				foreach($preference->where('category_id', 7)->get() as $data)
				{
					$facilities[str_slug($data->name)] = $data->name;
				}

				echo Form::select('facilities[]', $facilities, json_decode(Sentinel::getUser()->facilities), array('multiple' => true, 'class' => 'ui normal fluid search dropdown'));
				?>
			</div>

			<div class="field">
				<label>Personen</label>
				 <div class="ui normal compact selection dropdown ">
					<input type="hidden" name="kids" value="{{ Sentinel::getUser()->kids }}">

					<div class="default text">Personen</div>
					<i class="dropdown icon"></i>

					<div class="menu">
						@for($i = 1; $i <= 10; $i++)
							<div class="item" data-value="<?php echo $i; ?>"><?php echo $i; ?> <?php echo $i == 1 ? 'persoon' : 'personen'; ?></div>
						@endfor
					</div>
				</div>
			</div>
		</div>

		<div class="two fields">
			<div class="field">
				<label>Korting</label>
				<?php
				$discount = array();
				$discount[''] = 'Korting';

				foreach ($preference->where('category_id', 5)->get() as $data) {
					$discount[rawurlencode($data->name)] = $data->name;
				}

				echo Form::select('discount[]', $discount, json_decode(Sentinel::getUser()->discount), array('multiple' => true, 'class' => 'ui normal fluid search dropdown'));
				?>
			</div>
			<div class="field">
				<label>Soort</label>
				<?php
				$price = array();
				$price[''] = 'Soort';

				foreach ($preference->where('category_id', 4)->get() as $data) {
					$price[str_slug($data->name)] = $data->name;
				}

				echo Form::select('price[]', $price, json_decode(Sentinel::getUser()->price), array('multiple' => true, 'class' => 'ui normal fluid search dropdown'));
				?>
			</div>
		</div>

		<div class="two fields">
			<div class="field">
                <?php
                $newsletter = [
                    0 => 'nee',
                    1 => 'ja'
                ];
                ?>
				<label>Wilt u de nieuwsbrief?</label>
				<select name="letter" class="ui normal fluid search dropdown">
					@foreach($newsletter as $key => $item)
						<option value="{{$key}}" @if($key == Sentinel::getUser()->newsletter)selected="selected"@endif>{{$item}}</option>
					@endforeach
				</select>
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
						<option value="{{$key}}" @if($key == Sentinel::getUser()->lang)selected="selected"@endif >{{$item}}</option>
					@endforeach
				</select>
			</div>
		</div>

		<div class="field">
			<div class="ui checkbox">
				<?php
				echo Form::checkbox('newsletter', 1);
				?>
				<label>Ik meld mij af voor de Uwvoordeelpas.nl nieuwsbrief</label>
			</div>
		</div>

		<button class="ui button" type="submit"><i class="pencil icon"></i> Wijzigen</button>
	<?php echo Form::close(); ?><br /><br />

	<?php echo Form::open(array('id' => 'deleteForm', 'url' => 'account/delete', 'method' => 'post')); ?>
		<button id="removeButton" class="ui red button" name="delete" value="1" type="button">
			<i class="remove icon"></i> Verwijder account
		</button><br /><br/>
	<?php echo Form::close(); ?>
</div>
@stop
