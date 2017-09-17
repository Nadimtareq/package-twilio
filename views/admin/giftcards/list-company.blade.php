@foreach($data as $fetch)
    

    <tr>
        <td>{{ $fetch->is_active == 1 ? 'Geactiveerd' : 'Niet geactiveerd' }}</td>
        <td>{{ $fetch->code }}</td>
        <td>{{ $fetch->amount }}</td>
        <td>{{ $fetch->buy_date }}</td>
    </tr>
@endforeach