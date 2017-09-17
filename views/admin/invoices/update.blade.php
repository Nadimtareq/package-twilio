@extends('template.theme')

@inject('preference', 'App\Models\Preference')

@section('scripts')

	@if (count(json_decode($invoice->products, true)) >= 1)
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
	@endif

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
	<div class="two fields">
		<div class="field">
			<label>Factuurnummer</label>
		   	<?php
		   	echo Form::text(
		   		'invoice_number',
		   		$invoice->invoice_number
		   	); 
		   	?>
		</div>

		<div class="field">
			<label>Bedrijf</label>
			<?php 
			echo Form::select(
				'company',  
				array_add($companies, '', 'Kies een bedrijf'),
		   		$invoice->company_id,
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
			 	$invoice->start_date,
			 	array(
			 		'class' => 'datepicker', 
			 		'placeholder' => 'Selecteer een datum'
			 	)
			 ); ?>
		</div>

		<div class="field">
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
			 	$invoice->period,
				array(
					'class' => 'ui normal dropdown'
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
				$invoice->type, 
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
				$invoice->debit_credit,
				array(
					'class' => 'ui normal dropdown'
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
				$invoice->payment_method, 
				array(
					'class' => 'ui normal dropdown'
				)
			); 
			?>
		</div>
	</div>

	<div class="ui divider"></div>

	<div id="productsMessage" class="ui info message" style="display: none;">
		Selecteer eerst een bedrijf om een dienst toe te voegen
	</div>

	<div id="products">
		@if (count(json_decode($invoice->products, true)) >= 1)
			@foreach(json_decode($invoice->products, true) as $key => $product)
			<div id="productsTable" class="r-group six fields">
				<div class="four wide field">
					<label for="vehicle_{{ $key }}_name" data-pattern-text="Type">Dienst</label>
					<?php 
					echo Form::select(
						'products[service][]',
						array(
							0 => 'Kies een dienst'
						),
						0,
						array(
							'class' => 'ui normal fluid dropdown product-services',
							'id' => 'products_'.$key.'_service',
							'data-pattern-name' => 'products[++][service]',
							'data-pattern-id' => 'products++_service'
						)
					);
					?>
				</div>

				<div class="four wide field">
					<label for="vehicle_{{ $key }}_name" data-pattern-text="Commissie">Prijs</label>
					<div class="ui left icon input">
	  					<i class="euro icon"></i>
						<?php
						echo Form::text(
							'products[price][]', 
							isset($product['price']) ? $product['price'] : '',
							array(
								'id' => 'products_'.$key.'_price',
								'class' => 'productPrice',
								'data-pattern-name' => 'products[++][price]',
								'data-pattern-id' => 'products++_price'
							)
						); 
						?>
					</div>
				</div>

				<div class="three wide field">
					<label for="vehicle_{{ $key }}_name" data-pattern-text="Commissie">Aantal</label>
					<?php
					echo Form::text(
						'products[amount][]', 
						isset($product['amount']) ? $product['amount'] : '',
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

				<div class="six wide field">
					<label for="vehicle_{{ $key }}_name" data-pattern-text="Commissie">Omschrijving</label>
					<?php
					echo Form::text(
						'products[description][]', 
						isset($product['description']) ? $product['description'] : '',
						array(
							'id' => 'products_'.$key.'_description',
							'data-pattern-name' => 'products[++][description]',
							'data-pattern-id' => 'products++_description'
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
							'products[total][]', 
							isset($product['price']) && isset($product['amount']) ? $product['price'] * $product['amount'] : '',
							array(
								'id' => 'products_'.$key.'_total',
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
			@endforeach
		@endif
	</div><br><br>

	<button class="ui tiny button" type="submit"><i class="plus icon"></i> Opslaan</button>
	<?php echo Form::close(); ?>
</div>
<div class="clear"></div>
@stop