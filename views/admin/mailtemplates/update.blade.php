@extends('template.theme')

@section('scripts')
	@include('admin.template.editor')

	<script type="text/javascript">
		$(document).ready(function() {
		    closeBrowser();  
		});
	</script>
@stop

@section('content')
<div class="content">
    @if($data != '')
   		 @include('admin.template.breadcrumb')
    
		<?php echo Form::open(array('url' => 'admin/mailtemplates/update/'.$data->id, 'method' => 'post', 'class' => 'ui edit-changes form')) ?>
			<?php echo Form::hidden('ids', $data->id) ?>
			<?php echo Form::hidden('company_id', $data->company_id) ?>
			<div class="ui grid">
				<div class="twelve wide column">
					<div class="field">
						<div class="ui checkbox">
					   	 	<?php echo Form::checkbox('is_active', 1, $data->is_active) ?>
					   	 	<label>Mailtemplate uitschakelen</label>
						</div>
					</div>

					<div class="field">
						<label>Onderwerp</label>
						<?php echo Form::text('subject', $data->subject) ?>
					</div>

			        <div class="field">
			            <label>Type</label>
			            <?php 
			            echo Form::select(
			                'type', 
			                array(
			                    'mail' => 'Mail',
			                    'call' => 'Bellen',
			                    'message' => 'SMS',
			                    'push' => 'Push',
			                    'notifications' => 'Notificaties',
			                ),
			                $data->type,
			                array('class' => 'ui search normal dropdown')
			            );
			            ?>
			        </div>

					<div class="field">
			            <label>Categorie</label>
			            <?php echo Form::select('category', Config::get('preferences.mail_templates'), $data->category) ?>
			        </div>

			        @if($admin == TRUE)
			        <div class="field">
			            <label>Bedrijf</label>
			            <?php echo Form::select('company', $companies, $data->company_id, array('class' => 'ui normal search dropdown'));  ?>
			        </div>
			        @endif

					<div class="one fields">
						<div class="field">
							<label>Inhoud</label>
							<?php echo Form::textarea('content', $data->content, array('class' => 'editor')) ?>
						</div>	
					</div>	

					<button class="ui tiny button" type="submit"><i class="pencil icon"></i> Wijzigen</button>
				</div>

				<div class="four wide column">
					<div class="field">
						<?php echo Form::textarea('explanation', $data->explanation) ?>
					</div>

					<div class="field">
							<label>Commando's</label>
							<table class="ui table">
					    		<tr>
						    		<td><strong>%randomPassword%</strong></td>
						    		<td>Geeft een uniek wachtwoord (werkt niet overal)</td>
						    	</tr>
					    		<tr>
						    		<td><strong>%invoicenumber%</strong></td>
						    		<td>Geeft factuurnummer van klant weer (werkt niet overal)</td>
						    	</tr>
					    		<tr>
						    		<td><strong>%name%</strong></td>
						    		<td>Geeft naam van klant weer</td>
						    	</tr>
					    		<tr>
						    		<td><strong>%cname%</strong></td>
						    		<td>Geeft naam van contactpersoon weer</td>
						    	</tr>
					    		<tr>
						    		<td><strong>%email%</strong></td>
						    		<td>Geeft e-mail adres van klant weer</td>
						    	</tr>
					    		<tr>
						    		<td><strong>%phone%</strong></td>
						    		<td>Geeft telefoonnummer van klant weer</td>
						    	</tr>
					    		<tr>
						    		<td><strong>%date%</strong></td>
						    		<td>Geeft datum van reservering weer (Werkt alleen bij reserving templates)</td>
						    	</tr>
					    		<tr>
						    		<td><strong>%time%</strong></td>
						    		<td>Geeft tijd van reservering weer (Werkt alleen bij reserving templates)</td>
						    	</tr>
					    		<tr>
						    		<td><strong>%persons%</strong></td>
						    		<td>Geeft aantal personen van reservering weer (Werkt alleen bij reserving templates)</td>
						    	</tr>
					    		<tr>
						    		<td><strong>%comment%</strong></td>
						    		<td>Geeft opmerking van reservering van een klant weer (Werkt alleen bij reserving templates)</td>
						    	</tr>
					    		<tr>
						    		<td><strong>%saldo%</strong></td>
						    		<td>Geeft saldo van klant weer</td>
						    	</tr>
					    		<tr>
						    		<td><strong>%preferences%</strong></td>
						    		<td>Geeft voorkeuren van klant weer</td>
						    	</tr>
					    		<tr>
						    		<td><strong>%allergies%</strong></td>
						    		<td>Geeft allergie&euml;n van klant weer</td>
						    	</tr>
					    		<tr>
						    		<td><strong>%url%</strong></td>
						    		<td>Geeft link naar pagina weer</td>
						    	</tr>
					    		<tr>
						    		<td><strong>%euro%</strong></td>
						    		<td>Geeft een bedrag weer</td>
						    	</tr>
					    		<tr>
						    		<td><strong>%webshop%</strong></td>
						    		<td>Geeft webshop naam weer</td>
						    	</tr>
					    		<tr>
						    		<td><strong>%days%</strong></td>
						    		<td>Geeft kortings dagen weer</td>
						    	</tr>
					    		<tr>
						    		<td><strong>%discount%</strong></td>
						    		<td>Geeft korting weer</td>
						    	</tr>
					    		<tr>
						    		<td><strong>%discount_comment%</strong></td>
						    		<td>Geeft korting opmerking weer (werkt nog niet)</td>
						    	</tr>
							</table>
						</div>	
				</div>
			</div>
		<?php echo Form::close(); ?>
	@else
		<div class="ui error message">Het formulier met record ID <strong>{{ Request::segment(4) }}</strong> is niet gevonden.</div>
	@endif
</div>
<div class="clear"></div>
@stop