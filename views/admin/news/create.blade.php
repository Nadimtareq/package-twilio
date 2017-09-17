@extends('template.theme')

@section('scripts')
	<script type="text/javascript" src="{{ URL::asset('public/js/tinymce/tinymce.min.js') }}"></script>
	<script>
	tinymce.init({
	    selector: "textarea.edit",
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

	<?php echo Form::open(array('url' => 'admin/news/'.$slug.'create/', 'method' => 'post', 'class' => 'ui edit-changes form', 'files' => TRUE)) ?>
	<div class="ui grid">
		<div class="twelve wide column">
			<div class="field">
				<label>Titel</label>
				<?php echo Form::text('title') ?>
			</div>	

			<div class="field">
				<label>Korte omschrijving (meta omschrijving) (Max 255 tekens)</label>
				<?php echo Form::textarea('meta_description', '', array('rows' =>'2')); ?>
			</div>

			@if($admin)
			<div class="field">
				<label>Bedrijf</label>
				<?php echo Form::select('company', $companies, null, array('class' => 'ui normal search dropdown'));  ?>
			</div>

			<div class="field">
				<div class="ui toggle checkbox">
					<?php echo Form::checkbox('is_published', 1, 1); ?>
					<label>Zichtbaar</label>
				</div>
			</div>
			@endif

			<div class="field">
				<?php echo Form::textarea('content', '', array('class' => 'edit')) ?>
			</div>

			<button class="ui button" type="submit"><i class="plus icon"></i> Aanmaken</button>
		</div>

		<div class="three wide column">
			<div class="field">
				<label>Afbeeldingen</label>
				<?php echo Form::file('images[]', array('multiple' => true, 'class' => 'multi with-preview')); ?>
			</div>
		</div>
	</div>
	<?php echo Form::close(); ?>
</div>
<div class="clear"></div>
@stop