<?php
use App\Models\Company;
?>
@foreach($data as $data)
<tr>
    <td>
        <div class="ui child checkbox">
        	<input type="checkbox" name="id[]" value="{{ $data->id }}">
        	<label></label>
        </div>
    </td>
    <td>{{ $data->name }}</td>
    <td>{{ Config::get('preferences.options.'.$data->category_id) }}</td>
    <td>{{ $data->clicks }}</td>
    <td><a href="{{ url('admin/'.$slugController.'/update/'.$data->id) }}" class="ui label">Bewerk</a></td>
</tr>
@endforeach