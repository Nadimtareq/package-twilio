@extends('template.theme')


@inject('discountHelper', 'App\Helpers\DiscountHelper')
@inject('companyReservation', 'App\Models\companyReservation')
@inject('FileHelper', 'App\Helpers\FileHelper')

<?php use App\Http\Controllers\HomeController; $i = 0; ?>
@section("header_picture")
@include('pages._search-slider') 
 
@endsection  

@section('content')
<script type="javascript">
        var searchPage = 1;
    </script>


<div class="clearfix"></div>
     @include('pages.search-filter')
    <div class="clearfix"></div>
    <section>
        <div class="booking_posting">
            <div class="container">
                <div class="ro">
                    @foreach ($companies as $data)
                        @foreach ($data->ReservationOptions2()->get() as $index => $deal)

                            <?php
                            $media = $data->getMedia('default');
                            $getRec = HomeController::getPersons($deal->id);
                            $count_persons = $getRec[0]->total_persons;
                            /** 270717 **/
                            if($count_persons >= $deal->total_amount)
                                continue;
                            ?>

                             {{--  @if($index % 3 == 0 || $index == 0)
                           {{$index}}
                                {{--  <div class="row">  --}
                                    @endif   --}}
                                @if($index == 0)
                                    {{--  @if($index == 0)
                                       <div class="row">
                                    @else
                                        </div>
                                        <div class="row">
                                    @endif  --}}
                                     <div class="row">
                                @endif
                                    <div class="col-md-4 col-xs-12">
                                     
                                        <div class="back_pric_ground company"
                                             data-kitchen="{{ is_array(json_decode($data->kitchens)) ? str_slug(json_decode($data->kitchens)[0]) : '' }}"
                                             data-url="{{ url('restaurant/'.$data->slug) }}"
                                             data-name="{{ $data->name }}"
                                             data-address="{{ $data->address }}"
                                             data-city="{{ $data->city }}"
                                             data-zipcode="{{ $data->zipcode }}">

                                            <div class="price_img_sec">

                                                {{--<img src="images/img_1.jpg" class="img-responsive" alt="img">--}}


                                                @if (isset($media[0]) && isset($media[0]->file_name) && file_exists(public_path($media[0]->disk. DIRECTORY_SEPARATOR . $media[0]->id . DIRECTORY_SEPARATOR . $media[0]->file_name)) )
                                                    @if($count_persons >= $deal->total_amount)
                                                        <img src="{{ url('media/'.$media[0]->id.'/'.$media[0]->file_name) }}"
                                                             alt="{{ $data->name }}" class="img-responsive"/>
                                                    @else
                                                        <a href="{{ url('restaurant/'.$data->slug).'?deal='.$deal->id }}"
                                                           title="{{ $data->name }}" style="position: relative;">
                                                            <img src="{{ url('media/'.$media[0]->id.'/'.$media[0]->file_name) }}"
                                                                 alt="{{ $data->name }}" class="img-responsive" style="opacity: .7;"/>
                                                        </a>
                                                    @endif
                                                @else

                                                    @if($deal->image != null  &&  file_exists(public_path('images/deals/'  . $deal->image)))
                                                        <a href="{{ url('restaurant/'.$data->slug).'?deal='.$deal->id }}"
                                                           title="{{ $data->name }}" data-url="" style="position: relative;">
                                                            <img src="{{ url('images/deals/' . $deal->image) }}" alt="{{ $data->name }}" class="img-responsive" />

                                                        </a>
                                                    @else
                                                        <a href="{{ url('restaurant/'.$data->slug).'?deal='.$deal->id }}"
                                                           title="{{ $data->name }}" data-url="" style="position: relative;">
                                                            <img src="{{ url('images/placeholdimagerest.png') }}" alt="{{ $data->name }}"
                                                                 class="img-responsive"/>
                                                        </a>
                                                    @endif
                                                @endif
                                                {{--<div class="offer_present_iiner">--}}
                                                {{--<img src="images/offer_persent.png" class="offer_pric" alt="img">--}}
                                                {{--<span>50%</span>--}}
                                                {{--</div>--}}

                                                {!! $discountHelper->replaceKeys(
                                                        $data,
                                                        $data->days,
                                                        (isset($contentBlock[44]) ? $contentBlock[44] : ''),
                                                        'ui green label'
                                                    )
                                                !!}

                                                <div class="button_ptice_loc">
                                                    <button type="button" class="price_rate">

                                                        @if($deal->price_from >= 1)
                                                            <del class="price">
                                                                &euro; {{ $deal->price_from }}
                                                            </del> &nbsp; &nbsp;                                                       
                                                        @endif

                                                        <span class="price2">
                                                            &euro; {{ $deal->price }}
                                                        </span>
                                                    </button>
                                                    <div class="loc_sec_main">
                                                        {{--<a href="">Bodega Maxima</a> | <a href=""><i class="fa fa-map-marker" aria-hidden="true"></i>--}}
                                                        {{--Eindhoven</a> | <a href=""><i class="fa fa-floppy-o" aria-hidden="true"></i>--}}
                                                        {{--Save</a>--}}

                                                        {{--<span class="stars"><img src="{{ asset('images/stars.png') }}" alt="stars" style="width: initial;">5.00</span>--}}
                                                        {{--  {{ $data->name }} | <span><i class="marker icon"></i> {{ ucfirst($data->city) }}&nbsp;</span>  --}}
                                                        <a style="font-size: 0.9em;" href="{{ url('search?q='.$data->city) }}">{{ $data->name }} | <span><i class="marker icon"></i> {{ ucfirst($data->city) }}</span></a>
                                                        <?php
                                                       /* if ($data->kitchens != 'null' && $data->kitchens != NULL && $data->kitchens != '[""]') {
                                                            $kitchens = json_decode($data->kitchens);
                                                            echo '<a href="' . url('search?q=' . $kitchens[0]) . '"><i class="food icon"></i> ' . ucfirst($kitchens[0]) . '</a>';
                                                        } */
                                                        ?>

                                                        @if(isset($onFavoritePage))
                                                            <a href="{{ url('account/favorite/companies/remove/'.$data->id.'/'.$data->slug) }}">
                                                                <span><i class="empty star red icon"></i> Verwijder van favorieten</span>
                                                            </a>
                                                        @else
                                                            @if($userAuth == TRUE)
                                                                <a class="save button"
                                                                   href="{{ url('account/favorite/companies/add/'.$data->id.'/'.$data->slug) }}">
                                                                    <span><i class="save icon"></i> Bewaren</span>
                                                                </a>
                                                            @else
                                                                <a class="login save button"
                                                                   href="{{ url('account/favorite/companies/add/'.$data->id.'/'.$data->slug) }}"
                                                                   data-type-redirect="1"
                                                                   data-type="login"
                                                                   data-redirect="{{ url('account/favorite/companies/add/'.$data->id.'/'.$data->slug) }}">
                                                                    <span><i class="save icon"></i> Bewaren</span>
                                                                </a>
                                                            @endif
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <style>
                                                .deal-details{
                                                    padding: 10px;
                                                }
                                                .deal-details a.title, .deal-details h1{
                                                    color: #273398;
                                                    font-size: 1.8em;
                                                    padding-top: 10px;
                                                    display: inline-block;
                                                    white-space: pre-wrap;
                                                    line-height: 1.2em;
                                                }

                                                 .deal-details a.title:hover{
                                                     color: #1E70BF;
                                                 }

                                                .deal-details p{
                                                    color: #999;
                                                }
                                            </style>
                                            <div class="deal-details">
                                                @if($count_persons >= $deal->total_amount)
                                                       <h1> {{ $deal->name }} </h1>
                                                    @else
                                                       <a href="{{ url('restaurant/'.$data->slug).'?deal='.$deal->id }}"
                                                           title="{{ $data->name }}" class="title">{{ $deal->name }}</a>
                                                         

                                                    @endif
                                                <span>
                            <a href="{{ url('search?q='.$data->city) }}">{{ $data->name }} | <span>
                            <i class="marker icon"></i> {{ ucfirst($data->city) }}&nbsp;</span>
                            </a>
                        </span>
                                                <p class="hidden-xs0" style="color: #666; "> <em>{!! substr(strip_tags($deal->description,'<em>'), 0, 107) !!}</em></p>

                                                {{--<div class="avilable_to_people">--}}
                                                {{--<i class="fa fa-caret-left" aria-hidden="true"></i>--}}
                                                {{--<button type="button" class="timing_buton gey_btn">08:00</button>--}}
                                                {{--<button type="button" class="timing_buton gey_btn">08:00</button>--}}
                                                {{--<button type="button" class="timing_buton">08:00</button>--}}
                                                {{--<button type="button" class="timing_buton">08:00</button>--}}
                                                {{--<button type="button" class="timing_buton">08:00</button>--}}
                                                {{--<i class="fa fa-caret-right" aria-hidden="true"></i>--}}
                                                {{--</div>--}}

                                                {{--  <div style="display: block; padding-top; 20px;">  --}}
                                              
                            
                                                <div class="row">
                                                    
                                                        <div class="col-md-6 col-xs-12" style="margin-bottom: 10px;">
                                                   
                                                    <a href="{{ url('restaurant/'.$data->slug).'?deal='.$deal->id }}" class="more_info_buton more_info_lg">Meer info</a>
                                                    
                                                        </div>
                                                        <div class="col-md-6 col-xs-12" style="margin-bottom: 10px;">
                                                    
                                                    <a href="{{ url('future-deal/'.$data->slug).'?deal='.$deal->id }}" class="more_info_buton more_info_lg">Koop deal </a>
                                                    
                                                        </div>
                                                    
                                                </div>
                                            </div>
                                        </div>
                                     </div> 
                                    <?php $items = $index + 1; ?>
                                    @if($items % 3 == 0)
                                        </div>
                                        <div class="row">
                                    @endif  

                        @endforeach
                    @endforeach

                    {{--<div class="col-md-4 wow fadeIn">--}}
                    {{--<div class="back_pric_ground">--}}
                    {{--<div class="price_img_sec">--}}
                    {{--<img src="images/img_2.jpg" class="img-responsive" alt="img">--}}
                    {{--<div class="offer_present_iiner">--}}
                    {{--<img src="images/offer_persent.png" class="offer_pric" alt="img">--}}
                    {{--<span>50%</span>--}}
                    {{--</div>--}}
                    {{--<div class="button_ptice_loc">--}}
                    {{--<button type="button" class="price_rate">€ 16.99</button>--}}
                    {{--<div class="loc_sec_main">--}}
                    {{--<a href="">Cafe Restaurant Het Swaard</a> | <a href=""><i class="fa fa-map-marker" aria-hidden="true"></i>--}}
                    {{--Valkenswaard</a> | <a href=""><i class="fa fa-floppy-o" aria-hidden="true"></i>--}}
                    {{--Save</a>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--<div class="heading_price_sec">--}}
                    {{--<h1>Dinner voucher € 20.- Free choice of--}}
                    {{--card at Cafe-Restaurant de Swaard--}}
                    {{--(Hotel de Valk).  </h1>--}}
                    {{--<p>This gift voucher is valid for 1 person and has a value of € 20.- with a free choice of card at...</p>--}}
                    {{--<div class="avilable_to_people">--}}
                    {{--<i class="fa fa-caret-left" aria-hidden="true"></i>--}}
                    {{--<button type="button" class="timing_buton gey_btn">08:00</button>--}}
                    {{--<button type="button" class="timing_buton gey_btn">08:00</button>--}}
                    {{--<button type="button" class="timing_buton">08:00</button>--}}
                    {{--<button type="button" class="timing_buton">08:00</button>--}}
                    {{--<button type="button" class="timing_buton">08:00</button>--}}
                    {{--<i class="fa fa-caret-right" aria-hidden="true"></i>--}}
                    {{--</div>--}}
                    {{--<button type="button" class="more_info_buton">MORE INFO</button>--}}
                    {{--<button type="button" class="more_info_buton">BUY DEAL</button>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--<div class="col-md-4 wow fadeInRight">--}}
                    {{--<div class="back_pric_ground">--}}
                    {{--<div class="price_img_sec">--}}
                    {{--<img src="images/img_3.jpg" class="img-responsive" alt="img">--}}
                    {{--<div class="offer_present_iiner">--}}
                    {{--<img src="images/offer_persent.png" class="offer_pric" alt="img">--}}
                    {{--<span>50%</span>--}}
                    {{--</div>--}}
                    {{--<div class="button_ptice_loc">--}}
                    {{--<button type="button" class="price_rate"><del style="font-size: 13px; margin-right: 3px">€ 29.75 </del>€ 16.99</button>--}}
                    {{--<div class="loc_sec_main">--}}
                    {{--<a href="">Javaans Eetcafe</a> | <a href=""><i class="fa fa-map-marker" aria-hidden="true"></i>--}}
                    {{--Eindhoven</a> | <a href=""><i class="fa fa-floppy-o" aria-hidden="true"></i>--}}
                    {{--Save</a>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--<div class="heading_price_sec">--}}
                    {{--<h1>Indonesian 3 course menu with--}}
                    {{--choice of meat or fish!  </h1>--}}
                    {{--<p>As you can already find out, the Javaans Eetcafé is a place where you can enjoy Indonesian... </p>--}}
                    {{--<div class="avilable_to_people">--}}
                    {{--<i class="fa fa-caret-left" aria-hidden="true"></i>--}}
                    {{--<button type="button" class="timing_buton gey_btn">08:00</button>--}}
                    {{--<button type="button" class="timing_buton gey_btn">08:00</button>--}}
                    {{--<button type="button" class="timing_buton">08:00</button>--}}
                    {{--<button type="button" class="timing_buton">08:00</button>--}}
                    {{--<button type="button" class="timing_buton">08:00</button>--}}
                    {{--<i class="fa fa-caret-right" aria-hidden="true"></i>--}}
                    {{--</div>--}}
                    {{--<button type="button" class="more_info_buton">MORE INFO</button>--}}
                    {{--<button type="button" class="more_info_buton">BUY DEAL</button>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                </div>
            </div>
        </div>
    </section>
@endsection

@section("after_styles")

    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    <link href="{{ asset('css/animate-min.css') }}" rel="stylesheet">

    <style>
        .header {
            top: 0;
        }
        .back_slideer_mnu {
            margin-top: 0px;
        }
    </style>
@endsection

@section("after_scripts")
    {{--  <script src="{{ asset('js/bootstrap.min.js') }} "></script>  --}}
    {{--  <script src="{{ asset('js/wow.js') }}"></script>
    <script src="{{ asset('js/script.js') }}"></script>  --}}

    <script>
        $(function() {
            hideandshow($("#searchbox"), $("#search-bar"), 300);
        });

        function hideandshow(e, n, a) {
            var c = !1;
            e.click(function(e) {
                c ? n.slideUp(a) : n.slideDown(a);
                c = !c;
                e.preventDefault();
            });
        } 
    </script>
@endsection