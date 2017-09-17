@extends('template.theme')

@section('scripts')
	@include('admin.template.editor')
@stop

@section('content')
<div class="content">
    @if($notification != null)
    	@include('admin.template.breadcrumb')
    
		<?php echo Form::open(array('method' => 'post', 'class' => 'ui edit-changes form', 'files' => true)) ?>
		<?php echo Form::hidden('id'); ?>

		<div class="ui grid">
			<div class="nine wide column">
		  	  	<div class="field">
			        <label>Bericht</label>
			        <?php echo Form::textarea('content', $notification->content, array('id' => 'text', 'class' => 'editor')) ?>
			    </div>      
	    	</div>

			<div class="five wide column">
			    <div class="field">
			    	<label>Afbeelding</label>
			    	<?php echo Form::file('image'); ?><br /><br />

			    	<div class="ui one cards">
			    		@foreach($mediaItems as $id => $images)
			    			<div class="card">
			    				<div class="image">
			    					<img src="{{ url('public/'.$images->getUrl()) }}">
			    				</div>
			    				<div class="extra">
			    					<i class="crop icon"></i>
			    					<i class="expand icon"></i>
			    				</div>
			    			</div>
						 @endforeach
					</div>
				</div> 

				<div class="field">
			        <label>Breedte</label>
			        <?php echo Form::text('width', $notification->width); ?>px
			    </div>    

				<div class="field">
			        <label>Hoogte</label>
			        <?php echo Form::text('height', $notification->height); ?>px
			    </div>      
	    	</div>
		</div>      

		<button class="ui button" type="submit"><i class="save icon"></i> Opslaan</button>
		<button class="ui button" type="button" id="notificationView"><i class="eye icon"></i> Bekijk voorbeeld</button>
		<?php echo Form::close(); ?>
	@else
		<div class="ui error message">Het formulier met record ID <strong>{{ Request::segment(4) }}</strong> is niet gevonden.</div>
	@endif
</div>
<div class="clear"></div>
@stop