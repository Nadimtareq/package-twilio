<?php
setlocale(LC_TIME, 'Dutch');
?>
@foreach($data as $result)

<?php

$date = \Carbon\Carbon::create(date('Y', strtotime($result->date)), date('m', strtotime($result->date)), date('d', strtotime($result->date)));

?>
<tr>
    <td>
        <i class="calendar icon"></i>
        {{ $date->formatLocalized('%d %B %Y') }}<br>

        <i class="clock icon"></i>
        {{ date('H:i', strtotime($result->time)) }} 
    </td>
    @if(Sentinel::inRole('admin'))
    <td>
        {{ $result->companyName }}
    </td>
    @endif
    <td>
        <a href="{{ url('account/reservations/'.$result->companySlug.'/user/'.$result->user_id) }}">
            {{ $result->name }}
        </a>
        <a href="{{ url('account/reservations/'.$result->companySlug.'/user/'.$result->user_id) }}">{{ $result->email }}</a>
        <a href="{{ url('account/reservations/'.$result->companySlug.'/user/'.$result->user_id) }}">{{ $result->phone }}</a>

    </td>

    <td>
        {{ $result->deal_name }}

    </td>
    <td>{{ $result->persons }}</td>
    <td><i class="euro icon"></i> {{ (float)($result->price_per_guest * $result->persons )  }}</td>
    <td class="text-aligned center">
    <?php 
    $dbDate = strtotime($result->date.' '.$result->time);
    $currentDate = strtotime(date('Y-m-d H:i'));
    if($currentDate > $dbDate){
    echo '<i class="green icon checkmark"></i>';
    }else{ echo '<i class="red remove icon"></i>';}
    ?>
    <!-- {!! ($result->restaurant_is_paid == 1 ? '<i class="green icon checkmark"></i>' : '<i class="red remove icon"></i>') !!} -->
    </td>
    <td><i class="euro icon"></i> {{ (float)$result->saldo }}</td>
</tr>
@endforeach