@foreach($data as $data)
<tr>
    <td>
        <div class="ui child checkbox">
        	<input type="checkbox" name="id[]" value="{{ $data->id }}">
        	<label></label>
        </div>
    </td>
    <td>{{ $data->name }}</td>
    <td>{{ $data->email }}</td>
    <td style="width: 25%">{{ $data->subject }}</td>
    <td style="width: 60%">{{ substr($data->content, 0, 180) }}...</td>
    @if($data->status == "new")
    <td><a href="{{ url("admin/contact/read/" . $data->id) }}" class="btn red darken-3 white-text">Nieuw</a></td>
    @elseif($data->status == "read")
        <td><a href="{{ url("admin/contact/read/" . $data->id) }}" class="btn yellow darken-3 white-text">Gelezen</a></td>
    @else
        <td><a href="{{ url("admin/contact/read/" . $data->id) }}" class="btn">Beantwoord</a></td>
    @endif

    {{--<td><a href="{{ url('admin/'.$slugController.'/update/'.$data->id) }}" class="ui label"><i class="pencil icon"></i> Bewerk</a></td>--}}
</tr>
@endforeach