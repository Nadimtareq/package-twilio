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

	<?php echo Form::open(array('method' => 'post', 'class' => 'ui edit-changes form')) ?>
		<div class="left section">
			<div class="field">
			    <label>Naam</label>
			    <?php echo Form::text('name') ?>
			</div>
			
			@if ($userAdmin OR $userCallcenter)
			<div class="field">
				<label>Notitie</label>
				<?php echo Form::textarea('comment') ?>
			</div>
			@endif

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
				    <label>E-mailadres</label>
				    <?php echo Form::text('email') ?>
				 </div>

				<div class="field">
				    <label>Telefoonnummer</label>
				    <?php echo Form::text('phone') ?>
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

			<h4>Voorkeuren</h4>

				<div class="two fields">
					<div class="field">
						<label>Voorkeuren</label>
						<?php
						$preferences     = array();
						$preferences[''] = 'Voorkeuren';

						foreach($preference->where('category_id', 1)->get() as $prefData) {
							$preferences[str_slug($prefData->name)] = $prefData->name;
						}

						echo Form::select('preferences[]', $preferences, '', array('multiple' => true, 'class' => 'ui normal fluid search dropdown'));
						?>
					</div>

					<div class="field">
						<label>Duurzaamheid</label>
						<?php
						$sustainability = array();
						$sustainability[''] = 'Duurzaamheid';

						foreach($preference->where('category_id', 8)->get() as $prefData)
						{
							$sustainability[str_slug($prefData->name)] = $prefData->name;
						}

						echo Form::select('sustainability[]', $sustainability, '', array('multiple' => true, 'class' => 'ui normal fluid search dropdown'));
						?>
					</div>
				</div>

				<div class="two fields">
					<div class="field">
						<label>Keuken</label>
						<?php
						$kitchens = array();
						$kitchens[''] = 'Keuken';

						foreach($preference->where('category_id', 2)->get() as $prefData)
						{
							$kitchens[str_slug($prefData->name)] = $prefData->name;
						}

						echo Form::select('kitchens[]', $kitchens, '', array('multiple' => true, 'class' => 'ui normal fluid search dropdown'));
						?>
					</div>

					<div class="field">
						<label>Allergie&euml;n</label>
						<?php
						$allergies = array();
						$allergies[''] = 'Allergie&euml;n';

						foreach($preference->where('category_id', 3)->get() as $prefData)
						{
							$allergies[str_slug($prefData->name)] = $prefData->name;
						}

						echo Form::select('allergies[]', $allergies, '', array('multiple' => true, 'class' => 'ui normal fluid search dropdown'));
						?>
					</div>
				</div>

				<div class="two fields">
					<div class="field">
						<label>Faciliteiten</label>
						<?php
						$facilities = array();
						$facilities[''] = 'Faciliteiten';

						foreach($preference->where('category_id', 7)->get() as $prefData)
						{
							$facilities[str_slug($prefData->name)] = $prefData->name;
						}

						echo Form::select('facilities[]', $facilities, '', array('multiple' => true, 'class' => 'ui normal fluid search dropdown'));
						?>
					</div>

					<div class="field">
						<label>(klant van Wifi Online)</label>
						<?php
						$kids = array();
						$kids[''] = 'Wi-Fi';

						foreach($preference->where('category_id', 6)->get() as $prefData)
						{
							$kids[str_slug($prefData->name)] = $prefData->name;
						}

						echo Form::select('kids[]', $kids, '', array('multiple' => true, 'class' => 'ui normal fluid search dropdown'));
						?>
					</div>
				</div>

				<div class="two fields">
					@if($userAdmin)
					<div class="field">
						<label>Korting</label>
						<?php
						$discount = array();
						$discount['NULL'] = 'Geen korting';

						foreach($preference->where('category_id', 5)->get() as $prefData) {
							$discount[rawurlencode($prefData->name)] = $prefData->name;
						}

						echo Form::select('discount[]', $discount, '', array('multiple' => true, 'class' => 'ui normal fluid search dropdown'));
						?>
					</div>
					@endif

					<div class="field">
						<label>Soort</label>
						<?php
						$price = array();
						$price[''] = 'Soort';

						foreach($preference->where('category_id', 4)->get() as $prefData)
						{
							$price[str_slug($prefData->name)] = $prefData->name;
						}

						echo Form::select('price[]', $price, '', array('multiple' => true, 'class' => 'ui normal search dropdown'));
						?>
					</div>
				</div>

				<div class="two fields">
					<div class="field">
						<label>Regio</label>
						<?php
						$regio = array();
						$regio[''] = 'Regio';

						foreach($preference->where('category_id', 9)->get() as $prefData)
						{
							$regio[$prefData->id] = $prefData->name;
						}

						echo Form::select('regio', $regio, '', array('class' => 'ui normal search dropdown'));
						?>
					</div>

					<div class="field">
						<label>Kortingsdagen</label>
						<?php 
						$discountDaysArray = array(
							'multiple' => true, 
							'id' => 'discountDays', 
							'class' => 'ui normal search dropdown'
						);

						echo Form::select('days[]', Config::get('preferences.days'), '', $discountDaysArray);
						?>
					</div>
				</div>
			<button class="ui tiny button" type="submit"><i class="plus icon"></i> Aanmaken</button>
		</div>
	<?php echo Form::close(); ?>
</div>
<div class="clear"></div>
@stop
