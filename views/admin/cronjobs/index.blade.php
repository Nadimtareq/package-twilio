@extends('template.theme')

@section('content')
<div class="content">
    @include('admin.template.breadcrumb')

   	<?php echo Form::open(array('method' => 'post', 'class' => 'ui form')) ?>
   	<h4>Affiliaties</h4>
	<div class="two fields">
		<div class="field">
		   	<div class="ui slider checkbox">
			  	<input 
				  	type="checkbox" 
			  		name="affilinet_affiliate"
			  		{{ isset($settings['affilinet_affiliate']) ? 'checked="checked"' : '' }}>
			  	<label>Voeg nieuwe programma's toe van Affilinet.</label>
			</div>
		</div>

		<div class="field">
		   	<div class="ui slider checkbox">
			  	<input 
			  		type="checkbox" 
			  		name="tradetracker_affiliate"
			  		{{ isset($settings['tradetracker_affiliate']) ? 'checked="checked"' : '' }}>
			  	<label>Voeg nieuwe programma's toe van Tradetracker.</label>
			</div>
		</div>
	</div>

	<div class="two fields">
		<div class="field">
		   	<div class="ui slider checkbox">
				<input 
			  		type="checkbox" 
			  		name="zanox_affiliate"
			  		{{ isset($settings['zanox_affiliate']) ? 'checked="checked"' : '' }}>
			  	<label>Voeg nieuwe programma's toe van Zanox.</label>
			</div>
		</div>

		<div class="field">
		   	<div class="ui slider checkbox">
				<input 
			  		type="checkbox" 
			  		name="daisycon_affiliate"
			  		{{ isset($settings['daisycon_affiliate']) ? 'checked="checked"' : '' }}>
			  	<label>Voeg nieuwe programma's toe van Daisycon.</label>
			</div>
		</div>
	</div>

   	<h4>Transacties</h4>
	<div class="two fields">
		<div class="field">
		   	<div class="ui slider checkbox">
			  	<input 
				  	type="checkbox" 
			  		name="affilinet_transaction"
			  		{{ isset($settings['affilinet_transaction']) ? 'checked="checked"' : '' }}>
			  	<label>Voeg transacties toe van Affilinet.</label>
			</div>
		</div>

		<div class="field">
		   	<div class="ui slider checkbox">
			  	<input 
			  		type="checkbox" 
			  		name="tradetracker_transaction"
			  		{{ isset($settings['tradetracker_transaction']) ? 'checked="checked"' : '' }}>
			  	<label>Voeg transacties toe van Tradetracker.</label>
			</div>
		</div>
	</div>

	<div class="two fields">
		<div class="field">
		   	<div class="ui slider checkbox">
				<input 
			  		type="checkbox" 
			  		name="zanox_transaction"
			  		{{ isset($settings['zanox_transaction']) ? 'checked="checked"' : '' }}>
			  	<label>Voeg transacties toe van Zanox.</label>
			</div>
		</div>

		<div class="field">
		   	<div class="ui slider checkbox">
				<input 
			  		type="checkbox" 
			  		name="daisycon_transaction"
			  		{{ isset($settings['daisycon_transaction']) ? 'checked="checked"' : '' }}>
			  	<label>Voeg transacties toe van Daisycon.</label>
			</div>
		</div>
	</div>

	<div class="two fields">
		<div class="field">
		   	<div class="ui slider checkbox">
				<input 
			  		type="checkbox" 
			  		name="que_transaction"
			  		{{ isset($settings['que_transaction']) ? 'checked="checked"' : '' }}>
			  	<label>Keurt alle transacties (geaccepteerd, afgekeurd of geweigerd)</label>
			</div>
		</div>

		<div class="field">
		   	<div class="ui slider checkbox">
				<input 
			  		type="checkbox" 
			  		name="expired_transaction"
			  		{{ isset($settings['expired_transaction']) ? 'checked="checked"' : '' }}>
			  	<label>Laat transacties verlopen op de 90e dag</label>
			</div>
		</div>
	</div>

   	<h4>Facturen</h4>
	<div class="two fields">
		<div class="field">
		   	<div class="ui slider checkbox">
				<input 
			  		type="checkbox" 
			  		name="product_invoice"
			  		{{ isset($settings['product_invoice']) ? 'checked="checked"' : '' }}>
			  	<label>Stuur facturen naar klanten (Producten)</label>
			</div>
		</div>

		<div class="field">
		   	<div class="ui slider checkbox">
				<input 
			  		type="checkbox" 
			  		name="reservation_invoice"
			  		{{ isset($settings['reservation_invoice']) ? 'checked="checked"' : '' }}>
			  	<label>Stuur facturen naar klanten (Reservering)</label>
			</div>
		</div>
	</div>

	<div class="two fields">
		<div class="field">
		   	<div class="ui slider checkbox">
				<input 
			  		type="checkbox" 
			  		name="debit_invoice"
			  		{{ isset($settings['debit_invoice']) ? 'checked="checked"' : '' }}>
			  	<label>Maak XML aan voor incasso</label>
			</div>
		</div>

		<div class="field">
		   	<div class="ui slider checkbox">
				<input 
			  		type="checkbox" 
			  		name="reminder_invoice"
			  		{{ isset($settings['reminder_invoice']) ? 'checked="checked"' : '' }}>
			  	<label>Stuur betaal herinnering</label>
			</div>
		</div>
	</div>

   	<h4>Reserveringen</h4>
	<div class="two fields">
		<div class="field">
		   	<div class="ui slider checkbox">
				<input 
			  		type="checkbox" 
			  		name="reminder_reservation"
			  		{{ isset($settings['reminder_reservation']) ? 'checked="checked"' : '' }}>
			  	<label>Stuur klanten een herinnering voor reservering.</label>
			</div>
		</div>

		<div class="field">
		   	<div class="ui slider checkbox">
				<input 
			  		type="checkbox" 
			  		name="reminder_review"
			  		{{ isset($settings['reminder_review']) ? 'checked="checked"' : '' }}>
			  	<label>Stuur klanten een herinnering om een recensie te plaatsen.</label>
			</div>
		</div>
	</div>

	<div class="two fields">
		<div class="field">
		   	<div class="ui slider checkbox">
				<input 
			  		type="checkbox" 
			  		name="today_reservation"
			  		{{ isset($settings['today_reservation']) ? 'checked="checked"' : '' }}>
			  	<label>Stuur de admin reserveringen na sluitingstijd van vandaag</label>
			</div>
		</div>

		<div class="field">
		   	<div class="ui slider checkbox">
				<input 
			  		type="checkbox" 
			  		name="pay_reservation"
			  		{{ isset($settings['pay_reservation']) ? 'checked="checked"' : '' }}>
			  	<label>Zet reserveringen als betaald na reservering datum/tijd</label>
			</div>
		</div>
	</div>

   	<h4>Overige</h4>
	<div class="two fields">
		<div class="field">
		   	<div class="ui slider checkbox">
				<input 
			  		type="checkbox" 
			  		name="wifi_guest"
			  		{{ isset($settings['wifi_guest']) ? 'checked="checked"' : '' }}>
			  	<label>Voeg gasten toe via wifi hotspot</label>
			</div>
		</div>

		<div class="field">
		   	<div class="ui slider checkbox">
				<input 
			  		type="checkbox" 
			  		name="sitemap_other"
			  		{{ isset($settings['sitemap_other']) ? 'checked="checked"' : '' }}>
			  	<label>Maak een sitemap aan</label>
			</div>
		</div>
	</div>

	<div class="two fields">
		<div class="field">
		   	<div class="ui slider checkbox">
				<input 
			  		type="checkbox" 
			  		name="expired_barcode"
			  		{{ isset($settings['expired_barcode']) ? 'checked="checked"' : '' }}>
			  	<label>Verwijder verlopen barcodes na een jaar</label>
			</div>
		</div>

		<div class="field">
		   	<div class="ui slider checkbox">
				<input 
			  		type="checkbox" 
			  		name="validate_payment"
			  		{{ isset($settings['validate_payment']) ? 'checked="checked"' : '' }}>
			  	<label>Keur mollie betalingen</label>
			</div>
		</div>
	</div>

	<button class="ui tiny button" type="submit"><i class="plus icon"></i> Opslaan</button>
   	<?php echo Form::close() ?>
</div>
@stop