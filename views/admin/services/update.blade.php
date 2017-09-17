@extends('template.theme')

@section('scripts')
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
    
		<?php echo Form::open(array('url' => 'admin/services/update/'.$data->id, 'method' => 'post', 'class' => 'ui edit-changes form', 'files' => true)) ?>
		<div class="field">
			<label>Naam</label>
			<?php echo Form::text('name', $data->name) ?>
		</div>	

		<div class="field">
			<label>Prijs</label>
			<?php echo Form::number('price', $data->price) ?>
		</div>	

		<div class="field">
			<label>Percentage</label>
			<?php echo Form::number('tax', $data->tax) ?>
		</div>	

		<div class="field">
			<label>Periode</label>
			<?php 
			echo Form::select(
				'period', 
				array(
					0 => 'Eenmalig',
					7 => 'Wekelijks',
					30 => 'Maandelijks'
				),
				$data->periode,
				array(
					'class' => 'ui normal search dropdown'
				)
			); 
			?>
		</div>	

		<div class="field">
			<label>Bedrijf</label>
			<?php echo Form::select('company', $companies, $data->company_id, array('class' => 'ui normal search dropdown'));  ?>
		</div>

		<div class="field">
			<?php echo Form::textarea('content', $data->content) ?>
		</div>

		<button class="ui button" type="submit"><i class="save icon"></i> Opslaan</button>
	<?php echo Form::close(); ?>
	@else
		<div class="ui error message">Het formulier met record ID <strong>{{ Request::segment(4) }}</strong> is niet gevonden.</div>
	@endif
</div>
<div class="clear"></div>
@stop

