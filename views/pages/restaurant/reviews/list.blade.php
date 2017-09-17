 @if(count($reviews) >= 1)
 	@foreach($reviews as $review)
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
 				Eten <div class="ui star tiny orange rating no-rating" data-rating="{{ $review->food }}"></div><br />
 				Service <div class="ui star tiny orange rating no-rating" data-rating="{{ $review->service }}"></div><br />
 				decor <div class="ui star tiny orange rating no-rating" data-rating="{{ $review->decor }}"></div> 
 			</div>
 		</div>
 	</div>
	@endforeach
@else
	<div class="ui basic padded segment">
		Er zijn nog geen recensies gegeven. Hier gegeten? Laat je recensie achter!
	</div>
@endif