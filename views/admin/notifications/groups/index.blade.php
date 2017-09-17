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

    <a href="{{ url('admin/notifications') }}" class="ui icon blue button">
        <i class="list icon"></i> Notificaties
    </a>

    <table class="ui sortable very basic collapsing celled unstackable table" style="width: 100%;">
        <thead>
            <tr>
                <th data-slug="disabled" class="disabled one wide">
                    <div class="ui master checkbox"><input type="checkbox"></div>
                </th>
                <th data-slug="name" class="four wide">Naam</th>
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
                       {{ strip_tags($notification->name) }}
                    </td>
                    <td>
                        <a href="{{ url('admin/notifications/groups/update/'.$notification->id) }}" class="ui small icon button">
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