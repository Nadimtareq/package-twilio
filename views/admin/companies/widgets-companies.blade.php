@extends('template.theme')

@section('content')
<div class="content">
    @include('admin.template.breadcrumb')

    <div class="ui normal floating basic search selection large dropdown">
       	<div class="text">Filter op bedrijf</div>
        <i class="dropdown icon"></i>

		    <div class="menu">
            @foreach($companies as $company)
            <a class="item" href="{{ url('admin/widgets/'.$company->slug) }}">{{ $company->name }}</a>
            @endforeach
       </div>
    </div>
</div>
<div class="clear"></div>
@stop
