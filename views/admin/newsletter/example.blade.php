@extends('template.theme')

@section('content')
<div class="content">
    @include('admin.template.breadcrumb')
	
	<div class="ui three mini steps">
		<a href="{{ url('admin/newsletter?id='.Request::input('id').'&step=1') }}" class="link step">
			<i class="paint brush icon"></i>
			<div class="content">
				<div class="title">Lay-out</div>
			</div>
		</a>

		<a href="{{ url('admin/newsletter/guests?id='.Request::input('id').'&step=2') }}" class="link step">
			<i class="users icon"></i>
			<div class="content">
				<div class="title">Gasten</div>
			</div>
		</a>

		<a href="{{ url('admin/newsletter/example?id='.Request::input('id').'&step=3') }}" class="link active step">
			<i class="image icon"></i>
			<div class="content">
				<div class="title">Voorbeeld</div>
			</div>
		</a>
	</div><br /><br />


    <?php echo Form::open(array('url' => 'admin/newsletter/example?id='.Request::input('id'), 'method' => 'post', 'class' => 'ui form')); ?>
	<button class="ui button" name="send" value="send" type="submit">
		<i class="envelope icon"></i> Verstuur
	</button>
		
	<?php echo Form::close(); ?>

</div>
@stop