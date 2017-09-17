@extends('template.theme')

@inject('preference', 'App\Models\Preference')

@section('scripts')
	<script>
	$('#products').repeater({
		btnAddClass: 'r-btnAdd',
		btnRemoveClass: 'r-btnRemove',
		groupClass: 'r-group',
		minItems: 1,
		maxItems: 0,
		startingIndex: 0,
		reindexOnDelete: true,
		repeatMode: 'append',
		animation: null,
		animationSpeed: 400,
		animationEasing: 'swing',
		clearValues: true
	});
	</script>

	<script type="text/javascript">
		$(document).ready(function() {
		    closeBrowser();
		    $('.credit.dropdown').dropdown({
			    onChange: function(value, text, $selectedItem) {

			    	if (value == 'credit') { 
						$('input[name="invoice_number"]').val(function(i,val) { 
						    return val + '-credit';
						});
					} else {
						$('input[name="invoice_number"]').val(function(i,val) { 
						    return val.replace('-credit', '');
						});
					}
				}
			});
		});
	</script>
@stop

@section('content')
<div class="content">
    @include('admin.template.breadcrumb')

    <div class="ui icon info message">
	  	<i class="info icon"></i>

	  	<div class="content">
	    	<div class="header">
     		 	OPGELET!
    		</div>
    		<p>
    			<h5>Voorbeeld bij reserveringen facturen:</h5>
    			Startdatum: 18-07-2016<br />
    			Periode: Tweewekelijks<br />
    			Begint met reserveringen van 04-07-2016 tot 17-06-2016<br />
    		</p>
	  	</div>
	</div>
	
	<?php echo Form::open(array('method' => 'post', 'class' => 'ui edit-changes form')) ?>
	<div class="two fields">
		<div class="field">
			<label>Factuurnummer</label>
		   	<?php
		   	echo Form::text(
		   		'invoice_number',
		   		((isset($getLastId->invoice_number) ? $getLastId->invoice_number : date('Y').'00') + 1)
		   	); 
		   	?>
		</div>

		<div class="field">
			<label>Bedrijf</label>
			<?php 
			echo Form::select(
				'company',  
				array_add($companies, '', 'Kies een bedrijf'),
				'', 
				array(
					'id' => 'getServicesCompany',
					'class' => 'ui normal search dropdown'
				)
			); 
			?>
		</div>
	</div>

	<div class="two fields">
		<div class="field">
			  <label>Startdatum</label>
			 <?php 
			 echo Form::text(
			 	'start_date',
			 	'',
			 	array(
			 		'class' => 'datepicker', 
			 		'placeholder' => 'Selecteer een datum'
			 	)
			 ); ?>
		</div>

		<div id="periodField" class="field">
			<label>Perodiek</label>
			<?php 
			echo Form::select(
				'period',  
				array(
					0 => 'Eenmalig', 
					7 => 'Wekelijks',
					14 => 'Tweewekelijks',
					21 => 'Driewekelijks',
					28 => 'Maandelijks',
				), 
				0, 
				array(
					'id' => 'period',
					'class' => 'ui normal periodDropdown dropdown'
				)
			); 
			?>
		</div>
	</div>

	<div class="two fields">
		<div class="field">
			<label>Soort factuur</label>
			<?php 
			echo Form::select(
				'type',  
				array(
					'products' => 'Dienst',
					'reservation' => 'Reservering'
				),
				'products', 
				array(
					'id' => 'typeInvoice',
					'class' => 'ui normal dropdown'
				)
			); 
			?>
		</div>

		<div class="field">
			<label>Debit / Credit</label>
			<?php 
			echo Form::select(
				'debit_credit',  
				array(
					'debit' => 'Debit',
					'credit' => 'Credit',
				),
				'debit', 
				array(
					'class' => 'ui normal credit dropdown'
				)
			); 
			?>
		</div>
	</div>

	<div class="two fields">
		<div class="field">
			<label>Betaal mthode</label>
			<?php 
			echo Form::select(
				'payment_method',  
				array(
					'ideal' => 'iDeal',
					'directdebit' => 'Incasso'
				),
				'ideal', 
				array(
					'class' => 'ui normal dropdown'
				)
			); 
			?>
		</div>
	</div>

	<div class="ui divider"></div>

	<div id="productsMessage" class="ui info message">
		Selecteer eerst een bedrijf om een dienst toe te voegen
	</div>

	<div id="products" style="display: none;">
		<div id="productsTable" class="r-group six fields">
			<div class="four wide field">
				<label for="vehicle_0_name" data-pattern-text="Type">Dienst</label>
				<?php 
				echo Form::select(
					'products[0][service]',
					array(
						0 => 'Kies een dienst'
					),
					0,
					array(
						'class' => 'ui normal fluid dropdown product-services',
						'id' => 'products_0_service',
						'data-pattern-name' => 'products[++][service]',
						'data-pattern-id' => 'products++_service'
					)
				);
				?>
			</div>

			<div class="six wide field">
				<label for="vehicle_0_name" data-pattern-text="Commissie">Omschrijving</label>
				<?php
				echo Form::text(
					'products[0][description]', 
					'',
					array(
						'id' => 'products_0_description',
						'data-pattern-name' => 'products[++][description]',
						'data-pattern-id' => 'products++_description'
					)
				); 
				?>
			</div>

			<div class="four wide field">
				<label for="vehicle_0_name" data-pattern-text="Commissie">Prijs</label>
				<div class="ui left icon input">
  					<i class="euro icon"></i>
					<?php
					echo Form::text(
						'products[0][price]', 
						0,
						array(
							'id' => 'products_0_price',
							'class' => 'productPrice',
							'data-pattern-name' => 'products[++][price]',
							'data-pattern-id' => 'products++_price'
						)
					); 
					?>
				</div>
			</div>

			<div class="three wide field">
				<label for="vehicle_0_name" data-pattern-text="Commissie">Aantal</label>
				<?php
				echo Form::text(
					'products[0][amount]', 
					1,
					array(
						'id' => 'products_0_amount',
						'class' => 'productAmount',
						'data-pattern-name' => 'products[++][amount]',
						'data-pattern-id' => 'products++_amount'
					)
				); 
				?>
			</div>
			
			<div class="three wide field">
				<label for="vehicle_0_name" data-pattern-text="Commissie">BTW</label>
				<?php
				echo Form::text(
					'products[0][tax]', 
					21,
					array(
						'id' => 'products_0_tax',
						'class' => 'productTax',
						'data-pattern-name' => 'products[++][tax]',
						'data-pattern-id' => 'products++_tax'
					)
				); 
				?>
			</div>

			<div class="three wide field">
				<label>Totaal</label>
				<div class="ui left icon input">
  					<i class="euro icon"></i>
					<?php
					echo Form::text(
						'products[0][total]', 
						0,
						array(
							'id' => 'products_0_total',
							'readonly' => 'readonly',
							'data-pattern-name' => 'products[++][total]',
							'data-pattern-id' => 'products++_total'
						)
					); 
					?>
				</div>
			</div>

			<div class="three wide field">
				<label>Opties</label>
				<div class="ui buttons">
					<button type="button" class="r-btnAdd ui icon button">
						<i class="add icon"></i>
					</button>

					<button type="button" class="r-btnRemove ui red button icon">
						<i class="trash icon"></i>
					</button>
				</div>
			</div>
		</div>
	</div><br /><br />

	<button class="ui tiny button" type="submit"><i class="plus icon"></i> Opslaan</button>
	<?php echo Form::close(); ?>
</div>
<div class="clear"></div>
@stop