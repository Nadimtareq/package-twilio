@extends('template.theme')

@section('scripts')
    @include('admin.template.remove_alert')
@stop

@section('content')
    <div class="content">
        @include('admin.template.breadcrumb')
        <div class="buttonToolbar">
            <div class="ui grid">
                <div class="left floated sixteen wide mobile nine wide computer column">

                    <button id="removeButton" type="submit" name="action" value="remove" class="ui disabled icon grey button">
                        <i class="trash icon"></i> Verwijderen
                    </button>

                    {{--<a href="{{ url('admin/faq/categories') }}" class="ui icon button">--}}
                    {{--<i class="list icon"></i> Categorie&euml;n--}}
                    {{--</a>--}}
                </div>

                <div class="right floated sixteen wide mobile six wide computer column">
                    <div class="ui grid">
                        <div class="two column row">
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

        <div class="clearfix"></div>
        <div class="panel panel-default">
            <div class="panel-body">

                <?php echo Form::open(array('id' => 'formList', 'url' => 'admin/'.$slugController.'/reply/' . $data->id, 'method' => 'post')) ?>
                <h1>{{ $data->subject }}</h1>
                <h3>{{ $data->name }} <small>({{ $data->email }})</small></h3>
                <hr>
                <p>
                    {{ $data->content }}
                </p>

                <div class="panel panel-primary">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="reply-contact">Reply Contact</label>
                                    <textarea required name="reply" id="reply" rows="10" class="form-control"></textarea>
                                </div>
                            </div>
                        </div>
                        <button class="btn btn-success pull-right">Reply</button>
                    </div>
                </div>
                <?php echo Form::close(); ?>

            </div>
        </div>
    </div>
    <div class="clear"></div>
@stop

@section("after_styles")
    <link href="{{ asset("css/bootstrap.min.css") }}" rel="stylesheet">

@endsection