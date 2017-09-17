@extends('template.theme')

@section('fixedMenu')
<a href="{{ url('faq/8/reserveren') }}" class="ui black icon big launch right attached fixed button">
    <i class="question mark icon"></i>
    <span class="text">Veelgestelde vragen</span>
</a><br />
@stop

@section('scripts')
	<script type="text/javascript">
		$(document).ready(function() {
		    closeBrowser();  
		});
	</script>

	@if (Request::has('step'))
	<script>
	var i  = 0;

	$('#days0').select({
		maxOptionsInLabel: 1
	});

	$('#dateAppend').repeater({
		btnAddClass: 'r-btnAdd',
		btnRemoveClass: 'r-btnRemove',
		groupClass: 'r-group',
		minItems: 0,
		maxItems: 0,
		startingIndex: 0,
		reindexOnDelete: true,
		repeatMode: 'append',
		animation: null,
		animationSpeed: 400,
		animationEasing: 'swing',
		clearValues: true,
		afterAdd: function() {
			i++;

		    $('.timepicker').pickatime({
		        format: 'H:i',
		        formatLabel: 'H:i',
		        formatSubmit: 'H:i',
		        interval: 15,
		        clear: 'verwijderen'
		    });

			$('#days' + i).select({
				maxOptionsInLabel: 1
			});
		}
	});
	</script>
	@endif
@stop

@section('content')
<div class="content">
 	@include('admin.template.breadcrumb')
	
	@if (Request::has('step'))
	<div class="ui three mini steps">
		<a href="{{ url('admin/companies/update/'.$company.'/'.$slug.'?step=1') }}" class="link step">
			<i class="building icon"></i>
			<div class="content">
				<div class="title">Bedrijf</div>
			</div>
		</a>

		<div class="active  step">
			<i class="calendar icon"></i>
			<div class="content">
				<div class="title">Reserveringen</div>
			</div>
		</div>

		<a href="{{ url('faq/3/restaurateurs?step=3&slug='.$slug) }}" class="link step">
			<i class="question mark icon"></i>
			<div class="content">
				<div class="title">Veelgestelde vragen</div>
			</div>
		</a>
	</div><br /><br />

	<div class="ui icon info message">
	  	<i class="info icon"></i>

	  	<div class="content">
	    	<div class="header">
     		 	OPGELET!
    		</div>
    		<p>
    			Met het onderstaande formulier kunt u nieuwe reserveringstijden opgegeven. Deze tijden kunt u gemakkelijk weer aanpassen op de pagina 
    			<strong>Instellingen</strong>.
    		</p>
	  	</div>
	  </div>
	@endif

	<?php echo Form::open(array('url' => 'admin/reservations/create/'.(trim($slug) != '' ? $slug : '').(Request::has('step') ? '?step=1' : ''), 'method' => 'post', 'class' => 'ui edit-changes form', 'files' => TRUE)) ?>
		@if(Sentinel::inRole('admin') && !Request::has('step'))
		<div class="field">
			<label>Bedrijf</label>
			<?php echo Form::select('company', $companies, $company, array('class' => 'ui normal search dropdown'));  ?>
		</div>	
		@endif
		
		@if (Request::has('step'))
			<?php echo Form::hidden('company', $company); ?>
			<?php echo Form::hidden('start_date', date('Y-m-d')); ?>
			<?php echo Form::hidden('end_date', date('Y-m-d', strtotime('+1 year'))); ?>
			
			<div id="dateAppend">
				@for ($i = 0; $i < 1; $i++) 
				<div class="r-group four fields">
					<div class="field">
						<label>Dag(en)</label>
						<?php
						echo Form::select(
							'days[0][]',
							Config::get('preferences.days'),
							'',
							array(
								'multiple' => true,
								'class' => 'multipleSelect',
								'id' => 'days_0',
								'data-pattern-name' => 'days[++][]',
								'data-pattern-id' => 'days++'
							)
						) ?>
					</div>	

					<div class="field">
						<label>Begin tijd</label>
						<div class="ui icon input">
							<?php echo Form::text('start_time[0]', '', array('class' => 'timepicker', 'placeholder' => 'Selecteer een tijd', 'data-pattern-name' => 'start_time[++]','data-pattern-id' => 'start_time++')); ?>
							<i class="clock icon"></i>
						</div>
					</div>	

					<div class="field">
						<label>Eind tijd</label>
						<div class="ui icon input">
							<?php echo Form::text('end_time[0]', '', array('class' => 'timepicker', 'placeholder' => 'Selecteer een tijd', 'data-pattern-name' => 'end_time[++]','data-pattern-id' => 'end_time++')); ?>
							<i class="clock icon"></i>
						</div>
					</div>	

					<div class="field">
						<label>Opties</label>
						<div class="ui buttons">
							<button type="button" class="r-btnAdd ui icon button">
								<i class="add icon"></i>
							</button>
							
							<button type="button" class="r-btnRemove ui red button icon">
								<i class="trash icon"></i>
							</button>
						</div>	
					</div>	
				</div>	
				@endfor
			</div>
		@endif
<br />
		<div class="two fields">
			@if (!Request::has('step'))
				@for ($i = 0; $i < 1; $i++) 
				<div class="two fields">
					<div class="field">
						<label>Dag(en)</label>
						<?php echo Form::select('days['.$i.'][]', Config::get('preferences.days'), '', array('multiple' => true, 'id' => 'day', 'class' => 'multipleSelect')) ?>
					</div>	

					<div class="field">
						<label>Begin tijd</label>
						<div class="ui icon input">
							<?php echo Form::text('start_time['.$i.']', '', array('class' => 'timepicker', 'placeholder' => 'Selecteer een tijd')); ?>
							<i class="clock icon"></i>
						</div>
					</div>	

					<div class="field">
						<label>Eind tijd</label>
						<div class="ui icon input">
							<?php echo Form::text('end_time['.$i.']', '', array('class' => 'timepicker', 'placeholder' => 'Selecteer een tijd')); ?>
							<i class="clock icon"></i>
						</div>
					</div>	
				</div>	
				@endfor
			@endif
		</div>	

		@if (!Request::has('step'))
		<div class="two fields">
			<div class="field">
				<label>Start datum</label>
				<div class="ui icon input">
					<?php echo Form::text('start_date', '', array('class' => 'datepicker', 'placeholder' => 'Selecteer een datum')); ?>
					<i class="calendar icon"></i>
				</div>
			</div>	

			<div class="field">
				<label>Eind datum</label>
				<div class="ui icon input">
					<?php echo Form::text('end_date', '', array('class' => 'datepicker', 'placeholder' => 'Selecteer een datum')); ?>
					<i class="calendar icon"></i>
				</div>
			</div>	
		</div>	
		@endif

		<div class="two fields">
			<div class="field">
				<label>Periode dat een gast vooraf moet reserveren</label>
				<?php 
				echo Form::select(
					'closed_before_time', 
					Config::get('preferences.closed_time'), 
					'',
					array(
						'class' => 'ui normal search dropdown'
					)
				) 
				?>
			</div>

			<div class="field">
				<label>Dagelijks reserveren kan tot:</label>
				<div class="ui icon input">
					<?php 
					echo Form::text(
						'closed_at_time', 
						'',
						array(
							'class' => 'timepicker', 
							'placeholder' => 'Selecteer een tijd'
						)
					);
					?>
					<i class="clock icon"></i>
				</div>
			</div>	
		</div>

		<div class="two fields">
			<div class="field">
				<label>Periode dat een gast vooraf een reservering mag annuleren</label>
				<?php 
				echo Form::select(
					'cancel_before_time', 
					Config::get('preferences.closed_time'), 
					'',
					array(
						'class' => 'ui normal search dropdown'
					)
				) 
				?>
			</div>

			<div class="field">
				<label>Aantal plaatsen <strong>per uur</strong></label>
				<div class="ui icon input">
					<?php 
					echo Form::text(
						'max_persons', 
						''
					);
					?>
					<i class="users icon"></i>
				</div>
			</div>	
		</div>	

		<div class="two fields">
			<div class="field">
				<label>Periode dat een gast vooraf een reservering mag wijzigen</label>
				<?php 
				echo Form::select(
					'update_before_time', 
					Config::get('preferences.closed_time'), 
					'',
					array(
						'class' => 'ui normal search dropdown'
					)
				) 
				?>
			</div>

			<div class="field">
				<label>Wilt u de reserveringen automatisch goedkeuren?</label>
				<?php 
				echo Form::select(
					'is_manual', 
					array(
						0 => 'Ja',
						1 => 'Nee'
					),
					'',
					array(
						'class' => 'ui normal search dropdown'
					)
				) 
				?>
			</div>
		</div>
		
		<div class="two fields">
			<div class="field">
				<label>Periode voor de reservering een herinnering ontvangen (klant)?</label>
				<?php 
				echo Form::select(
					'reminder_before_date', 
					array(
						0 => 'Uit',
						1 => '1 uur',
						2 => '2 uur',
						3 => '3 uur',
						4 => '4 uur',
						5 => '5 uur'
					), 
					'',
					array(
						'class' => 'ui normal search dropdown'
					)
				) 
				?>
			</div>
			
			<div class="field">
				<label>Aantal plekken beschikbaar</label>
				<?php echo Form::number('available_persons', 1, array('min' => 2)) ?>
			</div>
		</div>


		<div class="two fields">
			@if (!Request::has('step'))
			<div class="field">
				<label>Plaatsen per</label>
				<?php echo Form::select('per_time',  array(60 => 'Per uur', 30 => 'Per halfuur', 15 => 'Per kwartier'), '', array('class' => 'ui normal search dropdown')) ?>
			</div>	
			@else
			<?php echo Form::hidden('per_time', 15) ?>
			@endif

			@if (!Request::has('step'))
			<div class="field">
				<label>Aantal actie plaatsen</label>
				<?php echo Form::number('available_deals', 1, array('min' => 1)) ?>
			</div>	
			@endif
		</div>	

		<button class="ui tiny button" type="submit"><i class="plus icon"></i> Aanmaken</button>
	<?php echo Form::close(); ?>
</div>
<div class="clear"></div>
@stop