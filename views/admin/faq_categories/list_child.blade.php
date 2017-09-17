@foreach($data as $data)
<tr>
    <td>
        <div class="ui child checkbox">
        	<input type="checkbox" name="id[]" value="{{ $data->id }}">
        	<label></label>
        </div>
    </td>
    <td>{{ $data->name }}</td>
    <td>{{ $data->categoryName }}</td>
    <td>
    	<a href="{{ url('admin/faq/categories/update/child/'.$data->id) }}" class="ui label">
    		<i class="pencil icon"></i> Bewerk
    	</a>
    </td>
</tr>
@endforeach