@extends('template.theme')

@section('scripts')
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
@stop

@section('content')
<div class="content">
	@include('admin.template.breadcrumb')

	<?php echo Form::open(array('url' => 'admin/'.$slugController.'/create', 'method' => 'post', 'class' => 'ui edit-changes form', 'files' => TRUE)) ?>
			<div class="left section">
				<div class="ui top attached tabular menu">
				  	<a class="active item" data-tab="first">Campagne</a>
				  	<a class="item" data-tab="second">Categorie&euml;n</a>
				  	<a class="item" data-tab="third">Commissies</a>
				</div>

				<div class="ui bottom attached active tab segment" data-tab="first">
					<div class="field">
						<div class="ui switch checkbox">
							<?php echo Form::checkbox('no_show', 1) ?>
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
						    <?php echo Form::text('program_id'); ?>
						</div>	
					</div>

					<div class="two fields">
						<div class="field">
						    <label>Naam</label>
						    <?php echo Form::text('name'); ?>
						</div>	

						<div class="field">
						    <label>Website</label>
						    <?php echo Form::text('link'); ?>
						</div>	
					</div>

					<div class="two fields">
						<div class="field">
						    <label>Kliks</label>
						    <?php echo Form::text('clicks'); ?>
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
								'',
								array('class' => 'ui normal dropdown')
							) ;
							?>
						</div>	
			        </div> 
			        
					<div class="field">
						<label>Voorwaarden</label>
						<?php echo Form::textarea('terms'); ?>
					</div>
				</div>

				<div class="ui bottom attached tab segment" data-tab="second">
				 	<div class="field">
				        <label>Rubriek</label>
				        <?php echo Form::select('category[]', array_add($categories, '', 'Kies'), '', array('class' => 'ui normal dropdown multipleSelect', 'multiple' => 'multiple')) ?>
				    </div>   
				</div>

				<div class="ui bottom attached tab segment" data-tab="third">
					<div id="commisson">
								<div class="r-group three fields">
									<div class="two wide field">
										<label for="vehicle_0_name" data-pattern-text="Type">Type</label>
											<?php 
							        		echo Form::select(
							        			'commission[0][unit]',
							        			 array(
							        			 	'%' => '%',
							        			 	'€' => '&euro;'
							        			 ), 
							        			 '',
							        			 array(
							        			 	'class' => 'ui normal fluid dropdown',
							        			 	'id' => 'commission_0_type',
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
												 'commission[0][value]', 
												 '',
												 array(
												 	'id' => 'commission_0_value',
												 	'data-pattern-name' => 'commission[++][value]',
												 	'data-pattern-id' => 'commission++_value'
												 )
											); 
											?>
									    </div>

									    <div class="twelve wide field">
									      	<label for="vehicle_0_name" data-pattern-text="Omschrijving">Omschrijving</label>
											<div class="ui action input">
												<?php
												 echo Form::text(
												 	'commission[0][name]', 
												 	'',
												 	array(
												 		'id' => 'commission_0_name',
												 		'data-pattern-name' => 'commission[++][name]',
												 		'data-pattern-id' => 'commission++_name'
												 	)
												 ); 
												 ?>

												<button type="button" class="r-btnAdd ui icon button">
													<i class="add icon"></i>
												</button>

									    		<button type="button" class="r-btnRemove ui red button icon">
									    			<i class="trash icon"></i>
									    		</button>
											</div>
									    </div>

								</div>
					</div>
				</div><br />

				<button class="ui button" type="submit"><i class="pencil icon"></i> Aanmaken</button>
			</div>

			<div class="right section" style="padding-left: 20px;">
				<div class="field">
					<label>Logo</label>
					<?php echo Form::file('logo'); ?>
				</div>
			</div>
	<?php echo Form::close(); ?>
</div>
<div class="clear"></div>
@stop