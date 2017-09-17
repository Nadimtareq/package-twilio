@extends('template.theme')

@section('scripts')
    @include('admin.template.remove_alert')
@stop

@section('content')
    <div class="content">
        <br>
        <br>
        @include('admin.template.breadcrumb')

        <div class="clearfix"></div>
        <div class="panel panel-default">
            <div class="panel-body">

                <h1>{{ $contact->subject }}</h1>
                <h3>{{ $contact->name }} <small>({{ $contact->email }})</small></h3>

                <hr>
                <h4>
                    {{ $contact->content }}
                </h4>
                <hr>

                @foreach($data as $conversation)
                    <div class="box">
                        {{ $conversation->message }}
                    </div>
                @endforeach


                <?php echo Form::open(array('id' => 'formList', 'url' => 'contact/conversation/' . $id . '/' . $email, 'method' => 'post')) ?>

                <br>
                <br>
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="reply-contact">Reply Contact</label>
                                    <textarea required name="reply" id="reply" rows="3" class="form-control"></textarea>
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