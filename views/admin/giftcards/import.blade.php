@extends('template.theme')

@inject('preference', 'App\Models\Preference')

@section('content')
<div class="content">
    @include('admin.template.breadcrumb')
	<div class="ui icon info message">
	  	<i class="info icon"></i>

	  	<div class="content">
	    	<div class="header">
     		 	OPGELET!
    		</div>
    		<p>
    			Via de onderstaande formulier kunt u nieuwe gasten importeren met een CSV bestand. Het is belangrijk dat de eerste rij de kolom namen vermeld zijn:
    			<strong>code,amount,max_usage</strong><br /><br />
    		</p>
	  	</div>
	</div>

	<?php echo Form::open(array( 'method' => 'post', 'class' => 'ui form', 'files' => true)) ?>
		<div class="fields">
			<div class="twelve wide field">
			    <label>Upload hier een CSV bestand</label>
			    <?php echo Form::file('csv') ?>
			</div>
		</div>		
	
		<button class="ui tiny button" type="submit"><i class="plus icon"></i> Importeer</button>
	<?php echo Form::close(); ?>
</div>
<div class="clear"></div>
@stop