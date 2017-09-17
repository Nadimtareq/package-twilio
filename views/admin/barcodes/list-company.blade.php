@foreach($data as $fetch)
    <?php
    $expireDate = $fetch->expire_date != NULL ? date('d-m-Y', strtotime($fetch->expire_date)): date('d-m-Y', strtotime('+1 year', strtotime($fetch->created_at)));
    ?>

    <tr>
        <td class="center aligned">{{ trim($fetch->company_id) == 0 ? 'UwVoordeelpas' : $fetch->companyName }}</td>
        <td class="center aligned">
            <i class="circle large {{ $expireDate < date('d-m-Y') ? 'green' : 'red' }} on icon"></i>
        </td>
        <td>{{ $fetch->code }}</td>
        <td><a href="{{ url('admin/users/update/'.$fetch->user_id) }}">{{ $fetch->name }}</a></td>
        <td><a href="{{ url('admin/users/update/'.$fetch->user_id) }}">{{ $fetch->email }}</a></td>
        <td><a href="{{ url('admin/users/update/'.$fetch->user_id) }}">{{ $fetch->phone }}</a></td>
        <td>{{ date('d-m-Y', strtotime($fetch->created_at)) }}</td>
        <td>
            {{ $expireDate }}
        </td>
   </tr>
@endforeach