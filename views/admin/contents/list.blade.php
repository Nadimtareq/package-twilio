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
    <td>{{ $data->slug }}</td>
    <td>{{ $data->name }}</td>
    <td>{{ $data->type }}</td>
    <td><a href="{{ url('admin/'.$slugController.'/update/'.$data->id) }}" class="ui label"><i class="pencil icon"></i> Bewerk</a></td>
</tr>
@endforeach