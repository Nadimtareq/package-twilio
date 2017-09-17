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
			    	<label>Naam</label>
			    	<?php echo Form::text('name', $data->name) ?>
			  	</div>

			  	<button class="ui tiny button" type="submit"><i class="pencil icon"></i> Wijzigen</button>
			<?php echo Form::close(); ?>
		@else
			<div class="ui error message">Het formulier met record ID <strong>{{ Request::segment(4) }}</strong> is niet gevonden.</div>
		@endif
</div>
<div class="clear"></div>
@stop