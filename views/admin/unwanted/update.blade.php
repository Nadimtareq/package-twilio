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
			<label>woord</label>
            <?php echo Form::text('word',$data->word) ?>
		</div>
		<div class=" field">
			   	<label>Word Kort</label>
				<?php echo Form::select('short',  array(1 => 'E-mail', 2 => 'Website',3=>'Beide'), $data->short, array('class' => 'multipleSelect')); ?>
	</div>
<div class=" field"><br><br><br></div>

		<button class="ui button" type="submit"><i class="pencil icon"></i> Wijzigen</button>
		<?php echo Form::close(); ?>

		<div class="clear"></div>
	@else
		<div class="ui error message">Het formulier met record ID <strong>{{ Request::segment(4) }}</strong> is niet gevonden.</div>
	@endif
</div>
<div class="clear"></div>
@stop