@extends('template.theme')

@section('scripts')
@include('admin.template.editor')
@stop

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
    		Zodra je een nieuwe transactie toevoeg kan het zijn dat de partij dezelfde transactie aanmaakt. Waardoor een klant dubbele spaartegoed binnen krijgt (Dit liever alleen gebruiken wanneer de transacties echt niet binnen komen)
    		</p>
	  	</div>
	 </div>

	<?php echo Form::open(array('method' => 'post', 'class' => 'ui edit-changes form')) ?>
	<div class="two fields">
		<div class="field">
			<?php echo Form::hidden('program_id'); ?>

			<label>Programma</label>
  			<div id="affiliateSearch-3" class="ui search">
  				<div class="ui icon fluid input">
  					<input class="prompt" type="text" name="q" placeholder="Zoek een webshop">
  					<i class="search link icon"></i>
  				</div>

  				<div class="results"></div>
  			</div>
		</div>	

		<div class="field">
			<label>Status</label>
			<?php echo Form::select('status', $statusArray, '', array('class' => 'ui normal dropdown')) ?>
		</div>
	</div>	

	<div class="two fields">
		<div class="field">
			<label>Bedrag</label>
			<?php echo Form::text('amount') ?>
		</div>	

		<div class="field">
			<label>Netwerk</label>
			<?php 
			echo Form::select(
				'network', 
				array(
					'tradedoubler' => 'Tradedoubler',
					'tradetracker' => 'Tradetracker',
					'affilinet' => 'Affilinet',
					'daisycon' => 'Daisycon',
					'familyblend' => 'Familyblend',
					'zanox' => 'Zanox'
				), 
				'',
				 array('class' => 'ui normal dropdown network')
			);
			?>
		</div>
	</div>
				
	<div class="field">
		<label>Gebruiker</label>
		<div id="usersSearch" class="ui search">
			<div class="ui icon fluid input">
				<input class="prompt" type="text"  placeholder="Typ een naam in..">
				<i class="search icon"></i>
            </div>

            <div class="results"></div>
        </div>

		<input type="hidden" name="user_id">
	</div>

	<button class="ui tiny button" type="submit"><i class="pencil icon"></i> Wijzigen</button>
	<?php echo Form::close(); ?>
</div>
<div class="clear"></div>
@stop