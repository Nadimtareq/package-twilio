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

		<?php echo Form::open(array('url' => 'admin/'.$slugController.'/update/'.$data->id, 'method' => 'post', 'class' => 'ui edit-changes form')) ?>
		<div class="field">
			<label>Vraag</label>
			<?php echo Form::text('title', $data->title) ?>
		</div>

	    <div class="field">
	        <label>Categorie</label>
	        <?php echo Form::select('category', $categories, $data->category, array('id' => 'getCategories', 'class' => 'ui normal dropdown')) ?>
	    </div>   

	    <div class="field">
	        <label>Subcategorie</label>
	        <div id="getSubCategories" class="ui normal selection dropdown">
	            <input type="hidden" value="{{ $data->subcategory }}" name="subcategory">
	            <i class="dropdown icon"></i>

	            <div class="default text">Kies een subcategorie</div>

	            <div class="menu">
	            	<div class="item" data-value=" ">Geen</div>
	            	@foreach($subcategories as $key => $subcategory)
	            	<div class="item" data-value="{{ $key }}">{{ $subcategory }}</div>
	            	@endforeach
	            </div>
	        </div>
	    </div>   
	 
		<div class="field">
			<label>Antwoord</label>
			<?php echo Form::textarea('content', $data->answer) ?>
		</div>

		<button class="ui button" type="submit"><i class="pencil icon"></i> Wijzigen</button>
		<?php echo Form::close(); ?>

		<div class="clear"></div>
	@else
		<div class="ui error message">Het formulier met record ID <strong>{{ Request::segment(4) }}</strong> is niet gevonden.</div>
	@endif
</div>
<div class="clear"></div>
@stop