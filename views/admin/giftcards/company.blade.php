@extends('template.theme')

@section('scripts')
@include('admin.template.remove_alert')
@stop

@section('content')
<div class="content">
    @include('admin.template.breadcrumb')
    <div class="buttonToolbar">  
        <div class="ui grid">
            <div class="sixteen wide mobile eleven wide computer column">
                <div class="ui grid">
                    <div class="four column row">
                        <div class="column">
                            <div class="ui normal icon search selection fluid dropdown">
                                <i class="filter icon"></i>

                                <div class="text">Status</div>

                                <i class="dropdown icon"></i>

                                <div class="menu">
                                    <a class="item" href="{{ url('admin/giftcards/'.$slug.'?status=1') }}">Geactiveerd</a>
                                    <a class="item" href="{{ url('admin/giftcards/'.$slug.'?status=0') }}">Niet geactiveerd</a>
                                </div>
                            </div>
                        </div>


                        <div class="column">
                            @include('admin.template.limit')
                        </div>

                        <div class="column">
                            @include('admin.template.search.form')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php echo Form::open(array('id' => 'formList', 'method' => 'post')) ?>
    @if (count($data) >= 1)
    <table class="ui sortable very basic collapsing celled table list" style="width: 100%;">
        <thead>
            <tr>
                <th data-slug="is_active">Status</th>
                <th data-slug="code">Code</th>
                <th data-slug="amount">Saldo</th>
                <th data-slug="created_at">Koopdatum</th>
            </tr>
        </thead>
        <tbody class="list search">
            @include('admin/giftcards/list-company')
        </tbody>
    </table>
    @else
    {!! isset($contentBlock[47]) ? $contentBlock[47] : '' !!}
    @endif
    <?php echo Form::close(); ?>

    @if(count($data) >= 1)
    {!! $data->appends($paginationQueryString)->render() !!}
    @endif
</div>
<div class="clear"></div>
@stop