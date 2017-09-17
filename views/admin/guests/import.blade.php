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
    			<strong>(1) name, (2) email, (3) phone, (4) gender)</strong><br /><br />

    			Rijen zonder naam of e-mail worden niet ingevoerd. Bestaande leden die u in uw CSV bestand heeft staan worden wel aan uw gasten lijst toegevoegd.<br />

    			<h5>Gender (Geslacht)</h5>
    			U kunt een geslacht aan een gast account toevoegen door gebruiken van de nummers 1 of 2.<br />
    			1 staat voor man<br />
    			2 staat voor vrouw<br /><br />
    			<img src="{{ url('public/images/import-exp.png') }}" />
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
	<p> Send Welcome Email ? </p>
		<p> <select name="sendEmail" id="sendEmail">
			<option value="1" selected="selected">Yes</option>
			<option value="0">No</option>
		</select></p>
	
		 <button class="ui tiny button" type="submit"><i class="plus icon"></i> Importeer</button>
	<?php echo Form::close(); ?>
</div>
<div class="clear"></div>
@stop