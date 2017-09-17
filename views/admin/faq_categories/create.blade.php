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
    
	<?php echo Form::open(array('method' => 'post', 'class' => 'ui edit-changes form')) ?>
    <div class="field">
        <label>Naam</label>
        <?php echo Form::text('name') ?>
    </div>      

	<button class="ui button" type="submit"><i class="plus icon"></i> Aanmaken</button>
	<?php echo Form::close(); ?>
</div>
<div class="clear"></div>
@stop