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

		<?php echo Form::open(array('method' => 'post', 'class' => 'ui edit-changes form', 'files' => true)) ?>
			<div class="left section">
                                <div class="two fields">
                                    <div class="field">
                                        <label>Bedrijf</label>
                                        <?php echo Form::select('company', array_add($companies, '0', 'UwVoordeelpas'), $data->company_id, array('class' => 'ui normal search dropdown'));  ?>
                                    </div>
                                    <div class="field">
                                        <label>Saldo</label>
                                        <?php echo Form::text('amount',$data->amount); ?>
                                    </div>
                                </div>    
				<div class="two fields">
					<div class="field">
					    <label>Code</label>
					    <?php echo Form::text('code', $data->code); ?>
					</div>	

						
                                <div class="field">
                                    <label>Maximale gebruik</label>
                                    <?php echo Form::text('max_usage',$data->max_usage); ?>
                                </div>	
				</div>
				<button class="ui tiny button" type="submit"><i class="pencil icon"></i> Wijzigen</button>
			</div>

			<div class="right section" style="padding-left: 20px;">
				<div class="field">
					<label>Giftcard inschakelen</label>
					<div class="ui toggle checkbox">
						<?php echo Form::checkbox('is_active', ($data->is_active == 0 ? 1 : 0), $data->is_active); ?>
						<label>Actief</label>
					</div>
				</div>
			</div>
		<?php echo Form::close(); ?>

		<div class="clear"></div>
	@else
		<div class="ui error message">Het formulier met record ID <strong>{{ Request::segment(4) }}</strong> is niet gevonden.</div>
	@endif
</div>
<div class="clear"></div>
@stop