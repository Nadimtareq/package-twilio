@extends('template.theme')

{{--*/ $pageTitle = 'Contact' /*--}}

@section('content')
<div class="container">
	<div class="ui breadcrumb">
		<a href="{{ url('/') }}" class="section">Home</a>
		<i class="right chevron icon divider"></i>

		<span class="active section"><h1>Veelgestelde vragen</h1></span>
	</div>


	<div id="faq" class="ui styled fluid accordion">
		@foreach ($questions as $question)
			<div id="{{ $question->id }}" class="title">
				<i class="dropdown icon"></i>
				{{ $question->title }}
			</div>

			<div class="content">
				<p>{{ $question->answer }}</p>
			</div>
		@endforeach
	</div>

	<div class="ui divider"></div>

	<h3 style="text-align: center" class="red-text">
		Heb je je antwoord gevonden?
		<br>
		<a href="{{ url("contact/faq") }}" class="btn">NO</a>
		<a href="{{ url("contact/faq?req=complete") }}" class="btn">Ja</a>
	</h3>
	<hr>
<style>
	.ui.form textarea{
		padding: 3.78571em 1em;
	}
</style>
@stop