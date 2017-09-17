@foreach($data as $data)
<tr>
    <td>
        <div class="ui child checkbox">
        	<input type="checkbox" name="id[]" value="{{ $data->id }}">
        	<label></label>
        </div>
    </td>
    <td>{{ $data->title }}</td>
    <td>{{ $data->categoryName }}</td>
    <td>{{ $data->subcategoryName }}</td>
    <td>{{ $data->clicks }}</td>
    <td><a href="{{ url('admin/'.$slugController.'/update/'.$data->id) }}" class="ui label"><i class="pencil icon"></i> Bewerk</a></td>
</tr>
@endforeach