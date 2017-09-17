@foreach($data as $data)
<tr>
    <td>{{ $data->term }}</td>     
    <td>{{ $data->count == 0 ? 1 : $data->count }}x</td>     
    <td>{{ $data->page }}</td>     
</tr>
@endforeach