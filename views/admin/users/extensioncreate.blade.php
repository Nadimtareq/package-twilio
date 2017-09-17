@extends('template.theme')

@inject('preference', 'App\Models\Preference')

@section('content')
<div class="content">
    @include('admin.template.breadcrumb')

	<?php echo Form::open(array('url' => 'admin/'.$slugController.'/list/create', 'method' => 'post', 'class' => 'ui edit-changes form')) ?>
		

		<div class="fields six ">
			
			    <label>Uitbreiding</label>
			    <?php echo Form::text('extension') ?>
			
		</div>
		<div class="four wide field">
			   	<label>staat</label>
				<?php echo Form::select('status',  array(1 => 'in staat stellen', 0 => 'onbruikbaar maken'), '', array('class' => 'multipleSelect')); ?>
			</div>
		<div class="fields">
			
			   <br>
		</div>

			
		  	
			
		

		

		 <button class="ui tiny button" type="submit"><i class="plus icon"></i> Aanmaken</button>
	<?php echo Form::close(); ?>
</div>
<div class="clear"></div>
@stop