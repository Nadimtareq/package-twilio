@foreach($data as $data)
<tr>
    <td>
        <div class="ui child checkbox">
        	<input type="checkbox" name="id[]" value="{{ $data->id }}">
        	<label></label>
        </div>
    </td>
    <td>{{ $data->name }}</td>
    <td>{{ date('d-m-Y', strtotime($data->start_date)) }}</td>
    <td>{{ date('d-m-Y', strtotime($data->end_date)) }}</td>
    <td>&euro; {{ $data->price }}</td>
    <td>{{ $data->tax }}%</td>
    <td>{{ $data->company }}</td>
    <td>
        <a href="{{ url('admin/services/update/'.$data->id) }}" class="ui icon button"><i class="pencil icon"></i></a>
    </td>
 </tr>
 @endforeach