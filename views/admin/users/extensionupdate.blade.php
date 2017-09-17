@extends('template.theme')

@inject('cityPref', 'App\Models\Preference')

@section('content')
<div class="content">

    @if (isset($data))
    @include('admin.template.breadcrumb')
	<?php echo Form::open(array('url' => 'admin/'.$slugController.'/list/update/'.$data->id, 'method' => 'post', 'class' => 'ui edit-changes form')) ?>
		
		<div class="fields">
			<div class="four wide field">
			   	<label>staat</label>
				<?php echo Form::select('status',  array(1 => 'in staat stellen', 0 => 'onbruikbaar maken'), $data->id1, array('class' => 'multipleSelect')); ?>
			</div>

			<div class="twelve wide field">
			    <label>Uitbreiding</label>
			    <?php echo Form::text('extension', $data->email_extension) ?>
			</div>
		</div>

		


		 <button class="ui tiny button" type="submit"><i class="pencil icon"></i> Wijzigen</button>
		 <a href="{{ url('admin/ban/create/'.$data->id) }}" class="ui tiny red button" type="submit"><i class="ban icon"></i> Verbannen</a>
	<?php echo Form::close(); ?>
	@endif
</div>
<div class="clear"></div>
@stop
