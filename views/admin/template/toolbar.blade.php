<a href="{{ url('admin/'.$slugController.'/create'.(isset($companyParam) ? '/'.$companyParam : '')) }}" class="ui icon blue button">
	<i class="plus icon"></i> Nieuw
</a>
	
<button id="removeButton" type="submit" name="action" value="remove" class="ui disabled icon grey button">
   	<i class="trash icon"></i> Verwijderen
</button><br />