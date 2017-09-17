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
    @if ($data != '')
		@include('admin.template.breadcrumb')

		<?php echo Form::open(array( 'method' => 'post', 'class' => 'ui edit-changes form')) ?>
			<?php echo Form::hidden('city', (Request::has('city') ? Request::input('city') : '')) ?>

			<div class="left section">
				<div class="field">
					<label>Naam</label>
					<?php echo Form::text('name', $data->name) ?>
				</div>

				@if ($userAdmin OR $userCallcenter)
				<div class="field">
					<label>Notitie</label>
					<?php echo Form::textarea('comment', $data->comment) ?>
				</div>
				@endif

				<?php echo Form::hidden('date', date('Y-m-d')); ?>
				<?php echo Form::hidden('time', date('H:i')); ?>
				
				<h4>Terug bellen op</h4>
				<div class="two fields">
					<div class="field">
						<label>Datum</label>
						<div class="ui icon input">
							<?php echo Form::text('callback_date', date('Y-m-d'), array('class' => 'datepicker', 'placeholder' => 'Selecteer een datum')); ?>
							<i class="calendar icon"></i>
						</div>
					</div>	
					
					<div class="field">
						<label>Tijd</label>
						<div class="ui icon input">
							<?php echo Form::text('callback_time', date('H:i'), array('class' => 'timepicker2', 'placeholder' => 'Selecteer een tijd')); ?>
							<i class="clock icon"></i>
						</div>
					</div>	
				</div>

				<h4>Contactgegevens</h4>
				<div class="three fields">
					<div class="field">
						<label>Adres</label>
						<?php echo Form::text('address', $data->address) ?>
					</div>

					<div class="field">
						<label>Postcode</label>
						<?php echo Form::text('zipcode', $data->zipcode) ?>
					</div>

					<div class="field">
						<label>Woonplaats</label>
						<?php echo Form::text('city', $data->city) ?>
					</div>
				</div>

				<div class="three fields">
					<div class="field">
						<label>E-mailadres</label>
						<?php echo Form::text('email', $data->email) ?>
					</div>

					<div class="field">
						<label>Telefoonnummer</label>
						<?php echo Form::text('phone', App\Models\CompanyCallcenter::formatPhoneNumber($data->phone)); ?>
					</div>

					<div class="field">
						<label>Bellen</label>
						<a class="ui icon blue fluid button" href="tel:<?php echo App\Models\CompanyCallcenter::formatPhoneNumber($data->phone); ?>">
							<i class="skype icon"></i> 
							Bellen
						</a><br />
					</div>
				</div>

				<h4>Administratie</h4>
				<div class="three fields">
					<div class="field">
					    <label>Contactpersoon</label>
					    <?php echo Form::text('contact_name', $data->contact_name) ?>
					</div>

					<div class="field">
					    <label>Telefoon (contactpersoon)</label>
					    <?php echo Form::text('contact_phone', $data->contact_phone) ?>
					</div>

					<div class="field">
					    <label>Emailadres (contactpersoon)</label>
					    <?php echo Form::text('contact_email', $data->contact_email) ?>
					</div>
				</div>

				<div class="three fields">
					<div class="field">
					    <label>Functie</label>
					    <?php echo Form::text('contact_role', $data->contact_role) ?>
					</div>

					<div class="field">
					    <label>KVK</label>
					    <?php echo Form::text('kvk', $data->kvk) ?>
					</div>

					<div class="field">
					    <label>BTW</label>
					    <?php echo Form::text('btw', $data->btw) ?>
					</div>
				</div>

				<div class="three fields">
					<div class="field">
					    <label>IBAN Nummer</label>
					    <?php echo Form::text('financial_iban', $data->financial_iban) ?>
					</div>

					<div class="field">
					    <label>IBAN tnv</label>
					    <?php echo Form::text('financial_iban_tnv', $data->financial_iban_tnv) ?>
					</div>

					<div class="field">
					    <label>E-mailadres (administratie)</label>
					    <?php echo Form::text('financial_email', $data->financial_email) ?>
					</div>
				</div>

				<div class="two fields">
					<div class="field">
						<label>Regio</label>
						<?php
						$regio = array();
						$regio[''] = 'Regio';

						foreach($preference->where('category_id', 9)->get() as $prefData) {
							$regio[$prefData->id] = $prefData->name;
						}

						echo Form::select('regio', $regio, $data->regio, array('class' => 'ui normal search dropdown'));
						?>
					</div>
				</div>

				<h4>Voorkeuren</h4>

				<div class="two fields">
					<div class="field">
						<label>Voorkeuren</label>
						<?php
						$preferences     = array();
						$preferences[''] = 'Voorkeuren';

						foreach($preference->where('category_id', 1)->get() as $prefData)
						{
							$preferences[str_slug($prefData->name)] = $prefData->name;
						}

						echo Form::select('preferences[]', $preferences, json_decode($data->preferences), array('multiple' => true, 'class' => 'ui normal fluid search dropdown'));
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

						echo Form::select('sustainability[]', $sustainability, json_decode($data->sustainability), array('multiple' => true, 'class' => 'ui normal fluid search dropdown'));
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

						echo Form::select('kitchens[]', $kitchens, json_decode($data->kitchens), array('multiple' => true, 'class' => 'ui normal fluid search dropdown'));
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

						echo Form::select('allergies[]', $allergies, json_decode($data->allergies), array('multiple' => true, 'class' => 'ui normal fluid search dropdown'));
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

						echo Form::select('facilities[]', $facilities, json_decode($data->facilities), array('multiple' => true, 'class' => 'ui normal fluid search dropdown'));
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

						echo Form::select('kids[]', $kids, json_decode($data->kids), array('multiple' => true, 'class' => 'ui normal fluid search dropdown'));
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

						echo Form::select('discount[]', $discount, is_array(json_decode($data->discount)) != '' ? json_decode($data->discount) : 'NULL', array('multiple' => true, 'class' => 'ui normal fluid search dropdown'));
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

						echo Form::select('price[]', $price, json_decode($data->price), array('multiple' => true, 'class' => 'ui normal search dropdown'));
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

						echo Form::select('regio', $regio, $data->regio, array('class' => 'ui normal search dropdown'));
						?>
					</div>

					<div class="field">
						<label>Kortingsdagen</label>
						<?php 
						$discountDaysArray = array(
							'multiple' => true, 
							'id' => 'discountDays', 
							'class' => 'ui normal search dropdown '.(count(json_decode($data->discount)) == 0 ? 'disabled' : '')
						);
						
						if (count(json_decode($data->discount)) == 0) {
							$discountDaysArray['disabled'] = 'disabled';
						}

						echo Form::select('days[]', Config::get('preferences.days'), json_decode($data->days), $discountDaysArray);
						?>
					</div>
				</div>
				
				<button class="ui green button" name="score" value="1" type="submit"><i class="smile icon"></i> Won</button>
				<button class="ui red button" name="score"  value="2" type="submit"><i class="frown icon"></i> Lose</button>
			</div>

		<?php echo Form::close(); ?>

		<div class="clear"></div>
	@else
		<div class="ui error message">Het formulier met record ID <strong>{{ Request::segment(4) }}</strong> is niet gevonden.</div>
	@endif
</div>
<div class="clear"></div>
@stop
