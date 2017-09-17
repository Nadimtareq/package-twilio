@extends('template.theme')

@section('scripts')
	<script type="text/javascript">
		$(document).ready(function() {
		    closeBrowser();  
		});
	</script>
@stop

@section('content')
<div class="content">
    @include('admin.template.breadcrumb')

	<?php echo Form::open(array('url' => 'admin/'.$slugController.'/create', 'method' => 'post', 'class' => 'ui edit-changes form', 'files' => true)) ?>
	<div class="field">
		<div class="ui slider checkbox">
			<?php echo Form::checkbox('no_frontpage', '', 1); ?>
			<label>Niet op de homepage</label>
		</div>
	</div>

	<div class="field">
		<label>Afbeelding</label>
		<?php echo Form::file('photo'); ?>
	</div>

	<div class="field">
		<label>Categorie</label>
		<?php echo Form::select('category', Config::get('preferences.options'), '', array('class' => 'ui normal dropdown')) ?>
	</div>		

	<div class="field">
		<label>Naam</label>
		<?php echo Form::text('name') ?>
	</div>	
	
	<button class="ui tiny button" type="submit"><i class="plus icon"></i> Aanmaken</button>
	<?php echo Form::close(); ?>
</div>
<div class="clear"></div>
@stop