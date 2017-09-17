@extends('template.theme')
@inject('calendarHelper', 'App\Helpers\CalendarHelper')

@section('scripts')
    @include('admin.template.remove_alert')
@stop

@section('content')
<script type="text/javascript">
    var activateAjax = 'appointmentsadmin';
</script>

<div class="content">
    @include('admin.template.breadcrumb')
    
    <input type="hidden" name="redirectUrl" value="{{ url('admin/appointments') }}">

    <?php echo Form::open(array('id' => 'formList', 'method' => 'post')) ?>
    <a href="{{ url('admin/'.$slugController.'/create') }}" class="ui icon blue button">
        <i class="plus icon"></i> Nieuw
    </a>

    <button id="removeButton" type="submit" name="action" value="remove" class="ui disabled icon grey button">
        <i class="trash icon"></i> Verwijderen
    </button>   
    
    <div class="ui icon input">
        <input type="text" class="ajax-datepicker" />
        <i class="calendar icon"></i>
    </div>

    @if ($userAdmin)
        <div class="ui normal selection dropdown item">
            <input type="hidden" name="saldo" value="{{ Request::get('saldo') }}">
            <div class="text">
                Beller
            </div>
            <i class="dropdown icon"></i>

            <div class="menu">
                <a class="item" href="{{ url('admin/'.$slugController) }}">Alles</a>

                @foreach ($callcenterUsers as $user)
                    <a class="item" data-value="1" href="{{ url('admin/'.$slugController.'?'.http_build_query(array_add($queryString, 'caller_id', 1))) }}">{{ $user->name }}</a>
                @endforeach
            </div>
        </div>
    @endif

    <table class="ui sortable very basic collapsing celled unstackable table" style="width: 100%;">
        <thead>
            <tr>
                <th data-slug="disabled" class="disabled one wide">
                    <div class="ui master checkbox"><input type="checkbox"></div>
                </th>
                <th data-slug="appointment_at" class="four wide">Datum en tijd</th>
                <th data-slug="caller_id" class="four wide">Beller</th>
                <th data-slug="status" class="four wide">Status</th>
                <th data-slug="email" class="four wide">E-mailadres</th>
                <th data-slug="disabled" class="four wide"></th>
            </tr>
        </thead>
        <tbody class="list search">
            @if(count($appointments) >= 1)
                @foreach($appointments as $appointment)
                <?php 
                if (trim($appointment->appointment_at) != '0000-00-00 00:00:00') {
                    $date = Carbon\Carbon::create(date('Y', strtotime($appointment->e)), date('m', strtotime($appointment->appointment_at)), date('d', strtotime($appointment->appointment_at))); 
                }
                ?>
                <tr>
                   <td>
                        <div class="ui child checkbox">
                            <input type="checkbox" name="id[]" value="{{ $appointment->id }}">
                            <label></label>
                        </div>
                    </td>
                    <td>
                        @if ($appointment->appointment_at != '0000-00-00 00:00:00')
                        {{ $date->formatLocalized('%d-%m-%Y') }} 
                        om {{ date('H:i', strtotime($appointment->appointment_at)) }}
                        @else
                        Ongeldige datum
                        @endif
                    </td>
                    <td>{{ $appointment->callerName }}</td>
                    <td>{{ $appointment->status }}</td>
                    <td>{{ $appointment->email }}</td>
                    <td>
                        <div class="ui buttons">
                            <a href="{{ url('admin/appointments/update/'.$appointment->id) }}" class="ui small icon button">
                                <i class="pencil icon"></i>
                            </a>
                            {!! $calendarHelper->displayCalendars(
                                1, 
                                $appointment->name,
                                $appointment->comment, 
                                $appointment->place, 
                                date('Y-m-d H:i:s', strtotime($appointment->appointment_at)),
                                date('Y-m-d H:i:s', strtotime($appointment->appointment_at.' +1 hours'))
                            ) !!}
                        </div>
                    </td>
                </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="2"><div class="ui error message">Er is geen data gevonden.</div></td>
                </tr>
            @endif
        </tbody>
    </table>
    <?php echo Form::close(); ?>

                {!! with(new \App\Presenter\Pagination($appointments->appends($paginationQueryString)))->render() !!}

</div>
@stop