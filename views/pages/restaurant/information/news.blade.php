<h5 class="ui header thin"><i class="icon newspaper"></i><div class="content">Nieuwsberichten</div></h5>
@if($news->count() >= 1)
		<div class="ui items">
			@foreach($news as $article)
			<div class="item">
	    		<?php $newsMedia = $article->getMedia(); ?>
				<div class="image">
				    @if($newsMedia != '[]')
	                    <img class="ui small image" src="{{ url('public/'.$newsMedia->last()->getUrl()) }}" />
	                @elseif($media != '[]')
	                	  <img class="ui small image" src="{{ url('public/'.$media->last()->getUrl()) }}" />
	                @endif
				</div>
				
				<div class="content">
				   	<a href="{{ url('news/'. $article->slug) }}" class="header"><h4>{{ $article->title }}</h4></a>
		           	<div class="description">Geplaatst op {{ date('d-m-Y H:i:s', strtotime($article->created_at)) }}</div><br />
		              
		           	<p>{{ implode(' ', array_slice(explode(' ', strip_tags($article->content)), 0, 100)) }}...</p>
				  	<a href="{{ url('news/'. $article->slug) }}">Lees meer</a><br /><br />
				</div>
			</div>
	   		@endforeach
   		</div>
   		{!! $news->appends($paginationQueryString)->render() !!}
   	@else
   		Er zijn geen nieuwsberichten gevonden.
   	@endif