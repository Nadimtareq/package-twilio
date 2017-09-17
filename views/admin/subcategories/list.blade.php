@foreach($data as $data)
<tr>
   <td>
        <div class="ui child checkbox">
        	<input type="checkbox" name="id[]" value="{{ $data->id }}">
        	<label></label>
        </div>
    </td>
    <td>{{ $data->slug }}</td>
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
        @if (isset($categoriesNames[$data->subcategory_id]))
            <a href="{{ url('admin/affiliates?id='.$data->subcategory_id.'&category='.str_slug($categoriesNames[$data->subcategory_id])) }}">
                <span class="ui label">{{ $categoriesNames[$data->subcategory_id] }}</span>
            </a>
        @endif

        @if (count(json_decode($data->extra_categories_id)) >= 1)
            @foreach (json_decode($data->extra_categories_id) as $extraCategory)
                @if (isset($categoriesNames[$extraCategory]))
                    <a href="{{ url('admin/affiliates?id='.$extraCategory.'&category='.str_slug($categoriesNames[$extraCategory])) }}">
                        <span class="ui label">{{ $categoriesNames[$extraCategory] }}</span>
                    </a>
                @endif
            @endforeach
        @endif
    </td>           
 	<td><a href="{{ url('admin/'.$slugController.'/update/'.$data->id) }}" class="ui label"><i class="pencil icon"></i> Bewerk</a></td>
</tr>
@endforeach