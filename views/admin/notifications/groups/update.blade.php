@extends('template.theme')

@section('scripts')
	@include('admin.template.editor')
@stop

@section('content')
<div class="content">
    @if($notification != null)
    	@include('admin.template.breadcrumb')
    
		<?php echo Form::open(array('method' => 'post', 'class' => 'ui form', 'files' => true)) ?>
		<div class="ui grid">
			<div class="nine wide column">
		  	  	<div class="field">
			        <label>Naam</label>
			        <?php echo Form::text('name', $notification->name); ?>
			    </div>      
	    	</div>

			<div class="nine wide column">
		  	  	<div class="field">
			        <label>Notifications</label>
					<?php 
					echo Form::select(
						'notifications[]', 
						$notificationArray, 
						json_decode($notification->notification_ids), 
						array(
							'multiple' => true, 
							'class' => 'ui normal fluid search dropdown'
						)
					);
					?>
			    </div>
	    	</div>
		</div>      

		<button class="ui button" type="submit"><i class="save icon"></i> Opslaan</button>
		<?php echo Form::close(); ?>
	@else
		<div class="ui error message">Het formulier met record ID <strong>{{ Request::segment(4) }}</strong> is niet gevonden.</div>
	@endif
</div>
<div class="clear"></div>
@stop