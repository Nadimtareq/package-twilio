@extends('template.theme')

@inject('preference', 'App\Models\Preference')

{{--*/ $pageTitle = 'Vergelijken' /*--}}

@section('content')
	<div class="container">
	     <div class="ui breadcrumb">
	        <a href="{{ url('/') }}" class="sidebar open">Home</a>
	        <i class="right chevron icon divider"></i>

	        <span class="active section"><h1>Vergelijken</h1></span>
	    </div>

	    <div class="ui divider"></div>
	   	
	   	@include('pages.compare.menu')<br />
	</div>
@stop