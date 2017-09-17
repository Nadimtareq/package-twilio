@foreach($data as $data)
<?php 
$date = \Carbon\Carbon::create(
    date('Y', strtotime($data->date)), 
    date('m', strtotime($data->date)),
    date('d', strtotime($data->date)),
    date('H', strtotime($data->time)),
    date('i', strtotime($data->time)),
    0
);

$barcodeDate = \Carbon\Carbon::create(
    date('Y', strtotime($data->barcodeDate)), 
    date('m', strtotime($data->barcodeDate)),
    date('d', strtotime($data->barcodeDate)),
    0,
    0,
    0
);

if ($data->days != 'null' && $data->days != NULL && $data->days != '[""]') {
    foreach (json_decode($data->days) as $day) {
        $days[$day] = $day;
    }
}

$classBarcode = $barcodeDate->addYear(1)->isPast() ? '' : isset($days[date('N')]) ? 'active' : '';
?>
<tr class="{{ $classBarcode }}">
    <td>
        <input type="number" class="changeTableNr" data-id="{{ $data->id }}" value="{{ $data->table_nr }}" name="0" max="999" min="1" style="width: 30px;">
    </td>
    <td>
        {{ $date->formatLocalized('%d %B %Y') }} {{ date('H:i', strtotime($data->time)) }}
    </td>
    <td>{{ $data->persons }}</td>
    <td>
        <script type="text/javascript">
            var activateAjax = 'reservationadmin';

            $(document).ready(function(){
                $("#phone-email{{ $data->id }}").hide();

                $("#name{{ $data->id }}").click(function(){
                    $("#phone-email{{ $data->id }}").hide();
                });

                $("#name{{ $data->id }}").click(function(){
                    $("#phone-email{{ $data->id }}").show();
                });
            });
        </script>

        <div class="strong" id="name{{ $data->id }}" style="cursor: pointer"> <i class="user icon"></i>  {{ $data->name }} </div>  <br />

        <div id="phone-email{{ $data->id }}">
            <i class="envelope icon"></i> {{ $data->email }}<br>
            <i class="phone icon"></i> {{ $data->phone }} <br>
        </div>

        @if (trim($data->source) != '')
        Via: {{ $data->source }}
        @endif
    </td>
    <td>  {{ $data->deal }}</td>
        
    <td>
        @if ($data->allergies != 'null' && $data->allergies != NULL && $data->allergies != '[""]')
        <div class="ui normal floating dropdown small labeled basic button">
            <span class="text">Open</span>
            <i class="dropdown icon"></i>
            
            <div class="menu">
                <div class="divider"></div>

                <div class="header">
                    <i class="tags icon"></i>
                    Allergie&euml;n
                </div>

                <div class="scrolling menu">
                    @if (is_array(json_decode($data->allergies)))
                        @foreach(json_decode($data->allergies) as $allergies)
                            <div class="item">{{ $allergies }}</div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
        @endif
    </td>
    <td>
        @if($data->preferences != 'null' && $data->preferences != NULL && $data->preferences != '[""]')
        <div class="ui normal floating dropdown small labeled basic button">
            <span class="text">Open</span>
            <i class="dropdown icon"></i>
            
            <div class="menu">
                <div class="divider"></div>

                <div class="header">
                    <i class="tags icon"></i>
                    Voorkeuren
                </div>

                <div class="scrolling menu">
                    @if (is_array(json_decode($data->preferences)))
                        @foreach (json_decode($data->preferences) as $preferences)
                            <div class="item">{{ $preferences }}</div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
        @endif
    </td>
    <td style="width: 50px;">
        <div style="width: 150px; overflow: hidden;">{{ $data->comment }}</div>
    </td>
    <td>{{ $data->saldo }}</td>
    <td>
        @if (isset($days[date('N')]) && $data->discountCard == 1) 
            @if ($data->discount != 'null' && $data->discount != NULL && $data->discount != '[""]')
                echo urldecode(json_decode($data->discount)[0]);
            @endif
        @endif
    </td>
    <td style="text-align: center;">
    <?php 
    $dbDate = strtotime($data->date.' '.$data->time);
    $currentDate = strtotime(date('Y-m-d H:i'));
         if($currentDate > $dbDate){
        echo '<i class="icon green checkmark"></i>';
     }else{
        echo '<i class="icon red remove"></i>';
    }
    ?>
      <!--  <i class="icon {{ $data->restaurant_is_paid  == 1 ? 'green checkmark' : 'red remove' }}"></i> -->
    </td>
    
    @if ($company == NULL)
    <td>
        <a href="{{ url('admin/reservations/clients/'.$data->companyId) }}">{{ $data->companyName }}</a>
    </td>
    @endif

    <td>
        @if(!Request::has('cancelled'))
            <?php echo Form::open(array('class' => 'clientOptionsForm')); ?>
                <input value="{{ $data->id }}" type="hidden" name="reservationId" />
                <input value="{{ Request::segment(5)  }}" type="hidden" name="reservationDate" />

                <div class="ui">
                    @if (!$date->isPast())
                        @if($data->status == 'reserved-pending' OR $data->status == 'iframe-pending')
                            <div class="ui buttons">
                                <button value="{{ $data->status }}" name="reservationSubmit" type="submit" class="ui fluid label">Accepteren</button>
                                <button value="refused" name="reservationSubmit" type="submit" class="ui red fluid label">Weigeren</button>
                            </div>
                            <br /><br />
                        @else
                            @if($data->status != 'present' && $data->status != 'iframe-present')
                            <a href="{{ url('reservation/edit/'.$data->id.'?company_page=1') }}" class="ui fluid icon small label">
                                <i class="pencil icon"></i> Wijzig
                            </a><br />
                            @endif

                            @if ($data->status == 'present' OR $data->status == 'iframe-present')
                            <button style="margin-top: 5px;" value="{{ $data->status == 'iframe' ? 'iframe-present' : 'present' }}" name="reservationSubmit" type="button" class="ui green icon fluid label">
                                Aanwezig
                            </button>
                            @else
                            <button style="margin-top: 5px;" value="{{ $data->status == 'iframe' || $data->status == 'iframe-reserved' ? 'iframe-present' : 'present' }}" name="reservationSubmit" type="submit" class="ui fluid green label">
                             Aanwezig
                            </button>
                            @endif
                            
                            @if($data->status == 'reserved' || $data->status == 'iframe')
                            <button value="noshow" style="margin-top: 5px;" name="reservationSubmit" type="submit" class="ui fluid red label">No Show</button>
                            @endif
                        @endif
                    @endif
                </div>
            <?php echo Form::close(); ?>
        @endif
    </td>
</tr>
@endforeach