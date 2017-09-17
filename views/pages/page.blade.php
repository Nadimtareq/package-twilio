@extends('template.theme')

{{--*/ $pageTitle = $page->title /*--}}
{{--*/ $metaDescription = $page->meta_description /*--}}

@section('content')
<div class="container ">
	<div class="row">
	 <div class="col-md-12 page-fixed">
		{!! str_replace(array('\r\n','\n'), ' ', $page->content) !!}	
	 </div>
	</div>
</div>
@stop