@extends('template.theme')

{{--*/ $pageTitle = 'Veelgestelde vragen' /*--}}

@section('slider')
<br>
@endsection

@section('content')
<div class="container">
	<div class="ui breadcrumb">
		<a href="{{ url('/') }}" class="section">Home</a>
		<i class="right chevron icon divider"></i>

		<span class="active section"><h1>Veelgestelde vragen</h1></span>
	</div>

	<div class="ui divider"></div>

	@if (Request::has('step'))
	<div class="ui three mini steps">
		<a href="{{ url('admin/companies/update/'.Request::input('slug').'?step=1') }}" class="link step">
			<i class="building icon"></i>
			<div class="content">
				<div class="title">Bedrijf</div>
			</div>
		</a>

		<a href="{{ url('admin/reservations/create/'.Request::input('slug').'?step=2') }}" class="link step">
			<i class="calendar icon"></i>
			<div class="content">
				<div class="title">Reserveringen</div>
			</div>
		</a>

		<div class="active step">
			<i class="question mark icon"></i>
			<div class="content">
				<div class="title">Veelgestelde vragen</div>
			</div>
		</div>
	</div><br /><br />
	@endif
	<div class="ui grid">
		<div class="row">
			<div class="sixteen wide mobile five wide tablet ten wide computer column">
				@if (count($categories) >= 1)
				<div class="ui normal dropdown button basic">
					<input type="hidden" name="category" value="{{ trim($categoryId) != '' ? $categoryId : '' }}">

					<div class="text">Kies een rubriek</div>
					<i class="dropdown icon"></i>

					<div class="menu">
						@foreach ($categories as $category)
						<a class="item" data-value="{{ $category->id }}" href="{{ url('faq/'.$category->id.'/'.$category->slug) }}">{{ $category->name }}</a>
						@endforeach
					</div>
				</div>
				@endif

				@if (isset($subcategories) && count($subcategories) >= 1)
				<div class="ui normal dropdown button basic">
					<input type="hidden" name="subcategory" value="{{ trim($slug) != '' ? $slug : '' }}">

					<div class="text">Kies een subrubriek</div>
					<i class="dropdown icon"></i>

					<div class="menu">
						@foreach ($subcategories as $subcategory)
						<a class="item" data-value="{{ $subcategory->slug }}" href="{{ url('faq/'.$subcategory->id.'/'.$subcategory->slug) }}">{{ $subcategory->name }}</a>
						@endforeach
					</div>
				</div>
				@endif
			</div>

			<div class="sixteen wide mobile three wide tablet three wide computer right floated column">
				<?php echo Form::open(array('method' => 'GET')) ?>
				<div class="ui action fluid input">
					<input type="text" class="admin search input" name="q" placeholder="Zoeken...">
				    <button class="ui basic icon button admin-search"><i class="search icon"></i></button>
				</div>
				<?php echo Form::close(); ?>
			</div>
		</div>
	</div>


		@if (trim(Request::segment(2) != '' OR Request::has('q')))
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
		@else
			<div class="ui grid two columns">
				<div class="column">
					<a href="{{ url('faq/1/gasten') }}">
						<h2 class="ui center aligned icon header">
						  	<i class="circular users icon"></i>
						  	Consumenten
						</h2>
					</a>
				</div>

				<div class="column">
					<a href="{{ url('faq/3/restaurateurs') }}">
						<h2 class="ui center aligned icon header">
						  	<i class="home circular icon"></i>
						  	Restaurants
						</h2>
					</a>
				</div>
			</div>
		@endif

	@if (trim(Request::segment(2) != '' OR Request::has('q')))
	    @if (count($questions) >= 1)
	        {!! $questions->render() !!}<br />
	    @else
	    Er zijn geen resultaten gevonden.
	    @endif
    @endif
</div>
@stop