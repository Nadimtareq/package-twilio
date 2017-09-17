@foreach($data as $fetch)
    <tr>
        <td>
            <div class="ui child checkbox">
            	<input type="checkbox" name="id[]" value="{{ $fetch->id }}">
            	<label></label>
            </div>
        </td>
        <td>{{ $fetch->is_active == 1 ? 'Geactiveerd' : 'Niet geactiveerd' }}</td>
        <td>{{ $fetch->code }}</td>
        <td>{{ $fetch->amount }}</td>
        <td>{{ $fetch->max_usage }}</td>
        <td>{{ $fetch->used_no }}</td>
        <td>{{ trim($fetch->companyName) == '' ? 'UwVoordeelpas' : $fetch->companyName }}</td>
        <td>{{ date('d-m-Y', strtotime($fetch->created)) }}</td>
        <td><a href="{{ url('admin/'.$slugController.'/update/'.$fetch->id) }}" class="ui label"><i class="pencil icon"></i> Bewerk</a>
            <a href="{{ url('admin/'.$slugController.'/used/'.$fetch->id) }}" class="ui label"><i class="eye icon"></i> Gebruik</a>
        </td>
    </tr>
@endforeach