@extends('template.theme',['search_header' => ($userAuth != FALSE) ])


@inject('affiliate', 'App\Models\Affiliate')
@inject('strHelper', 'App\Helpers\StrHelper')
@inject('FileHelper', 'App\Helpers\FileHelper')

{{--*/ $pageTitle = (isset($contentBlock[1]) ? strip_tags($contentBlock[1]) : '') /*--}}

@section('content')

<?php
$breadcrumbArray1 = (Request::has('preference') ? Request::get('preference') : ($userAuth && count($user->preferences) >= 1 && $user->preferences != 'null' && $user->preferences != null ? json_decode($user->preferences) : array()));
$breadcrumbArray2 = (Request::has('kitchen') ? Request::get('kitchen') : ($userAuth && count($user->kitchens) >= 1 && $user->kitchens != 'null' && $user->kitchens != null ? json_decode($user->kitchens) : array()));
$breadcrumbArray3 = (Request::has('price') ? Request::get('price') : ($userAuth && count($user->price) >= 1 && $user->price != 'null' && $user->price != null ? json_decode($user->price) : array()));
$breadcrumbArray4 = (Request::has('discount') ? Request::get('discount') : ($userAuth && count($user->discount) >= 1 && $user->discount != 'null' && $user->discount != null ? json_decode($user->discount) : array()));
$breadcrumbArray5 = (Request::has('allergies') ? Request::get('allergies') : ($userAuth && count($user->allergies) >= 1 && $user->allergies != 'null' && $user->allergies != null ? json_decode($user->allergies) : array()));
$arrayMerge = array_filter(array_merge($breadcrumbArray1, $breadcrumbArray2, $breadcrumbArray3, $breadcrumbArray4, $breadcrumbArray5));
?>


@if ($userAuth == FALSE)
<div class="clear"></div>
{{--<h2  style="color: blue" class="home login header" data-type="login">"Meld je nu aan en spaar direct!"</h2>--}}
<div id="cities">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 tabs-content">
                {!! isset($contentBlock[48]) ? $contentBlock[48] : '' !!}
                @foreach ($cities as $city)
                <?php $media = $city->getMedia(); ?>

                <div class="col-sm-4 col-xs-12 col4">
                    <a href="{{ url('search?regio='.$city->slug) }}">
                        @if (isset($media[0]) && $FileHelper::is_url_exist(url(''.$media[0]->getUrl('thumb'))))

                        <img class="ui image" src="{{ url(''.$media[0]->getUrl('thumb')) }}" alt="{{ $city->name }}">
                        @else
                        <img class="ui image" src="{{ url('images/placeholdimage.png') }}" alt="{{ $city->name }}" data-url="{{ url(''.$media[0]->getUrl('thumb')) }}">
                        @endif
                        <p>{{ $city->name }}</p>
                    </a>
                </div>
                @endforeach
            </div>
            <div class="clear"></div>

            @if (count($cities) > 6)
            <button id="loadMoreHome" class="more wr2" >
                <i class="arrow cicle outline left down icon"></i>
                MEER STEDEN BEKIJKEN
            </button>
            @endif

        </div>
    </div>
</div>

@if(count($affiliates) >= 1)
<section id="partners">

    <div class="container">
        <div class="row">
            <div class="col-sm-12 col-xs-12">
                <h1>SPAAR TOT WEL 10% BIJ 2000+ WEBSHOPS, O.A:</h1>
                @foreach ($affiliates as $data)
                <div class="col-md-2 col-sm-4 col-xs-12">
                    <div class="partner">
                        <a href="{{ url('tegoed-sparen/company/'.$data['name']) }}">
                            <span class="partner2"><h1>{{ $data['comissions'] }}</h1>Max. spaartegoed</span>
                            @if (isset($media[0]) && $FileHelper::is_url_exist(url(''.$media[0]->getUrl())))
                            <img src="{{ asset('images/affiliates/'.$data['affiliate_network'].'/'.$data['program_id'].'.'.$data['image_extension']) }}" alt="p1">
                            @else
                            <img src="{{ url('images/placeholdimage.png') }}" alt="p1">
                            @endif
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
@endif

@else

<div class="container">
    @if(count($arrayMerge) >= 1)
    <div class="ui grid container blueseal-text">
        <div class="left floated sixteen wide mobile ten wide computer column">
            U zoekt nu op &nbsp;
            @foreach($arrayMerge as $item)
            <div class="ui label">{{ urldecode(ucfirst($item)) }}</div>
            @endforeach
        </div>

        <div class="right floated sixteen wide mobile two wide computer column">
            <button id="homePrefrencesButton" class="ui display filter basic blue fluid tiny button">Aanpassen</button>
        </div>
    </div>
    @endif
</div>

<div class="{!! (Route::getCurrentRoute()->uri() !== '/') ? 'side_menus' : '' !!}">
    <?php echo Form::open(array('url' => 'preferences?type=home', 'method' => 'post', 'class' => 'ui form')); ?>

    @if(Request::has('date'))
    <input type="hidden" name="date" value="<?php echo Request::get('date'); ?>" />
    @endif

    @if(Request::has('time'))
    <input type="hidden" name="time" value="<?php echo Request::get('time'); ?>" />
    @endif

    @if(Request::has('time'))
    <input type="hidden" name="time_format" value="<?php echo date('Hi', strtotime(Request::get('time'))); ?>" />
    @endif
    <input type="hidden" id="typePage" name="typePage" value="1" />
    <input type="hidden" name="q" value="<?php echo Request::get('q'); ?>" />
    @if(Request::has('persons'))
    <input type="hidden" name="persons" value="<?php echo Request::get('persons'); ?>" />
    @endif

    <div class="content">
        <div class="static-menu row">
            <div class="jsearch col-md-2 col-sm-2 col-xs-6">
                <?php $data=''; ?>
                    @if(isset($preference[9]))
                    <select name="discount[]" class = 'multipleSelect' id='city' onchange="javascript:handleSelect(this)">
                        <option value=""><a href="#" >Stad</a></option>
                        @foreach ($preference[9] as $key=>$pre)
                        <option value="{{$key}}"><a href="#" >{{$pre}}</a></option>
                        @endforeach
                    </select>
                       @endif
            </div>
            <div class="jsearch col-md-2 col-sm-2 col-xs-6" >
                {{ Form::select('preference[]',
								(isset($preference[1]) ? $preference[1] : array()),
								(Request::has('preference') ? Request::get('preference') : ''),
								array('class' => 'multipleSelect', 'data-placeholder' => 'Voorkeuren', 'multiple' => 'multiple')) }}

            </div>
            <div class="jsearch col-md-2 col-sm-2 col-xs-6">
                {{  Form::select('kitchen[]',
								(isset($preference[2]) ? $preference[2] : array()),
								(Request::has('kitchen') ? Request::get('kitchen') : ($user && $user->kitchens != NULL ? json_decode($user->kitchens) : '')),
								array('class' => 'multipleSelect', 'data-placeholder' => 'Keuken', 'multiple' => 'multiple')) }}
            </div>
            <div class="jsearch col-md-2 col-sm-2 col-xs-6">
                {{ Form::select('price[]',
                                        (isset($preference[4]) ? $preference[4] : array()),
                                        (Request::has('price') ? Request::get('price') : ($user && $user->price != NULL ? json_decode($user->price) : '')),
                                        array('class' => 'multipleSelect', 'data-placeholder' => 'Soort', 'multiple' => 'multiple')) }}
            </div>
            <!-- 
            <div class="jsearch col-md-2 col-sm-2 col-xs-6">
                {{ Form::select('discount[]',
                                        (isset($preference[5]) ? $preference[5] : array()),
                                        (Request::has('discount') ? Request::get('discount') : ($user && $user->discount != NULL ? json_decode($user->discount) : '')),
                                        array('class' => 'multipleSelect', 'data-placeholder' => 'Korting', 'multiple' => 'multiple')) }}

            </div>
            -->
            <div class="jsearch col-md-2 col-sm-2 col-xs-6">
                {{ Form::select('allergies[]',
										(isset($preference[3]) ? $preference[3] : array()),
										(Request::has('allergies') ? Request::get('allergies') : ($user && $user->allergies != NULL ? json_decode($user->allergies) : '')),
										array('class' => 'multipleSelect', 'data-placeholder' => 'Allergieen',  'multiple' => 'multiple')) }}
            </div>
            <div class="jsearch col-md-2 col-sm-2 col-xs-12">
                <input type="submit" class="ui bluelink fluid filter button" value="Filteren" />
            </div>
        </div>

    </div>
    <?php echo Form::close() ?>
</div>

<section  id="prices" class="home_price_div">
    <div class="container">
        <div class="col-sm-12 col-ms1">
            <div class="col-sm-3 col5">

                @if (count($companies) >= 1)

                @include('company-list-home')

                <div class="ui vertically divided grid container">
                    <div class="row mobile only">
                        <div id="optionTwo" class="fourteen wide mobile seven wide tablet seven wide computer computer column">
                            <h3 class="ui small header">Populairste nieuwsberichten</h3>
                            @if(count($news) >= 1)
                            <div class="ui very relaxed divided selection list">
                                @foreach($news as $article)
                                <div class="item">
                                    <i class="angle right icon"></i>
                                    <div class="content">
                                        <a href="{{ url('news/'. $article->slug) }}" class="header"><h4>{{ $article->title }}</h4></a>
                                        <div class="description">Geplaatst op {{ date('d-m-Y H:i:s', strtotime($article->created_at)) }}</div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @else
                            {!! "Er zijn geen nieuwsberichten gevonden" !!}
                            @endif
                        </div>

                        <div id="optionThree" class="fourteen wide mobile seven wide tablet seven wide computer column">
                            <h3 class="ui small header">Populairste kortingscodes</h3>
                            @if(count($affiliates) >= 1)
                            <div class="ui grid container">
                                @foreach ($affiliates as $data)
                                <?php $media = $data->getMedia(); ?>
                                <div class="four columns row">
                                    <div class="six wide mobile five wide computer column">
                                        <a href="{{ url('tegoed-sparen/company/'.$data->slug) }}"><h4>{{ $data->name }}</h4></a>
                                    </div>

                                    <div class="one wide right floated column">
                                        <a href="{{ url('tegoed-sparen/company/'.$data->slug) }}"><i class="angle right grey large icon"></i></a>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @else
                            {!! "Er zijn nog geen kortingscodes" !!}
                            @endif
                        </div>
                    </div>
                </div>

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
                {!! "Er zijn geen restaurants gevonden met uw selectiecreteria." !!}
                @endif
            </div>
        </div>
    </div>
</section>

@endif

@stop
