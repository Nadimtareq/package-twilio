@extends('template.theme')

{{--*/ $pageTitle = $news->title /*--}}
{{--*/ $metaDescription = $news->meta_description /*--}}

@section('content')
<div class="container">
	<div class="ui breadcrumb">
		<a href="{{ url('/') }}" class="section">Home</a>
		<i class="right chevron icon divider"></i>

		<a href="{{ url('news') }}" class="section">Nieuws</a>

		<i class="right chevron icon divider"></i>
		<div class="active section"><h1>{{ $news->title }}</h1></div>
	</div>
	<div class="ui divider"></div>

	<div id="news">
		<div class="item">
			<div class="image">
				@if($media != '[]')
				    <img src="{{ url('public'.$media->last()->getUrl('175Thumbstretch')) }}" />
				@elseif	(count($company) == 1)
					@if($company->getMedia('default') != '[]')
				    <img class="ui small image" src="{{ url('public/'.$company->getMedia()->last()->getUrl('175Thumb')) }}" />
					@endif
				@else
                	<img src="{{ url('public/images/placeholdimage.png') }}" />
	            @endif
			</div>
				
			<div class="item-content">
				<div class="sub header">Geplaatst op {{ date('d-m-Y H:i:s', strtotime($news->created_at)) }}</div>
		  	
				{!! $news->content !!}
			</div>
		</div>
   	</div>	
   	<div class="clear"></div>
</div>
@stop