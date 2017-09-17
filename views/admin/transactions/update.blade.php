@extends('template.theme')

@section('scripts')
@include('admin.template.editor')
@stop

@section('content')
<div class="content">
    @if($data != '')
    	@include('admin.template.breadcrumb')

		<?php echo Form::open(array('method' => 'post', 'class' => 'ui edit-changes form')) ?>
		<div class="two fields">
					<div class="field">
						<?php echo Form::hidden('program_id', $data->program_id); ?>

						<label>Programma</label>
						<div id="affiliateSearch-3" class="ui">
							<div class="ui icon fluid input">
								<input class="prompt" type="text" name="q" placeholder="{{ $data->programName }}">
								 <i class="search link icon"></i> 
							</div>

							<div class="results"></div>
						</div>
					</div>	
					<div class="field">
					<label>Reference</label>
						<div class="ui normal icon selection fluid dropdown">
                                <input type="hidden" name="aff" value="{{ Request::input('aff') }}">

                                <div class="text">Reference</div>
                                <i class="dropdown icon"></i>

                                <div class="menu">
                                    <div data-value="Extern" class="item">Extern</div>
                                    <div data-value="Eigen" class="item">Eigen</div>
                                </div>
                            </div>
					</div>


					
		</div>

				<div class="two fields">
				<div class="field">
			            <label>Netwerk</label>
			            <?php echo Form::text('affiliate_network', $data->affiliate_network) ?>
			        </div>
					<div class="field">
			            <label>Status</label>
			            <?php echo Form::select('status', $statusArray, $data->status, array('class' => 'ui normal dropdown')) ?>
			        </div>
				
					
			    </div>
				<div class="two fields">
				<div class="field">
			            <label>Gebruiker</label>
			           	<div id="usersSearch" class="ui search">
	                        <div class="ui icon fluid input">
	                            <input class="prompt" type="text" value="<?php echo (isset($data->name) ? $data->name : ''); ?>" placeholder="Typ een naam in..">
	                             <i class="search icon"></i>
	                        </div>

	                        <div class="results"></div>
	                    </div>

						<input type="hidden" name="user_id" value="<?php echo $data->user_id; ?>">
			        </div>
				<div class="field">
			       	<label>Bedrag</label>
			        <?php echo Form::text('amount', $data->amount); ?>
			    </div>
				</div>

				<button class="ui tiny button" type="submit"><i class="pencil icon"></i> Wijzigen</button>
		<?php echo Form::close(); ?>

		<div class="clear"></div>
	@else
		<div class="ui error message">Het formulier met record ID <strong>{{ Request::segment(4) }}</strong> is niet gevonden.</div>
	@endif
</div>
<div class="clear"></div>
@stop