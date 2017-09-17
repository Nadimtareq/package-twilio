@extends('template.theme')

{{--*/ $pageTitle = 'Zoeken' /*--}}
@section("header_picture")
   @include('pages._search-slider')
@endsection 
@section('content')
    
    <script type="javascript">
        var searchPage = 1;
    </script>
    <div class="clearfix"></div>
    @include('pages.search-filter') 

      {{--  @include('pages._top-filter') --}}

    <div class="clearfix"></div>   

    <section  id="search-list" >
        <div class="container">
            <div class="col-sm-12 col-ms1">
                <div class="col-sm-3 col5">
                    @if (count($companies) >= 1)
{{--  
                        <div class="search-layout">
                            <div class="pull-right">

                                <span class="rs">View</span>

                                <a href="{{ url("search?regio=eindhoven&layout=grid") }}" class="active">
                                    <img src="{{ url("images/one.png") }}" alt="one">
                                </a>

                                <a href="{{ url("search?regio=eindhoven") }}">
                                    <img src="{{ url("images/two.png") }}" alt="two">
                                </a>

                            </div>
                        </div>  --}}

                        @include('company-list')

                        @if (count($recommended) >= 1)
                            <h3 class="ui header">Zie ook</h3>

                            @include('company-list-more', ['viewType' => 'more'])
                        @endif
					
                        @if($countCompanies >= 16)
                            <div id="limitSelect" class="ui basic segment">
                                <div class="ui normal floating icon selection dropdown">
                                    <i class="dropdown right floated icon"></i>
                                    <div class="text">{{ $limit }} resultaten weergeven</div>

                                    <div class="menu">
                                        <a class="item" href="{{ url('search?'.http_build_query(array_add($queryString, 'limit', '15'))) }}">15</a>
                                        <a class="item" href="{{ url('search?'.http_build_query(array_add($queryString, 'limit', '30'))) }}">30</a>
                                        <a class="item" href="{{ url('search?'.http_build_query(array_add($queryString, 'limit', '45'))) }}">45</a>
                                    </div>
                                </div>
                            </div>
                        @endif
                        {!! $companies->appends($paginationQueryString)->render() !!}
                    @else
                        Er zijn geen restaurants gevonden met uw selectiecreteria.
                    @endif
                </div>
            </div>

            {{--   <div class="right section">
               @include('template.sidebar')
             </div>  --}}
        </div>
    </section>

    <div class="clear"></div>
@stop

@section("after_styles")
     <link href="{{ asset("css/custom.css") }}" rel="stylesheet"> 
@endsection