@extends('template.theme')

@section('scripts')
	@include('admin.template.editor')
@stop

@section('content')
<div class="content">
    @include('admin.template.breadcrumb')
    
		<?php echo Form::open(array('method' => 'post', 'class' => 'ui form', 'files' => true)) ?>
		<div class="ui grid">
			<div class="nine wide column">
		  	  	<div class="field">
			        <label>Naam</label>
			        <?php echo Form::text('name'); ?>
			    </div>      
	    	</div>

			<div class="nine wide column">
		  	  	<div class="field">
			        <label>Notifications</label>
					<?php 
					echo Form::select(
						'notifications[]', 
						$notificationArray, 
						null, 
						array(
							'multiple' => true, 
							'class' => 'ui normal fluid search dropdown'
						)
					);
					?>
			    </div>      
	    	</div>
		</div>      

		<button class="ui button" type="submit"><i class="plus icon"></i> Aanmaken</button>
		<?php echo Form::close(); ?>
</div>
<div class="clear"></div>
@stop