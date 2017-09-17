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
    @if($data != '')
    	@include('admin.template.breadcrumb')

		<?php echo Form::open(array('url' => 'admin/'.$slugController.'/update/'.$data->id, 'method' => 'post', 'class' => 'ui edit-changes form', 'files' => true)) ?>
		<div class="field">
			<div class="ui slider checkbox">
				<?php echo Form::checkbox('no_frontpage', 1, $data->no_frontpage); ?>
				 <label>Niet op de homepage</label>
			</div>
		</div>

		<div class="field">
			<label>Afbeelding</label>
			<?php echo Form::file('photo'); ?>
		</div>

		<div class="field">
			<label>Categorie</label>
			<?php echo Form::select('category', Config::get('preferences.options'), $data->category_id, array('class' => 'ui normal dropdown')) ?>
		</div>		

		<div class="field">
			<?php echo Form::hidden('old_value', $data->slug); ?>
		</div>	

		<div class="field">
			<label>Naam</label>
			<?php echo Form::text('name', $data->name); ?>
		</div>	

		<button class="ui tiny button" type="submit"><i class="pencil icon"></i> Wijzigen</button>
		<?php echo Form::close(); ?>

		<div class="clear"></div>
	@else
		<div class="ui error message">Het formulier met record ID <strong>{{ Request::segment(4) }}</strong> is niet gevonden.</div>
	@endif
</div>
<div class="clear"></div>
@stop