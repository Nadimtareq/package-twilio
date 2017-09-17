@extends('template.theme')

@section('content')
<div class="content">
 	@include('admin.template.breadcrumb')

	<?php echo Form::open(array('method' => 'post', 'class' => 'ui form')) ?>
	<div class="ui icon info message">
	  	<i class="info icon"></i>

	  	<div class="content">
	    	<div class="header">
     		 	OPGELET!
    		</div>
    		<p>	Alle geselecteerde subcategorie&euml;n worden &egrave;&egrave;n categorie. De affiliaties gaan naar de nieuwe categorie en de oude categorie wordt niet meer zichtbaar.</p>
	  	</div>
	</div>

	<div class="field">
		<label>Subcategorie&euml;n (1 of meer)</label>
		<?php 
		echo Form::select(
			'category[]', 
			$categories, 
			'', 
			array(
				'multiple' => 'multiple', 
				'class' => 'ui normal selection search dropdown'
			)
		); 
		?>
	</div>

	<div class="two fields">
		<div class="field">
			<h5>Nieuwe subcategorie</h5>

			<label>Naam</label>
			<?php echo Form::text('name') ?>
		</div>

		<div class="field">
			<h5>Hoofdrubriek</h5>

			<label>Naam</label>
			<?php 
			echo Form::select(
				'categoryId', 
				$parentCategories, 
				'', 
				array(
					'class' => 'ui normal selection search dropdown'
				)
			); 
			?>
		</div>
	</div>

	<div class="ui horizontal divider">
	OF
	</div>

	<div class="two fields">
		<div class="field">
			<h5>Bestaande subcategorie</h5>

			<label>Subcategorie</label>
			<?php 
			echo Form::select(
				'categoryExist', 
				array_add($categories, 0, 'Kies een subcategorie (optioneel)'), 
				0, 
				array(
					'class' => 'ui normal selection search dropdown'
				)
			); 
			?>
		</div>
	</div>

	<button class="ui tiny button" type="submit"><i class="random icon"></i> Samenvoegen</button>
	<?php echo Form::close(); ?>
</div>
<div class="clear"></div>
@stop