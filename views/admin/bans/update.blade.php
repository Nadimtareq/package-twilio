@extends('template.theme')

@inject('cityPref', 'App\Models\Preference')

@section('scripts')
	<script type="text/javascript">
		$(document).ready(function() {
		    closeBrowser();  
		});
	</script>
@stop

@section('content')
<div class="content">

    @if (isset($data))
    @include('admin.template.breadcrumb')
	<?php echo Form::open(array('url' => 'admin/'.$slugController.'/update/'.$data->id, 'method' => 'post', 'class' => 'ui edit-changes form')) ?>
	<div class="two fields">
			<div class="field">
				<label>Tijd</label>
				<?php 
				echo Form::select(
					'days',
					array(
						1 => 'Een dag',
						7 => 'Een week',
						14 => 'Twee weken', 
						30 => 'Een maand', 
						365 => 'Een jaar',
						730 => 'Twee jaar',
						120000 => 'Altijd',
                                                0=>'Unlock'

					), 
					'', 
					array('class' => 'ui normal dropdown')
				); 
				?>
			</div>

			<div class="field">
			    <label>Gebruiker</label>
				<div id="usersSearch" class="ui search">
					<div class="ui icon fluid input">
						<input class="prompt" type="text" placeholder="{{ $data->name }}d">
						<i class="search icon"></i>
		            </div>

		            <div class="results"></div>
		        </div>

				<input type="hidden" name="user_id" value="{{ $data->user_id }}">
			</div>
		</div>

		<div class="field">
			<label>Reden</label>
			<?php echo Form::textarea('reason', $data->reason); ?>
		</div>

		 <button class="ui tiny button" type="submit"><i class="pencil icon"></i> Wijzigen</button>
	<?php echo Form::close(); ?>
	@endif
</div>
<div class="clear"></div>
@stop
