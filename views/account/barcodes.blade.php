@extends('template.theme')

@inject('preference', 'App\Models\Preference')

@section('scripts')
<script type="text/javascript">
$(document).ready(function() {
	$("#barcode").barcode(
		$("#barcode").data('code'), // Value barcode (dependent on the type of barcode)
		"code128" // type (string)
	);
});
</script>
@stop

@section('content')
<div class="container">
	<div class="ui breadcrumb">
		<a href="{{ url('/') }}" class="section">Home</a>
		<i class="right chevron icon divider"></i>

		<a href="#" class="sidebar open" data-activates="slide-out">Menu</a>
	    <i class="right chevron icon divider"></i>

		<div class="active section">Mijn voordeelpas</div>
	</div>

	<div class="ui divider"></div>
	
	<?php echo Form::open(array('url' => 'account/barcodes', 'method' => 'post', 'class' => 'ui form')) ?>		
		@if(count($data) == 0)
		<div class="field">
		 	<label>Voer hier uw barcode in:</label>
			<?php echo Form::text('code') ?>
		</div>

		<button class="ui green button" name="action" value="update" type="submit">
			<i class="check mark icon"></i> Activeren
		</button>
		
		<a class="ui icon blue button" href="{{ url('voordeelpas/buy') }}">
			<i class="barcode icon"></i> Koop een voordeelpas
		</a>

		<a class="ui icon button" href="{{ url('voordeelpas/buy') }}">
			<i class="info  icon"></i> Informatie
		</a>
		@endif

		@if (count($data) >= 1)	
		{!! isset($contentBlock[56]) ? $contentBlock[56] : '' !!}

		<h3 class="ui header thin">Mijn voordeelpas barcode</h3>

		<table class="ui very basic collapsing table list" style="width: 100%;">
			<thead>
				<tr>
					<th>Gekocht bij</th>
					<th>Barcode</th>
					<th>Geactiveerd op</th>
					<th>Verloopt op</th>
				</tr>
			</thead>
			<tbody>
				@foreach($data as $barcode)
				<?php
				$endDate = $barcode->expire_date == NULL OR $barcode->expire_date == '0000-00-00' ? date('Y-m-d', strtotime('+1 years', strtotime($barcode->activatedOn))) : $barcode->expire_date;
				$barcodeDate = \Carbon\Carbon::create(
				    date('Y', strtotime($endDate)), 
				    date('m', strtotime($endDate)),
				    date('d', strtotime($endDate)),
				    0,
				    0,
				    0
				);
				?>
				<tr class="{{ $barcodeDate->isPast() ? 'disabled' : '' }}">
					<td>{{ trim($barcode->name) == '' ? 'UwVoordeelpas' : $barcode->name }}</td>
					<td><div id="barcode" data-code="{{ $barcode->code }}"></div></td>
					<td>{{ date('d-m-Y', strtotime($barcode->activatedOn)) }}</td>
					<td>
						@if ($barcode->expire_date != NULL && $barcode->expire_date != '0000-00-00')
				            {{ date('d-m-Y', strtotime($barcode->expire_date)) }}
				        @else
				            {{ date('d-m-Y', strtotime('+1 year', strtotime($barcode->activatedOn))) }}
				        @endif
				    </td>
				</tr>
				@endforeach
			</tbody>
		</table>
		@endif
	<?php echo Form::close(); ?>
	</div>
</div>
@stop