@foreach($data as $time => $result)
<?php
$dt = $carbon->create(date('Y', strtotime($date)), date('m', strtotime($date)), date('d', strtotime($date)), 0, 0, 0); 
?>
<tr>
    <td>
        <div class="ui child checkbox">
             <input type="checkbox" name="id[]" value="{{ $time }}" data-date="{{ $time.' uur' }}">
             <label></label>
        </div>
    </td>
    <td>{{ $dt->formatLocalized('%a %d %B %Y') }}</td>
    <td>{{ $time }}</td>
    <td class="personInput"><input type="number" name="persons[{{ $time }}]" value="{{ $result['locked'] == 1 ? 0 : $result['available_persons'] }}" /></td>
    <td>{{ $result['locked'] == 1 ? 0 : ($result['persons'] == 0 ? $result['available_persons'] : $result['available_persons'] - $result['persons']) }}</td>
    <td>
        <a class="{{ $result['persons'] >= 1 ? 'personReserved' : '' }}" 
            data-count="{{ $result['persons'] >= 1 ? $result['persons'] : '' }}"
            href="{{ url('admin/reservations/clients/'.$result['company_id'].'?date='.date('Ymd', strtotime($date))) }}">
            {{ $result['persons'] }}
        </a>
    </td>
    <td><?php echo ($result['locked'] == 1 ? '<i class="remove red icon"></i>' : '<i class="check mark green icon"></i>') ?></td>
    <td><a href="{{ url('admin/reservations/clients/'.$result['company_id'].'/'.date('Ymd', strtotime($date))) }}" class="ui tiny blue icon button">Reserveringen</a></td>
</tr>
@endforeach