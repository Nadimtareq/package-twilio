@extends('template.theme')

@inject('affiliate', 'App\Models\Affiliate')
@inject('strHelper', 'App\Helpers\StrHelper')

{{--*/ $pageTitle = (isset($contentBlock[1]) ? strip_tags($contentBlock[1]) : '') /*--}}

@section('content')
    <?php
    $breadcrumbArray1 = (Request::has('preference') ? Request::get('preference') :($userAuth && count($user->preferences) >= 1 && $user->preferences != 'null' && $user->preferences != null ? json_decode($user->preferences) : array()));
    $breadcrumbArray2 = (Request::has('kitchen') ? Request::get('kitchen') : ($userAuth && count($user->kitchens) >= 1 && $user->kitchens != 'null' && $user->kitchens != null ? json_decode($user->kitchens) : array()));
    $breadcrumbArray3 = (Request::has('price') ? Request::get('price') : ($userAuth && count($user->price) >= 1 && $user->price != 'null' && $user->price != null ? json_decode($user->price) : array()));
    $breadcrumbArray4 = (Request::has('discount') ? Request::get('discount') : ($userAuth && count($user->discount) >= 1 && $user->discount != 'null' && $user->discount != null ? json_decode($user->discount) : array()));
    $breadcrumbArray5 = (Request::has('allergies') ? Request::get('allergies') : ($userAuth && count($user->allergies) >= 1 && $user->allergies != 'null' && $user->allergies != null ? json_decode($user->allergies) : array()));
    $arrayMerge = array_filter(array_merge($breadcrumbArray1, $breadcrumbArray2, $breadcrumbArray3, $breadcrumbArray4, $breadcrumbArray5));
    ?>

    @if ($userAuth == FALSE)
        <div class="clear"></div>
        {{--<h2  style="color: blue" class="home login header" data-type="login">"Meld je nu aan en spaar direct!"</h2>--}}

        <div class="container">
            {!! isset($contentBlock[48]) ? $contentBlock[48] : '' !!}

            <div class="ui three column stackable grid">
                @foreach ($cities as $city)
                    <?php $media = $city->getMedia(); ?>
                    <div class="column">
                        <div class="card">
                            <a href="{{ url('search?regio='.$city->slug) }}">
                                @if (isset($media[0]))
                                    <img class="ui image" src="{{ url(''.$media[0]->getUrl('thumb')) }}" alt="{{ $city->name }}">
                                @else
                                    <img class="ui image" src="{{ url('images/placeholdimage.png') }}" alt="{{ $city->name }}">
                                @endif
                                
                                <h4>{{ $city->name }}</h4>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div><br />

            @if (count($cities) > 6)
            <div class="ui three column centered grid">
                <div class="column">
                    <button id="loadMoreHome" class="ui fluid blue labeled icon button">
                        <i class="arrow cicle outline left down icon"></i>
                        Meer steden laden
                    </button>
                </div>
            </div>
            @endif

            <div class="clear"></div>
        </div>
    @else
    <div class="container">
        @if(count($arrayMerge) >= 1)
        <div class="ui grid container">
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

    <div class="content">
        <div class="ui equal width responsive grid container">
            <div class="column">
                {!! isset($contentBlock[2]) ? $contentBlock[2] : '' !!}
            </div>
            <div class="column">
                {!! isset($contentBlock[3]) ? $contentBlock[3] : '' !!}
            </div>
        </div> 

        <div id="typeBar" class="ui basic segment"> 
            <select id="typePage" class="multipleSelect">
                <option value="1" selected>Restaurant</option>
                <option value="2">Nieuwsberichten</option>
                <option value="3">Kortingscodes</option>
                <option value="4">Tegoed sparen</option>
                <option value="5">Veelgestelde vragen</option>
            </select>
        </div>
    </div>

    <div class="container">
        <div class="filter toolbar">
            <?php echo Form::open(array('url' => 'preferences?type=home', 'method' => 'post', 'class' => 'ui form')); ?>
            <div class="six fields">
                <div class="field">
                    <?php 
                    echo Form::select(
                        'preference[]', 
                        (isset($preference[1]) ? $preference[1] : array()),  
                        (Request::has('preference') ? Request::get('preference') : ($user && $user->preferences != NULL ? json_decode($user->preferences) : '')), 
                        array('class' => 'multipleSelect', 'data-placeholder' => 'Voorkeuren', 'multiple' => 'multiple')
                    ); 
                    ?>
                </div>

                <div class="field">
                    <?php 
                    echo Form::select(
                        'kitchen[]', 
                        (isset($preference[2]) ? $preference[2] : array()),  
                        (Request::has('kitchen') ? Request::get('kitchen') : ($user && $user->kitchens != NULL ? json_decode($user->kitchens) : '')), 
                        array('class' => 'multipleSelect', 'data-placeholder' => 'Keuken', 'multiple' => 'multiple')
                    ); 
                    ?>
                </div>

                <div class="field">
                    <?php 
                    echo Form::select(
                        'price[]', 
                        (isset($preference[4]) ? $preference[4] : array()),  
                        (Request::has('price') ? Request::get('price') : ($user && $user->price != NULL ? json_decode($user->price) : '')), 
                        array('class' => 'multipleSelect', 'data-placeholder' => 'Soort', 'multiple' => 'multiple')
                    ); 
                    ?>
                </div>

                <div class="field">           
                    <?php 
                    echo Form::select(
                        'discount[]', 
                        (isset($preference[5]) ? $preference[5] : array()),  
                        (Request::has('discount') ? Request::get('discount') : ($user && $user->discount != NULL ? json_decode($user->discount) : '')), 
                         array(
                            'class' => 'multipleSelect', 
                            'data-placeholder' => 'Korting', 
                            'multiple' => 'multiple'
                        )
                    ); 
                    ?>
                </div>

                <div class="field">
                    <?php echo Form::select(
                        'allergies[]', 
                        (isset($preference[3]) ? $preference[3] : array()),  
                        (Request::has('allergies') ? Request::get('allergies') : ($user && $user->allergies != NULL ? json_decode($user->allergies) : '')), 
                        array('class' => 'multipleSelect', 'data-placeholder' => 'Allergieen',  'multiple' => 'multiple')
                    ); ?>
                </div>

                <div class="field">
                    <input type="submit" class="ui blue fluid filter button" value="Filteren" />
                </div>
            </div>
            <?php echo Form::close() ?>
        </div>
        <div class="clear"></div>
    </div>

    <div id="companies" class="content">
        <div class="left section">
            <div id="optionOne" class="companies home">
                @include('deal-list')<br />
            </div>

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
                        Er zijn geen nieuwsberichten gevonden
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
                            Er zijn nog geen kortingscodes
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="right section">
{{--            @include('template.sidebar')--}}
        </div>

        <div class="ui grid container">
            <div class="left floated sixteen wide mobile ten wide computer column">
                {!! with(new \App\Presenter\Pagination($companies->appends($paginationQueryString)))->render() !!}
            </div>

            <div class="right floated sixteen wide mobile sixteen wide tablet three wide computer column">
                <div id="limitSelect">
                    <div class="ui normal floating icon selection fluid dropdown">
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

        <div class="clear"></div>
    </div>
    @endif
@stop