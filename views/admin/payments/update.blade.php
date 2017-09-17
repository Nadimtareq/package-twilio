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
    @if($data != null)
    	@include('admin.template.breadcrumb')
    
		<?php echo Form::open(array('method' => 'post', 'class' => 'ui edit-changes form')) ?>
		<div class="three fields">
			<div class="field">
				<label>Bedrag</label>
				<div class="ui left icon input">
				  	<i class="euro icon"></i>
					<?php echo Form::text('amount', $data->amount); ?>
				</div>
			</div>	

			<div class="field">
				<label>Status</label>
				<?php 
				echo Form::select(
					'status',
					array(
						'open' => 'Open',
						'paid' => 'Betaald',
					),
					$data->status,
					array(
						'class' => 'ui normal dropdown'
					)
				); 
				?>
			</div>

			<div class="field">
				<label>Betalingswijze</label>
				<?php echo Form::text('payment_type', $data->payment_type); ?>
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