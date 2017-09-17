@foreach($data as $fetch)
    <?php
    $expireDate = $fetch->expire_date != NULL ? date('d-m-Y', strtotime($fetch->expire_date)): date('d-m-Y', strtotime('+1 year', strtotime($fetch->created_at)));
    ?>

    <tr>
        <td>
            <div class="ui child checkbox">
            	<input type="checkbox" name="id[]" value="{{ $fetch->id }}">
            	<label></label>
            </div>
        </td>
        <td class="center aligned">
            <i class="circle large {{ $expireDate < date('d-m-Y') ? 'green' : 'red' }} on icon"></i>
        </td>        <td>{{ $fetch->code }}</td>
        <td><a href="{{ url('admin/users/update/'.$fetch->user_id) }}">{{ $fetch->name }}</a></td>
        <td>{{ trim($fetch->companyName) == '' ? 'UwVoordeelpas' : $fetch->companyName }}</td>
        <td>{{ date('d-m-Y', strtotime($fetch->created_at)) }}</td>
        <td>
            {{ $expireDate }}
        </td>
        <td><a href="{{ url('admin/'.$slugController.'/update/'.$fetch->id) }}" class="ui label"><i class="pencil icon"></i> Bewerk</a></td>
    </tr>
@endforeach