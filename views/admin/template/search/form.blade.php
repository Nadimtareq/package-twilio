
<span id="searchModal" class="ui icon button search"><i class="search icon"></i></span>
	
<div class="hideForm" style="display: none;">
	<div id="ajaxSearchForm" class="ajaxSearchForm" data-url="{{ url('admin/'.(isset($searchPath) ? $searchPath : $slugController)) }}">
	 	<div class="ui input">
	 		<input type="text" id="dropdownSearchInput" class="admin input dropdownSearchInput" name="q" placeholder="Zoeken...">
			<button class="dropdownSearchButton ui button basic admin-search" type="button" id="dropdownSearchButton"><i class="search icon"></i></button>
		</div>
	</div>
</div>