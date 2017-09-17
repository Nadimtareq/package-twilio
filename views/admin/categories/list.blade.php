@foreach($data as $data)
<tr>
   <td>
        <div class="ui child checkbox">
            <input type="checkbox" name="id[]" value="{{ $data->id }}">
        	<label></label>
        </div>
    </td>
    <td>
        <a href="{{ url('admin/affiliates?id='.$data->id.'&category='.$data->slug) }}">
			{{ $data->name }}
		</a>
	</td>
    <td>
        <a href="{{ url('admin/affiliates?id='.$data->id.'&category='.$data->slug) }}">
           {{ $data->programCount }}
        </a>
    </td>
    <td>
    	<a href="{{ url('admin/'.$slugController.'/update/'.$data->id) }}" class="ui icon button">
    		<i class="pencil icon"></i>
    	</a>
    </td>
</tr>
@endforeach