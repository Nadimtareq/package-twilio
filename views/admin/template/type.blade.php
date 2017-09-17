<div class="ui normal selection fluid dropdown item">
	<div class="text">
		{{ (isset($type) ? $type : 'Type') }} 
	</div>
	<i class="dropdown icon"></i>

	@if(isset($limit))
	<div class="menu">
		<a class="item" href="{{ url('admin/'.$slugController.'?'.http_build_query(array_add($queryString, 'type', 'website'))) }}">Website</a>
		<a class="item" href="{{ url('admin/'.$slugController.'?'.http_build_query(array_add($queryString, 'type', 'mail'))) }}">Mail</a>
	</div>
	@endif
</div>