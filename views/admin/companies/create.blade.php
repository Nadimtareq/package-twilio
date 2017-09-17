@extends('template.theme')

@inject('preference', 'App\Models\Preference')

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

	<?php echo Form::open(array('url' => 'admin/'.$slugController.'/create', 'method' => 'post', 'class' => 'ui form', 'files' => TRUE)) ?>
	<div class="left section">
		<div class="field">
			<div class="ui checkbox">
				<?php echo Form::checkbox('no_show', 1) ?>
				<label>Niet tonen op homepagina en zoeken</label>
			</div>
		</div>

		<div class="field">
			<div class="ui checkbox">
				<?php echo Form::checkbox('start_invoice', 1) ?>
				<label>Opstartfactuur</label>
			</div>
		</div>

		<div class="field">
			<label>Naam</label>
			<?php echo Form::text('name') ?>
		</div>

		<div class="ui top attached tabular menu">
			<a class="active item" data-tab="first">Over ons</a>
			<a class="item" data-tab="second">Menu</a>
			<a class="item" data-tab="third">Details</a>
		</div>

		<div class="ui bottom attached active tab segment" data-tab="first">
			
			<div class="field">
				<label>Korte omschrijving</label>
				<?php echo Form::textarea('description', '', array('rows' =>'2')); ?>
			</div>

			<div class="field">
				<label>Over ons</label>
				<?php echo Form::textarea('about_us', '', array('class' => 'editor')) ?>
			</div>
		</div>
		<div class="ui bottom attached tab segment" data-tab="second">
			<div class="field">
				<label>Menu</label>
				<?php echo Form::textarea('menu', '', array('class' => 'editor')) ?>
			</div>
		</div>
		<div class="ui bottom attached tab segment" data-tab="third">
			<div class="field">
				<label>Details</label>
				<?php echo Form::textarea('details', '', array('class' => 'editor')) ?>
			</div>
		</div>
		<div class="ui bottom attached tab segment" data-tab="four">
			<div class="field">
				<label>Contact details</label>
				<?php echo Form::textarea('contact', '', array('class' => 'editor')) ?>
			</div>
		</div><br />

		<div class="field">
			<label>Eigenaar</label>
			Kies een gebruiker uit die eigenaar is van dit bedrijf.<br /><br />

			<div class="field">
				<div class="ui checkbox">
					<?php echo Form::checkbox('new_user', 1) ?>
					<label>Een nieuwe gebruiker</label>
				</div>
			</div>

			<div class="ui horizontal divider">OF</div>

			<strong>Bestaande gebruiker</strong><br />
			<div id="companiesOwnersSearch" class="ui search">
				<div class="ui icon fluid input">
					<input class="prompt" type="text" placeholder="Typ een naam in..">
					<i class="search icon"></i>
				</div>

				<div class="results"></div>
			</div>
		</div>

		<h4>Contactgegevens</h4>
		<div class="three fields">
			<div class="field">
				<label>Adres</label>
				<?php echo Form::text('address') ?>
			</div>

			<div class="field">
				<label>Postcode</label>
				<?php echo Form::text('zipcode') ?>
			</div>
			<div class="field">
				<label>Woonplaats</label>
				<?php echo Form::text('city', '') ?>
			</div>
		</div>

		<div class="three fields">
			<div class="field">
				<label>E-mailadres (Reserveren)</label>
				<?php echo Form::text('email') ?>
			</div>

			<div class="field">
				<label>Telefoonnummer (Reserveren)</label>
				<?php echo Form::text('phone') ?>
			</div>

			<div class="field">
				<label>Website</label>
				<?php echo Form::text('website', '') ?>
			</div>
		</div>

		<h4>Administratie</h4>
		<div class="three fields">
			<div class="field">
				<label>Contactpersoon</label>
				<?php echo Form::text('contact_name') ?>
			</div>

			<div class="field">
				<label>Telefoon (contactpersoon)</label>
				<?php echo Form::text('contact_phone') ?>
			</div>

			<div class="field">
				<label>Emailadres (contactpersoon)</label>
				<?php echo Form::text('contact_email') ?>
			</div>
		</div>

		<div class="three fields">
			<div class="field">
				<label>Functie</label>
				<?php echo Form::text('contact_role') ?>
			</div>

			<div class="field">
				<label>KVK</label>
				<?php echo Form::text('kvk') ?>
			</div>

			<div class="field">
				<label>BTW</label>
				<?php echo Form::text('btw') ?>
			</div>
		</div>

		<div class="three fields">
			<div class="field">
				<label>IBAN Nummer</label>
				<?php echo Form::text('financial_iban') ?>
			</div>

			<div class="field">
				<label>IBAN tnv</label>
				<?php echo Form::text('financial_iban_tnv') ?>
			</div>

			<div class="field">
				<label>E-mailadres (administratie)</label>
				<?php echo Form::text('financial_email') ?>
			</div>
		</div>

		<h4 class="ui dividing header">Voorkeuren</h4>

		<div class="two fields">
			<div class="field">
				<label>Voorkeuren</label>
				<?php
				$preferences     = array();
				$preferences[''] = 'Voorkeuren';

				foreach ($preference->where('category_id', 1)->get() as $data) {
					$preferences[str_slug($data->name)] = $data->name;
				}

				echo Form::select('preferences[]', $preferences, null, array('multiple' => true, 'class' => 'ui normal fluid search dropdown'));
				?>
			</div>

			<div class="field">
				<label>Duurzaamheid</label>
				<?php
				$sustainability = array();
				$sustainability[''] = 'Duurzaamheid';

				foreach($preference->where('category_id', 8)->get() as $data) {
					$sustainability[str_slug($data->name)] = $data->name;
				}

				echo Form::select('sustainability[]', $sustainability, null, array('multiple' => true, 'class' => 'ui normal fluid search dropdown'));
				?>
			</div>
		</div>

		<div class="two fields">
			<div class="field">
				<label>Keuken</label>
				<?php
				$kitchens = array();
				$kitchens[''] = 'Keuken';

				foreach($preference->where('category_id', 2)->get() as $data) {
					$kitchens[str_slug($data->name)] = $data->name;
				}

				echo Form::select('kitchens[]', $kitchens, null, array('multiple' => true, 'class' => 'ui normal fluid search dropdown'));
				?>
			</div>

			<div class="field">
				<label>Allergie&euml;n</label>
				<?php
				$allergies = array();
				$allergies[''] = 'Allergie&euml;n';

				foreach($preference->where('category_id', 3)->get() as $data) {
					$allergies[str_slug($data->name)] = $data->name;
				}

				echo Form::select('allergies[]', $allergies, null, array('multiple' => true, 'class' => 'ui normal fluid search dropdown'));
				?>
			</div>
		</div>

		<div class="two fields">
			<div class="field">
				<label>Faciliteiten</label>
				<?php
				$facilities = array();
				$facilities[''] = 'Faciliteiten';

				foreach($preference->where('category_id', 7)->get() as $data) {
					$facilities[str_slug($data->name)] = $data->name;
				}

				echo Form::select('facilities[]', $facilities, null, array('multiple' => true, 'class' => 'ui normal fluid search dropdown'));
				?>
			</div>

			<div class="field">
				<label>Wi-Fi</label>
				<?php
				$kids = array();
				$kids[''] = 'Wi-Fi';

				foreach($preference->where('category_id', 6)->get() as $data) {
					$kids[str_slug($data->name)] = $data->name;
				}

				echo Form::select('kids[]', $kids, null, array('multiple' => true, 'class' => 'ui normal fluid search dropdown'));
				?>
			</div>
		</div>

		<div class="two fields">
			<div class="field">
				<label>Korting</label>
				<?php
				$discount = array();
				$discount[''] = 'Korting';

				foreach($preference->where('category_id', 5)->get() as $data) {
					$discount[rawurlencode($data->name)] = $data->name;
				}

				echo Form::select('discount[]', $discount, null, array('multiple' => true, 'class' => 'ui normal fluid search dropdown'));
				?>
			</div>

			<div class="field">
				<label>Soort</label>
				<?php
				$price = array();
				$price[''] = 'Soort';

				foreach($preference->where('category_id', 4)->get() as $data) {
					$price[str_slug($data->name)] = $data->name;
				}

				echo Form::select('price[]', $price, null, array('multiple' => true, 'class' => 'ui normal search dropdown'));
				?>
			</div>
		</div>

		<div class="two fields">
			<div class="field">
				<label>Regio</label>
				<?php
				$regio = array();
				$regio[''] = 'Regio';

				foreach($preference->where('category_id', 9)->get() as $data) {
					$regio[$data->id] = $data->name;
				}

				echo Form::select('regio[]', $regio, null, array('class' => 'ui normal search dropdown'));
				?>
			</div>

			<div class="field">
				<label>Dagen</label>
				<?php echo Form::select('days[]', Config::get('preferences.days'), '', array('multiple' => true, 'id' => 'day', 'class' => 'multipleSelect')) ?>
			</div>

			
		</div>
		<div class="field">
			<label>Prijs per persoon</label>
			<?php echo Form::text('price_per_guest') ?>
		</div>
		<button class="ui tiny button" type="submit"><i class="plus icon"></i> Aanmaken</button>


	</div>


	<div class="right section" style="padding-left: 20px;">
		@if($userAdmin)
		<div class="field">
			<label>PDF uploaden</label>
			<?php echo Form::file('pdf[]', array('multiple' => true)); ?>
		</div>
		@endif

		<div class="field">
			<label>Afbeeldingen (maximaal 6)</label>
			Middels u muis kunt u ze in de gewenste volgorde slepen.<br /><br />
			<?php echo Form::file('images[]', array('multiple' => true, 'class' => 'multi with-preview')); ?>
		</div>
	</div>
	<?php echo Form::close(); ?>
</div>
<div class="clear"></div>
@stop
