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
    		<p>	Alle geselecteerde categorie&euml;n worden &egrave;&egrave;n categorie. De affiliaties gaan naar de nieuwe categorie en de oude categorie wordt niet meer zichtbaar.</p>
	  	</div>
	</div>

	<div class="field">
		<label>Categorie&euml;n (1 of meer)</label>
		<?php 
		echo Form::select(
			'category[]', 
			$categories, 
			'', 
			array(
				'multiple' => 'multiple', 
				'class' => 'multipleSelect'
			)
		); 
		?>
	</div>

	<div class="field">
		<div class="ui checkbox">
			<?php echo Form::checkbox('subcategory', 1); ?>
			<label>De subcategorie&euml;n verplaatsen naar de nieuwe categorie</label>
		</div>
	</div>

	<div class="two fields">
		<div class="field">
			<h5>Nieuwe categorie</h5>

			<label>Naam</label>
			<?php echo Form::text('name') ?>
		</div>

		<div class="field">
			<h5>Bestaande categorie</h5>

			<label>Categorie</label>
			<?php 
			echo Form::select(
				'categoryExist', 
				$categories, 
				'', 
				array(
					'class' => 'ui normal dropdown'
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