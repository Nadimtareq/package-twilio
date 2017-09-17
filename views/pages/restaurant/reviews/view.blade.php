 @extends('template.theme')

@section('content')
@if (count($review) == 1)
	<br />
 	<div class="ui grid container review">
		<div class="ui breadcrumb">
			<a href="{{ url('/') }}" class="section">Home</a>
			<i class="right arrow icon divider"></i>
			<div class="active section">Recensie delen</div>
		</div>
	</div>

 	<div class="ui grid container review">
 		<div class="sixteen wide mobile one wide tablet six wide computer column">
 			<h4>Deel deze recensie</h4>
 			<span class="addthis_sharing_toolbox"></span>
 		</div>

		 

 		<div class="sixteen wide mobile one wide tablet six wide computer column">

 			<div class="ui vertically grid reviews">
			 	<div class="four wide column center aligned">
			 		<div class="ui left pointing blue huge label">
			 			{{ $reviewModel->getAverage(array($review->food,  $review->service, $review->decor)) }}
			 		</div><br /><br />
			 		{{ $reviewModel->countReviews($review->user_id) }} {{ $reviewModel->countReviews($review->user_id) == 1 ? 'recensie' : 'recensies' }}
			 	</div>

			 	<div class="twelve wide column">
			 		<div class="review">
			 			<div class="author">
			 				<h3>{{ $review->name }}</h3>
			 				<small>{{ date('d-m-Y', strtotime($review->created_at)) }}</small>
			 			</div>
			 			<div class="clear"></div>

			 			<p>{{ $review->content }}</p>

			 			<div class="score">
			 				Eten <div class="ui star tiny orange rating no-rating whitee" data-rating="{{ $review->food }}"></div><br />
			 				Service <div class="ui star tiny orange rating no-rating whitee" data-rating="{{ $review->service }}"></div><br />
			 				decor <div class="ui star tiny orange rating no-rating whitee" data-rating="{{ $review->decor }}"></div>
			 			</div>
			 		</div>
			 	</div>
			</div>

			<div class="clear"></div><br />
		</div>
	</div>


	<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-5751e9a264890504"></script>
	<script type="text/javascript">
	var addthis_share = {
	   url: "{{ url('review/'.$review->id) }}",
	   title: "{{ substr($review->content, 0, 100) }}..."
	}
	</script>
@else
	<div class="ui basic padded segment">
		Er zijn nog geen recensies gegeven. Hier gegeten? Laat je recensie achter!
	</div>
@endif
@stop