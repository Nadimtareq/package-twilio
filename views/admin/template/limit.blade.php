<div class="ui normal selection fluid dropdown item">
	<i class="list icon"></i>
		
	<div class="text">
		{{ (isset($limit) ? $limit : 15) }} 
	</div>
	<i class="dropdown icon"></i>

	@if(isset($limit))
	<div class="menu">
		<a class="item" href="{{ url('admin/'.$slugController.'?'.http_build_query(array_add($queryString, 'limit', '15'))) }}">15</a>
		<a class="item" href="{{ url('admin/'.$slugController.'?'.http_build_query(array_add($queryString, 'limit', '30'))) }}">30</a>
		<a class="item" href="{{ url('admin/'.$slugController.'?'.http_build_query(array_add($queryString, 'limit', '45'))) }}">45</a>
	</div>
	@endif
</div>