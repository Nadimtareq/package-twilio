@extends('template.theme')

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
    @include('admin.template.breadcrumb')
	<?php echo Form::open(array('url' => 'admin/pages/create', 'method' => 'post', 'class' => 'ui edit-changes form', 'files' => TRUE)) ?>
	    <div class="left section">
			<div class="field">
			    <label>Titel</label>
			    <?php echo Form::text('title') ?>
			</div>	

			<div class="field">
				<label>Korte omschrijving (meta omschrijving) (Max 255 tekens)</label>
				<?php echo Form::textarea('meta_description', '', array('rows' =>'2')); ?>
			</div>

			<div class="field">
	            <label>Categorie</label>
	            <?php echo Form::select('category', array_add(Config::get('preferences.pages'), '', 'Niets geselecteerd'), '', array('class' =>  'multipleSelect')) ?>
	        </div>      
			
			<div class="field">
		        <label>Open popup</label>
		        <?php echo Form::select('link_to', array('' => 'Uit', 'register' => 'Aanmelden', 'login' => 'Inloggen')) ?>
		    </div>

			<div class="field">
			    <?php echo Form::textarea('content', '', array('class' => 'editor')) ?>
			</div>

			<button class="ui tiny button" type="submit"><i class="plus icon"></i> Aanmaken</button>
		</div>

		<div class="right section" style="padding-left: 20px;">
			<div class="field">
				<div class="ui checkbox">
					<label>Niet zichtbaar</label>
					<?php echo Form::checkbox('is_hidden'); ?><br />
					<small>* Alleen te zien voor beheerders</small>
				</div>
			</div>
		</div>
	<?php echo Form::close(); ?>
</div>
<div class="clear"></div>
@stop