@extends('template.theme')

@section('scripts')
	@include('admin.template.editor')
@stop

@section('content')
<div class="content">
    @include('admin.template.breadcrumb')
    
		<?php echo Form::open(array('method' => 'post', 'class' => 'ui edit-changes form', 'files' => true)) ?>
		<div class="ui grid">
			<div class="nine wide column">
		  	  	<div class="field">
			        <label>Bericht</label>
			        <?php echo Form::textarea('content', '', array('class' => 'editor')) ?>
			    </div>      
	    	</div>

			<div class="five wide column">
			    <div class="field">
			    	<label>Afbeelding</label>
			    	<?php echo Form::file('image'); ?>
				</div>

				<div class="field">
			        <label>Breedte</label>
			        <?php echo Form::text('width'); ?>px
			    </div>    

				<div class="field">
			        <label>Hoogte</label>
			        <?php echo Form::text('height'); ?>px
			    </div>   
	    	</div>
		</div>      

		<button class="ui button" type="submit"><i class="plus icon"></i> Aanmaken</button>
		<?php echo Form::close(); ?>
</div>
<div class="clear"></div>
@stop