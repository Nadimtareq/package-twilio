@extends('template.theme')

@section('scripts')
	<script type="text/javascript">
		$('#image').cropper({
			preview: '.img-preview',
	  		crop: function(e) {
	    		// Output the result data for cropping image.
	    		$('input[name="width"]').val(e.width);
	    		$('input[name="height"]').val(e.height);
	    		$('input[name="left"]').val(e.x);
	    		$('input[name="top"]').val(e.y);
	  		}
		});
	</script>
@stop

@section('content')
<div class="content">
    @include('admin.template.breadcrumb')

	<?php echo Form::open(array('url' => 'admin/companies/crop/image/'.$slug.'/'.$image.'?type='.Request::input('type'), 'method' => 'post', 'class' => 'ui form', 'files' => true)) ?>
	    <input type="" name="type" value="{{ Request::has('type') ? Request::input('type') : '' }}">
	    <input type="" name="width">
	    <input type="" name="height">
	    <input type="" name="left">
	    <input type="" name="top">
	     
	    <div class="ui grid">
		  	<div class="twelve wide column">
			  	<div>
			  		<img id="image" src="{{ url('public'.$mediaItem->getUrl('mobileThumb')) }}">
				</div>
			</div>

		  	<div class="four wide column">
				<div class="docs-preview clearfix">
					<div class="">
				        <div class="img-preview preview-lg"></div>
				        <div class="clear"></div>
				    </div>
			    </div>
			</div>
			
			<button class="ui tiny button" type="submit"><i class="pencil icon"></i> Wijzigen</button>
		</div>
	<?php echo Form::close(); ?>
@stop