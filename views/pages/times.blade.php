@foreach($times as $time)
	@if(in_array($time, $disabled))
	<div class="item" data-value="{{ $time }}">{{ $time }}</div>
	@endif
@endforeach