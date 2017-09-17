@extends('template.theme')

@section('scripts')
	@include('admin.template.editor')
@stop

@section('content')
<div class="content">
    @include('admin.template.breadcrumb')

    <?php echo Form::open(array('class' => 'ui form', 'method' => 'post')) ?>
		<div class="ui grid container">
            <div class="left floated sixteen wide mobile ten wide computer column"> 
            	<div class="ui top attached tabular menu">
				  	<a class="active item" data-tab="first">Uitgelogd</a>
				  	<a class="item" data-tab="second">Account</a>
				  	<a class="item" data-tab="admin">Admin</a>
				  	<a class="item" data-tab="third">Transactie</a>
				  	<a class="item" data-tab="fourth">Bedrijf</a><br/>
                                        <a class="item" data-tab="fifth">Reserving</a>
				</div>

				<div class="ui bottom attached active tab segment" data-tab="first">
					<h5>Aanmelden</h5>

					<div class="field">
						<label>Titel</label>
						<?php echo Form::text('register_title', (isset($settings['register_title']) ? $settings['register_title'] : '')); ?>
					</div>	

					<div class="field">
						<label>Bericht</label>
						<?php echo Form::textarea('register_content', (isset($settings['register_content']) ? $settings['register_content'] : ''), array('class' => 'editor', 'rows' => '0.5')); ?>
					</div>	

					<h5>Wachtwoord vergeten</h5>

					<div class="field">
						<label>Titel</label>
						<?php echo Form::text('forgot_password_title', (isset($settings['forgot_password_title']) ? $settings['forgot_password_title'] : '')); ?>
					</div>	

					<div class="field">
						<label>Bericht</label>
						<?php echo Form::textarea('forgot_password_content', (isset($settings['forgot_password_content']) ? $settings['forgot_password_content'] : ''), array('class' => 'editor', 'rows' => '0.5')); ?>
					</div>	
				</div>
				
				<div class="ui bottom attached tab segment" data-tab="second">
					<h5>Saldo opwaarderen</h5>

					<div class="field">
						<label>Titel</label>
						<?php echo Form::text('saldo_charge_title', (isset($settings['saldo_charge_title']) ? $settings['saldo_charge_title'] : '')); ?>
					</div>	

					<div class="field">
						<label>Bericht</label>
						<?php echo Form::textarea('saldo_charge_content', (isset($settings['saldo_charge_content']) ? $settings['saldo_charge_content'] : ''), array('class' => 'editor', 'rows' => '0.5')); ?>
					</div>	
				</div>
				
				<div class="ui bottom attached tab segment" data-tab="third">
					<h5>Transactie (goedgekeurd)</h5>

					<div class="field">
						<label>Titel</label>
						<?php echo Form::text('transaction_accepted_title', (isset($settings['transaction_accepted_title']) ? $settings['transaction_accepted_title'] : '')); ?>
					</div>	

					<div class="field">
						<label>Bericht</label>
						<?php echo Form::textarea('transaction_accepted_content', (isset($settings['transaction_accepted_content']) ? $settings['transaction_accepted_content'] : ''), array('class' => 'editor', 'rows' => '0.5')); ?>
					</div>	

					<h5>Transactie (open)</h5>
					<div class="field">
						<label>Titel</label>
						<?php echo Form::text('transaction_open_title', (isset($settings['transaction_open_title']) ? $settings['transaction_open_title'] : '')); ?>
					</div>	

					<div class="field">
						<label>Bericht</label>
						<?php echo Form::textarea('transaction_open_content', (isset($settings['transaction_open_content']) ? $settings['transaction_open_content'] : ''), array('class' => 'editor', 'rows' => '0.5')); ?>
					</div>		

					<h5>Transactie (afgekeurd)</h5>
					<div class="field">
						<label>Titel</label>
						<?php echo Form::text('transaction_rejected_title', (isset($settings['transaction_rejected_title']) ? $settings['transaction_rejected_title'] : '')); ?>
					</div>	

					<div class="field">
						<label>Bericht</label>
						<?php echo Form::textarea('transaction_rejected_content', (isset($settings['transaction_rejected_content']) ? $settings['transaction_rejected_content'] : ''), array('class' => 'editor', 'rows' => '0.5')); ?>
					</div>	
				</div>
				
				<div class="ui bottom attached tab segment" data-tab="admin">
					<h5>Nieuw bedrijf (Mail naar eigenaar)</h5>

					<div class="field">
						<label>Titel</label>
						<?php echo Form::text('new_company_title', (isset($settings['new_company_title']) ? $settings['new_company_title'] : '')); ?>
					</div>	

					<div class="field">
						<label>Bericht</label>
						<?php echo Form::textarea('new_company_content', (isset($settings['new_company_content']) ? $settings['new_company_content'] : ''), array('class' => 'editor', 'rows' => '0.5')); ?>
					</div>	

					<h5>Callcenter (Inschrijfformulier / Herinnering)</h5>

					<div class="field">
						<label>Titel</label>
						<?php echo Form::text('callcenter_mail_title', (isset($settings['callcenter_mail_title']) ? $settings['callcenter_mail_title'] : '')); ?>
					</div>	

					<div class="field">
						<label>Bericht</label>
						<?php echo Form::textarea('callcenter_mail_content', (isset($settings['callcenter_mail_content']) ? $settings['callcenter_mail_content'] : ''), array('class' => 'editor', 'rows' => '0.5')); ?>
					</div>	

					<h5>Callcenter (Informatiemail)</h5>

					<div class="field">
						<label>Titel</label>
						<?php echo Form::text('callcenter_info_mail_title', (isset($settings['callcenter_info_mail_title']) ? $settings['callcenter_info_mail_title'] : '')); ?>
					</div>	

					<div class="field">
						<label>Bericht</label>
						<?php echo Form::textarea('callcenter_info_mail_content', (isset($settings['callcenter_info_mail_content']) ? $settings['callcenter_info_mail_content'] : ''), array('class' => 'editor', 'rows' => '0.5')); ?>
					</div>	
				</div>

				<div class="ui bottom attached tab segment" data-tab="fourth">
					<h5>Welkomstmail (na inschrijving)</h5>

					<div class="field">
						<label>Titel</label>
						<?php echo Form::text('welcome_mail_title', (isset($settings['welcome_mail_title']) ? $settings['welcome_mail_title'] : '')); ?>
					</div>	

					<div class="field">
						<label>Bericht</label>
						<?php echo Form::textarea('welcome_mail_content', (isset($settings['welcome_mail_content']) ? $settings['welcome_mail_content'] : ''), array('class' => 'editor', 'rows' => '0.5')); ?>
					</div>	
				</div>
                                <div class="ui bottom attached tab segment" data-tab="fifth">
					<h5>Welkomstmail (na Reserving)</h5>

					<div class="field">
						<label>Titel</label>
						<?php echo Form::text('reserving_title', (isset($settings['reserving_title']) ? $settings['reserving_title'] : '')); ?>
					</div>	

					<div class="field">
						<label>Bericht</label>
						<?php echo Form::textarea('reserving_content', (isset($settings['reserving_content']) ? $settings['reserving_content'] : ''), array('class' => 'editor', 'rows' => '0.5')); ?>
					</div>	
				</div>

				<br />
				
				<button class="ui tiny button" type="submit"><i class="pencil icon"></i> Opslaan</button>
            </div>

            <div class="left floated sixteen wide mobile five wide computer column">  
            	<h4 class="ui header">Commando's</h4>
				<div class="ui styled accordion">
				  	<div class="active title">
				    	<i class="dropdown icon"></i>
				    	%name%
				  	</div>

				  	<div class="active content">
				  		Geeft naam van klant weer
				  	</div>

				  	<div class=" title">
				    	<i class="dropdown icon"></i>
				    	%email%
				  	</div>

				  	<div class=" content">
				  		Geeft e-mail adres van klant weer
				  	</div>

				  	<div class=" title">
				    	<i class="dropdown icon"></i>
				    	%url%
				  	</div>

				  	<div class=" content">
				  		Geeft link naar pagina weer (werkt niet bij alle templates)
				  	</div>

				  	<div class=" title">
				    	<i class="dropdown icon"></i>
				    	%date%
				  	</div>

				  	<div class=" content">
						Geeft datum naar pagina weer (werkt niet bij alle templates)
				  	</div>

				  	<div class=" title">
				    	<i class="dropdown icon"></i>
				    	%status%
				  	</div>

				  	<div class=" content">
						Geeft status naar pagina weer (werkt niet bij alle templates)
				  	</div>

				  	<div class=" title">
				    	<i class="dropdown icon"></i>
				    	%place%
				  	</div>

				  	<div class=" content">
						Geeft plaats naar pagina weer (werkt niet bij alle templates)
				  	</div>

				  	<div class=" title">
				    	<i class="dropdown icon"></i>
				    	%comment%
				  	</div>

				  	<div class=" content">
						Geeft opmerking naar pagina weer (werkt niet bij alle templates)
				  	</div>

				  	<div class=" title">
				    	<i class="dropdown icon"></i>
				    	%webshop%
				  	</div>

				  	<div class=" content">
						Geeft naam van webshop weer
				  	</div>

				  	<div class=" title">
				    	<i class="dropdown icon"></i>
				    	%euro%
				  	</div>

				  	<div class=" content">
						Geeft transactie bedragweer
				  	</div>

				  	<div class=" title">
				    	<i class="dropdown icon"></i>
				    	%randomPassword%
				  	</div>

				  	<div class=" content">
						Een automatisch gegenereerde wachtwoord 
				  	</div>

				  	<div class=" title">
				    	<i class="dropdown icon"></i>
				    	%days%
				  	</div>

				  	<div class=" content">
						Geeft kortingsdagen aan
				  	</div>

				  	<div class=" title">
				    	<i class="dropdown icon"></i>
				    	%discount%
				  	</div>

				  	<div class=" content">
						Geeft korting aan
				  	</div>

				  	<div class=" title">
				    	<i class="dropdown icon"></i>
				    	%discout_comment%
				  	</div>

				  	<div class=" content">
						Geeft korting opmerkingen aan
				  	</div>
				</div>
            </div>
        </div>
    <?php echo Form::close(); ?>
</div>
<div class="clear"></div>
@stop