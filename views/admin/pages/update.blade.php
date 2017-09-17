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
    @if($data != '')
    	@include('admin.template.breadcrumb')

		<?php echo Form::open(array('url' => 'admin/pages/update/'.$data->id, 'method' => 'post', 'class' => 'ui edit-changes form')) ?>
			<div class="left section">
				<div class="field">
				    <label>Titel</label>
				    <?php echo Form::text('title', $data->title) ?>
				</div>	

				<div class="field">
					<label>Korte omschrijving (meta omschrijving) (Max 255 tekens)</label>
					<?php echo Form::textarea('meta_description', $data->meta_description, array('rows' =>'2')); ?>
				</div>
				
				<div class="field">
		            <label>Categorie</label>
		            <?php echo Form::select('category', Config::get('preferences.pages'), $data->category_id) ?>
		        </div>

				<div class="field">
		            <label>Open popup</label>
		            <?php echo Form::select('link_to', array('' => 'Uit', 'register' => 'Aanmelden', 'login' => 'Inloggen'), $data->link_to) ?>
		        </div>

				<div class="field">
					<?php echo Form::textarea('content', $data->content, array('class' => 'editor')) ?>
				</div>

				<button class="ui tiny button" type="submit"><i class="pencil icon"></i> Wijzigen</button>
			</div>

			<div class="right section" style="padding-left: 20px;">
				<a href="{{ url($data->slug) }}" class="ui blue basic button" target="_blank">Bekijk pagina</a><br /><br />
				
				<div class="field">
					<div class="ui checkbox">
						<?php echo Form::checkbox('is_hidden', ($data->is_hidden == 0 ? 1 : ''), $data->is_hidden); ?>
						<label>Niet zichtbaar</label>
					</div><br />
					<small>* Alleen te zien voor beheerders</small>
				</div>
			</div>
		<?php echo Form::close(); ?>

		<div class="clear"></div>

		<?php echo Form::open(array('url' => 'admin/'.$slugController.'/delete', 'method' => 'post')) ?>
			<?php echo Form::hidden('id[]', $data->id) ?><br />
			<button class="ui circular icon grey button" type="submit" style="float: right;"><i class="trash icon"></i></button>
		<?php echo Form::close(); ?>
	@else
		<div class="ui error message">Het formulier met record ID <strong>{{ Request::segment(4) }}</strong> is niet gevonden.</div>
	@endif
</div>
<div class="clear"></div>
@stop