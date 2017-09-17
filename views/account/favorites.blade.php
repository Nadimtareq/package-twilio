@extends('template.theme')

@section('content')
<div class="container">
    <div class="ui breadcrumb">
        <a href="{{ url('/') }}" class="section">Home</a>
        <i class="right chevron icon divider"></i>
        <a href="#" class="sidebar open" data-activates="slide-out">Menu</a>
        <i class="right chevron icon divider"></i>
        <div class="active section">Mijn favoriete restaurants</div>
    </div>

    <div class="ui divider"></div>

    <section  id="prices" >
        <div class="container">
            <div class="col-sm-12 col-ms1">
                <div class="col-sm-3 col5">
                    @if (count($companies) >= 1)

                    @include('company-list')

                    {!! $companies->appends($paginationQueryString)->render() !!}

                    <div id="limitSelect" class="ui basic segment">
                        <div class="ui normal floating float-right icon selection dropdown">
                            <i class="dropdown right floated icon"></i>
                            <div class="text">{{ $limit }} resultaten</div>

                            <div class="menu">
                                <a class="item" href="{{ url('/?'.http_build_query(array_add($queryString, 'limit', '15'))) }}">15</a>
                                <a class="item" href="{{ url('/?'.http_build_query(array_add($queryString, 'limit', '30'))) }}">30</a>
                                <a class="item" href="{{ url('/?'.http_build_query(array_add($queryString, 'limit', '45'))) }}">45</a>
                            </div>
                        </div>
                    </div>

                    @else
                    <div class="ui three mini steps">
                        <a href="{{ url('/') }}" class="link step">
                            <i class="search icon"></i>
                            <div class="content">
                                <div class="title">Zoek een restaurant uit naar keuze</div>
                            </div>
                        </a>

                        <div class="step">
                            <i class="save icon"></i>
                            <div class="content">
                                <div class="title">Klik op de diskette</div>
                            </div>
                        </div>

                        <div class=" step">
                            <i class="check mark icon"></i>
                            <div class="content">
                                <div class="title">Sla het op als favoriet</div>
                            </div>
                        </div>
                    </div>
                    @endif					
                </div>
            </div>
        </div>
</div>
</section>
<!--		
<div id="companies" class="content">
if (count($companies) >= 1)
<div class="companies home">
        include('company-list')

<div class="clear"></div>
        <div class="ui grid container">
<div class="left floated sixteen wide mobile ten wide computer column">
    {! with(new \App\Presenter\Pagination($companies->appends($paginationQueryString)))->render() !!}
</div>

<div class="right floated sixteen wide mobile sixteen wide tablet three wide computer column">
    <div id="limitSelect" class="ui basic segment">
        <div class="ui normal floating icon selection compact dropdown">
            <i class="dropdown right floated icon"></i>
            <div class="text">{{ $limit }} resultaten</div>
             
            <div class="menu">
                <a class="item" href="{{ url('/?'.http_build_query(array_add($queryString, 'limit', '15'))) }}">15</a>
                <a class="item" href="{{ url('/?'.http_build_query(array_add($queryString, 'limit', '30'))) }}">30</a>
                <a class="item" href="{{ url('/?'.http_build_query(array_add($queryString, 'limit', '45'))) }}">45</a>
            </div>
        </div>
    </div>
</div>
</div>
</div>
else
<div class="ui three mini steps">
<a href="{{ url('/') }}" class="link step">
<i class="search icon"></i>
<div class="content">
    <div class="title">Zoek een restaurant uit naar keuze</div>
</div>
</a>

<div class="step">
<i class="save icon"></i>
<div class="content">
    <div class="title">Klik op de diskette</div>
</div>
</div>

<div class=" step">
<i class="check mark icon"></i>
<div class="content">
    <div class="title">Sla het op als favoriet</div>
</div>
</div>
</div><br /><br />
endif
</div>
</div> -->
@stop