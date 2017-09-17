@extends('template.theme')
@inject('affiliateHelper', 'App\Helpers\AffiliateHelper')

@section('scripts')
	@if($data != '')
	<script type="text/javascript" src="{{ URL::asset('public/js/tinymce/tinymce.min.js') }}"></script>
	<script>
	tinymce.init({
	    selector: "textarea",
	    theme: "modern",
	    height: 300,
	    plugins: [
	         "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
	         "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
	         "save table contextmenu directionality emoticons template paste textcolor"
	   ],
	   toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | l      ink image | print preview media fullpage | forecolor backcolor emoticons", 
	   style_formats: [
	        {title: 'Bold text', inline: 'b'},
	        {title: 'Red text', inline: 'span', styles: {color: '#ff0000'}},
	        {title: 'Red header', block: 'h1', styles: {color: '#ff0000'}},
	        {title: 'Example 1', inline: 'span', classes: 'example1'},
	        {title: 'Example 2', inline: 'span', classes: 'example2'},
	        {title: 'Table styles'},
	        {title: 'Table row 1', selector: 'tr', classes: 'tablerow1'}
	    ]
	}); 

	$('#commisson').repeater({
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
		});
	</script>
	@endif
@stop

@section('content')
<div class="content">
    @if($data != '')
		@include('admin.template.breadcrumb')
			<?php echo Form::open(array('method' => 'post', 'class' => 'ui edit-changes form', 'files' => true)) ?>
			<div class="left section">
				<div class="ui top attached tabular menu">
				  	<a class="active item" data-tab="first">Campagne</a>
				  	<a class="item" data-tab="second">Categorie&euml;n</a>
				  	<a class="item" data-tab="third">Commissies</a>
				</div>

				<div class="ui bottom attached active tab segment" data-tab="first">
					<div class="field">
						<div class="ui switch checkbox">
							<?php echo Form::checkbox('no_show', 1, $data->no_show) ?>
							<label>Niet tonen op affiliatie pagina</label>
						</div>	
					</div>

					<div class="two fields">
						<div class="field">
						    <label>
						    	Programma ID  
								<i class="icon info tool hover"></i>
								<div class="ui flowing popup top left transition hidden">
								  	PROGRAMMA ID  gaan automatisch in Tradetracker, Affilinet, Zanox en Daisycon.<br />
								  	Voor Tradedoubler kan je de desbtreffende PROGRAMMA ID vinden via <a target="_blank" href="https://login.tradedoubler.com/pan/aReport3Selection.action?reportName=aAffiliateMyProgramsReport&programAffiliateStatus=3">Mijn Programmas</a>
								</div>					
							</label>
						    <?php echo Form::text('program_id', $data->program_id); ?>
						</div>	
					</div>

					<div class="two fields">
						<div class="field">
						    <label>Naam</label>
						    <?php echo Form::text('name', $data->name); ?>
						</div>	

						<div class="field">
						    <label>Website</label>
						    <?php echo Form::text('link', $data->link); ?>
						</div>	
					</div>

					<div class="two fields">
						<div class="field">
						    <label>Kliks</label>
						    <?php echo Form::text('clicks', $data->clicks); ?>
						</div>	

						<div class="field">
						    <label>Affiliatie netwerk</label>
							<?php 
								echo Form::select(
								'affiliate_network', 
								array(
									'tradetracker' => 'Tradetracker',
									'tradedoubler' => 'Tradedoubler',
									'daisycon' => 'Daisycon',
									'affilinet' => 'Affilinet',
									'zanox' => 'Zanox',
                                    'awin' => 'Awin',
									'familyblend' => 'FamilyBlend'
								),
								$data->affiliate_network,
								 array('class' => 'ui normal search dropdown')
							) ;
							?>
						</div>	
			        </div> 
			        
			        <div class="field">
						<label>Voorwaarden</label>
						<?php echo Form::textarea('terms', $data->terms); ?>
					</div>

					<div class="two fields">
						<div class="field">
						    <label>Dagen tussen aankoop en bevesteging</label>
						    <?php echo Form::text('time_duration_confirmed', $data->time_duration_confirmed); ?>
						</div>	

						<div class="field">
						    <label>Gemiddelde sales (%)</label>
						    <?php echo Form::text('percent_sales', $data->percent_sales); ?>
						</div>	
					</div>

					<div class="two fields">
						<div class="field">
						    <label>Dagen tot storting van geld</label>
						    <?php echo Form::text('time_duration_confirmed', $data->tracking_duration); ?>
						</div>	
					</div>
				</div>

				<div class="ui bottom attached tab segment" data-tab="second">
					<div class="field">
						<label>Categorieen</label>
					    <div class="ui selection transfer normal dropdown search multiple optgroup fluid">
					    	<input value="{{ $affiliateCategories }}" type="hidden" name="categories">

					        <span class="text">Kies</span>
					        <i class="dropdown icon"></i>

					        <div class="menu">
					            @foreach($categories as $category)
					                <div class="item" data-value="{{ $category['id'] }}">
					                    <strong>{{ $category['name'] }}</strong>
					                </div>
					                    
					                @foreach($category['subcategories'] as $subcategory)
					                    @if ($subcategory['name'] != NULL)
					                        <div class="item" data-value="{{ $subcategory['id'] }}">
					                            {{ $subcategory['name'] }}
					                        </div>
					                    @endif
					                @endforeach
					            @endforeach
					        </div>
					    </div>
				    </div>
				</div>

				<div class="ui bottom attached tab segment" data-tab="third">
					<div class="field">
						<label>Maximale spaartegoed</label>
						<?php echo Form::text('max', $affiliateHelper->commissionMaxValue($data->compensations), array('readonly' => '')); ?>
					</div>	  

					<div id="commisson">
						@if (count(json_decode($data->compensations)) >= 1)
						   @foreach (json_decode($data->compensations) as $key => $commissions)
						   		<?php
								echo Form::hidden(
									'commission['.$key.'][old_value]', 
									(!isset($commissions->old_value) ? $commissions->name : $commissions->old_value)
								); 
								?>

								<div class="r-group five fields">
									<div class="two wide field">
										<label for="vehicle_0_name" data-pattern-text="Type">Type</label>
											<?php 
							        		echo Form::select(
							        			'commission['.$key.'][unit]',
							        			 array(
							        			 	'%' => '%',
							        			 	'&euro;' => '&euro;'
							        			 ), 
												str_replace(chr(0xE2).chr(0x82).chr(0xAC), "&euro;",($commissions->unit)),
							        			 array(
							        			 	'class' => 'ui normal fluid search dropdown',
							        			 	'id' => 'vehicle_'.$key.'_type',
							        			 	'data-pattern-name' => 'commission[++][unit]',
							        			 	'data-pattern-id' => 'commission++_unit'
							        			 )
							        		);
							        		?>
									</div>

									<div class="three wide field">
									    <label for="vehicle_0_name" data-pattern-text="Commissie">Commissie</label>

										<?php
										echo Form::text(
											'commission['.$key.'][value]', 
											$commissions->value,
											array(
												'id' => 'vehicle_'.$key.'_value',
												'data-pattern-name' => 'commission[++][value]',
												'data-pattern-id' => 'commission++_value'
											)
										); 
										?>
									</div>

									<div class="twelve wide field">
									    <label for="vehicle_0_name" data-pattern-text="Omschrijving">Omschrijving</label>
										<?php
										echo Form::text(
											'commission['.$key.'][name]', 
											$commissions->name,
											array(
												'id' => 'vehicle_'.$key.'_name',
												'data-pattern-name' => 'commission[++][name]',
												'data-pattern-id' => 'commission++_name'
											)
										); 
										?><br />
										
										@if (isset($commissions->old_value))
											<em><small>Vorige commisie: {{  $commissions->old_value }}</small></em>
										@endif
									</div>

									<div class="four wide field">
										<div class="ui buttons">
											<label for="vehicle_0_name" data-pattern-text="Commissie">Opties</label>

											<button type="button" class="r-btnAdd ui icon button">
												<i class="add icon"></i>
											</button>

										    <button type="button" class="r-btnRemove ui red button icon">
										    	<i class="trash icon"></i>
										    </button>
									 	</div>
									 </div>

									<div class="three wide field">
										<label for="vehicle_0_name" data-pattern-text="Commissie">No show</label>

										<div class="ui fitted checkbox">
										    <input type="checkbox" name="commission[{{ $key }}][noshow]" {{ isset($commissions->noshow) ? 'checked' : '' }}>
										    <label></label>
										</div>
									</div>
								</div>
							@endforeach
						@endif
					</div>
				</div><br />

				<button class="ui button" type="submit"><i class="pencil icon"></i> Wijzigen</button>
			</div>

			<div class="right section" style="padding-left: 20px;">
				<div class="field">
					<label>Logo</label>
					<?php echo Form::file('logo'); ?>
				</div>

 				@if(file_exists(public_path('images/affiliates/'.$data->affiliate_network.'/'.$data->program_id.'.'.$data->image_extension))) 
                    <img class="ui image" 
                          alt="{{ $data->title }}"
                          src="{{ url('public/images/affiliates/'.$data->affiliate_network.'/'.$data->program_id.'.'.$data->image_extension) }}" />
                @else
				<i class="huge circular question mark icon"></i>
                @endif
			</div>
			<?php echo Form::close(); ?>

		<div class="clear"></div>
	@else
		<div class="ui error message">Het formulier met record ID <strong>{{ Request::segment(4) }}</strong> is niet gevonden.</div>
	@endif
</div>
<div class="clear"></div>
@stop