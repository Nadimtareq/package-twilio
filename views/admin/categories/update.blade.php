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
		<div class="ui grid">
			<div class="ten wide column">
				<div class="field">
					<div class="ui switch checkbox">
						<?php echo Form::checkbox('no_show', 1, $data->no_show) ?>
						<label>Niet tonen</label>
					</div>	
				</div>

				<div class="field">
					<label>Naam</label>
					<?php echo Form::text('name', $data->name) ?>
				</div>	

				<div class="field">
					<label>Advertentie pagina (doorstuur link)</label>
					<?php echo Form::select('ad_page_id', $pages, $data->ad_page_id, array('multiple' => true, 'class' => 'ui normal fluid search dropdown')); ?>
				</div>	
			</div>	

			<div class="five wide column">
				<div class="field">
					<label>Advertentie</label>
					<?php echo Form::file('ad'); ?><br /><br />

					<div class="ui one cards">
						@foreach($mediaItems as $id => $ad)
							<div class="card">
								<div class="image">
									<img src="{{ url('public/'.$ad->getUrl()) }}">
								</div>
								<div class="extra">
									<a href="{{ url('admin/'.$slugController.'/delete/image/'.$data->id.'/'.$id) }}">
										<i class="trash icon"></i>
									</a>
								</div>
							</div>
						@endforeach
					</div>
				</div>
			</div>
		</div>

		<button class="ui tiny button" type="submit"><i class="pencil icon"></i> Wijzigen</button>
		<?php echo Form::close(); ?>
	@else
		<div class="ui error message">Het formulier met record ID <strong>{{ Request::segment(4) }}</strong> is niet gevonden.</div>
	@endif
</div>
<div class="clear"></div>
@stop