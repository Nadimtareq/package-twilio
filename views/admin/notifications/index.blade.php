@extends('template.theme')

@section('scripts')
    @include('admin.template.remove_alert')

    <script type="text/javascript">
        $(document).ready(function() {
            $('.ui.radio.checkbox').checkbox({
                onChecked: function() {
                    $('#formList').submit();
                }
            });
        });
    </script>
@stop

@section('content')
<div class="content">
    @include('admin.template.breadcrumb')

    <?php echo Form::open(array('id' => 'formList', 'method' => 'post')) ?>
    <a href="{{ url('admin/'.$slugController.'/create') }}" class="ui icon blue button">
        <i class="plus icon"></i> Nieuw
    </a>

    <a href="{{ url('admin/notifications/groups') }}" class="ui icon blue button">
        <i class="list icon"></i> Notificatie groepen
    </a>

    <button type="submit" name="action" value="radioOut" class="ui icon grey button">
        <i class="trash icon"></i> Alles deactiveren
    </button>   

    <button id="removeButton" type="submit" name="action" value="remove" class="ui disabled icon grey button">
        <i class="trash icon"></i> Verwijderen
    </button>   

    <div class="ui info message">
        Er kan alleen 1 notificatie actief zijn. Een notificatie geeft zich zelf weer zodra je klikt op een van de keuzerondjes.
    </div>

    <table class="ui sortable very basic collapsing celled unstackable table" style="width: 100%;">
        <thead>
            <tr>
                <th data-slug="disabled" class="disabled one wide">
                    <div class="ui master checkbox"><input type="checkbox"></div>
                </th>
                <th data-slug="content" class="four wide">Bericht</th>
                <th data-slug="is_on" class="four wide">Actief</th>
                <th data-slug="disabled" class="four wide"></th>
            </tr>
        </thead>
        <tbody class="list search">
            @if(count($notifications) >= 1)
                @foreach($notifications as $notification)
                <tr>
                   <td>
                        <div class="ui child checkbox">
                            <input type="checkbox" name="id[]" value="{{ $notification->id }}">
                            <label></label>
                        </div>
                    </td>
                    <td>
                       {{ strip_tags($notification->content) }}
                    </td>
                    <td>
                        <div class="ui radio checkbox">
                            <input type="radio" name="idRadio" value="{{ $notification->id }}" {{ $notification->is_on == 1 ? 'checked' : '' }}>
                        </div>
                    </td>
                    <td>
                        <a href="{{ url('admin/notifications/update/'.$notification->id) }}" class="ui small icon button">
                            <i class="pencil icon"></i>
                        </a>
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

    {!! with(new \App\Presenter\Pagination($notifications->appends($paginationQueryString)))->render() !!}

</div>
@stop