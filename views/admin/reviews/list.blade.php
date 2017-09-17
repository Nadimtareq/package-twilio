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
    <td>{{ $data->food }}</td>
    <td>{{ $data->service }}</td>
    <td>{{ $data->decor }}</td>
    <td>{{ $data->content }}</td>
    <td>{{ $data->company }}</td>
    <td>{!! $data->is_approved ? '<i class="check green mark icon"></i>' : '<i class="remove red icon"></i>'  !!}</td>
    <td>{{ date('d-m-Y H:i', strtotime($data->created_at)) }}</td>
    <td><a href="{{ url('review/'.$data->id) }}" class="ui label">Delen</a></td>
</tr>
@endforeach