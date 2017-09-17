@extends('template.theme')

{{--*/ $pageTitle = 'Nieuws' /*--}}

@section('content')
<div class="container">
	<div class="ui breadcrumb">
		<a href="{{ url('/') }}" class="section">Home</a>
		<i class="right chevron icon divider"></i>
		<div class="active section"><h1>Nieuws</h1></div>
	</div>

	<div class="ui divider"></div> 

	@if($news->count() >= 1)
		<div id="news">
			@foreach($news as $article)
				<div class="item">
		    		<?php $media = $article->getMedia(); ?>
					<div class="image">
					    @if($media != '[]')
		                    <img src="{{ url('public'.$media->last()->getUrl('175Thumbstretch')) }}" />
		                @elseif(isset($companyImage[$article->company_id]))
		                	<img class="ui small image" src="{{ url('public/'.$companyImage[$article->company_id]) }}" />
		                @else
                			<img src="{{ url('public/images/placeholdimage.png') }}" />
		                @endif
					</div>
					
					<div class="item-content">
					   	<a href="{{ url('news/'. $article->slug) }}" class="header"><h3>{{ $article->title }}</h3></a>
			           	<div class="description">Geplaatst op {{ date('d-m-Y H:i:s', strtotime($article->created_at)) }}</div><br />
			              
					  	<p>{{ implode(' ', array_slice(explode(' ', strip_tags($article->content)), 0, 100)) }}...</p>
					  	<a href="{{ url('news/'. $article->slug) }}">Lees meer</a><br><br>
					</div>
	   			</div>
		   	@endforeach
   		</div>
   		{!! $news->appends($paginationQueryString)->render() !!}

   		<div style="clear: both"></div>
   	@else
   		Er zijn geen nieuwsberichten gevonden.
   	@endif
@stop