<?php
$st = \Carbon\Carbon::create(date('Y'), 1, 1, 0, 0, 0);
$dt = \Carbon\Carbon::create(date('Y') + 1, 12, 1, 0, 0, 0);
$dates = array();

while ($st->lte($dt)) {
    $dates[] = $st->copy()->format('Y-m');
    $st->addMonth();
}
?>

@extends('template.theme')
@inject('FileHelper', 'App\Helpers\FileHelper')


@section('slider')
    <!-- Hide Slider from main -->
@stop
@section('content')
    <div class="container">
        <div class="ui breadcrumb">
            <a href="{{ url('/') }}" class="section">Home</a>
            <i class="right chevron icon divider"></i>

            <a href="#" class="sidebar open" data-activates="slide-out">Menu</a>
            <i class="right chevron icon divider"></i>

            <div class="active section">Restaurants</div>
        </div>
    </div>
    @inject('discountHelper', 'App\Helpers\DiscountHelper')
    <div class="tabss">
        <div class="container">
            <div class="main_gallery">
                <div class="left_side">
                    <div class="bx-wrapper" style="max-width: 100%; margin: 0px auto;">
                        <div class="bx-viewport"
                             style="width: 100%; overflow: hidden; position: relative; height: 314px;">
                            <div class="bx-wrapper" style="max-width: 100%; margin: 0px auto;">
                                <div class="bx-viewport"
                                     style="width: 100%; overflow: hidden; position: relative; height: 314px;">
                                    <ul id="bxslider"
                                        style="width: 615%; position: relative; transition-duration: 0s; transform: translate3d(0px, 0px, 0px);">
                                        @if($media != '[]')
                                            @foreach($media as $mediaItem)
                                                @if(file_exists(public_path($mediaItem->disk. DIRECTORY_SEPARATOR . $mediaItem->id . DIRECTORY_SEPARATOR . $mediaItem->file_name)))
                                                    <li style="float: left; list-style: outside none none; position: relative; width: 674px;">
                                                        <a href="{{ url($mediaItem->getUrl()) }}"
                                                           data-lightbox="roadtrip">
                                                            <img class="ui image materialboxed rushy intialized"
                                                                 src="{{ url($mediaItem->getUrl()) }}">
                                                        </a>
                                                @else
                                                    <li style="float: left; list-style: outside none none; position: relative; width: 674px;">
                                                        <img src="{{ asset('images/r.png') }}" alt="s" class="materialboxed">
                                                        @endif
                                                        {!! $discountHelper->replaceKeys(
                                                        $company,
                                                        $company->days,
                                                        (isset($contentBlock[44]) ? $contentBlock[44] : ''),
                                                        'ribbon-wrapper thumb-discount-label'
                                                        )
                                                        !!}
                                                    </li>
                                                    @endforeach
                                                    @else
                                                        <li style="float: left; list-style: outside none none; position: relative; width: 674px;">
                                                            <img src="{{ asset('images/s1.jpg') }}" alt="s">
                                                        </li>
                                                    @endif

                                    </ul>
                                </div>
                                <div class="bx-controls bx-has-controls-direction">
                                    <div class="bx-controls-direction">
                                        <a class="bx-prev disabled" href=""></a>
                                        <a class="bx-next" href=""></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bx-controls bx-has-controls-direction">
                            <div class="bx-controls-direction">
                                <a class="bx-prev disabled" href=""></a>
                                <a class="bx-next" href=""></a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- The thumbnails -->
                <div class="r_side hidden-xs">
                    <div class="bx-wrapper" style="max-width: 205px; margin: 0px auto;">
                        <div class="bx-viewport"
                             style="width: 100%; overflow: hidden; position: relative; height: 323px;">
                            <ul id="bxslider-pager"
                                style="width: auto; position: relative; transition-duration: 0s; transform: translate3d(0px, 0px, 0px);">
                                @if($media != '[]')
                                    @foreach($media as $key=> $mediaItem)
                                        @if(file_exists(public_path($mediaItem->disk. DIRECTORY_SEPARATOR . $mediaItem->id . DIRECTORY_SEPARATOR . $mediaItem->file_name)))
                                            <li data-slideindex="{{ $key }}" data-slide-index="{{ $key }}"
                                                style="float: none; list-style: outside none none; position: relative; width: 187px; margin-bottom: 3px;">
                                                <a href="#">
                                                    <img src="{{ url($mediaItem->getUrl()) }}" alt="Alt">
                                                </a>
                                            </li>
                                        @else
                                            <li data-slideindex="{{ $key }}" style="width: 140px;height:78px">
                                                <!--Edited By Team AIT 28/0/2017-->
                                                <a

                                                        href="#"><img src="{{ asset('images/r.png')}} " alt="Alternate"></a>
                                                <!--Edited By Team AIT 28/0/2017-->
                                            </li>
                                        @endif
                                    @endforeach
                                @else
                                    <li data-slideindex="0" style="width: 140px;height:78px"><a href="#">
                                            <!--Edited By Team AIT 28/0/2017-->
                                            <img
                                                    src="{{ asset('images/r.png')}} " alt="Alt"></a></li>
                                    <!--Edited By Team AIT 28/0/2017-->
                                @endif
                            </ul>
                        </div>
                        <div class="bx-controls bx-has-controls-direction">
                            <div class="bx-controls-direction"><a class="bx-prev disabled" href=""><span></span></a><a
                                        class="bx-next disabled" href=""><span></span></a></div>
                        </div>
                    </div>
                </div>
                <div class="right_details calendar-ajax">
                    {!! Form::open(['url' => 'restaurant/'.$company->slug, 'id' => 'reservationForm', 'class' => 'ui form']) !!}
                    {{ Form::hidden('date_hidden') }}
                    {{ Form::hidden('date', date('Y-m-d')) }}
                    {{ Form::hidden('company_id', $company->id) }}
                    {{ Form::hidden('year', date('Y')) }}
                    {{ Form::hidden('month', date('m')) }}
                    {{ Form::hidden('monthDate', date('m-Y')) }}
                    {{ Form::hidden('reservation_url', URL::to('restaurant/reservation/'.$company->slug)) }}
                    <input type="hidden" name="deal"
                           value="{{ (@app('request')->input('deal'))?app('request')->input('deal'):'' }}">
                    <!-- <div id="datepicker" class="right_calendar hasDatepicker"><div class="ui-datepicker-inline ui-datepicker ui-widget ui-widget-content ui-helper-clearfix ui-corner-all" style="display: block;"><div class="ui-datepicker-header ui-widget-header ui-helper-clearfix ui-corner-all"><a class="ui-datepicker-prev ui-corner-all" data-handler="prev" data-event="click" title="Prev"><span class="ui-icon ui-icon-circle-triangle-w">Prev</span></a><a class="ui-datepicker-next ui-corner-all" data-handler="next" data-event="click" title="Next"><span class="ui-icon ui-icon-circle-triangle-e">Next</span></a><div class="ui-datepicker-title"><span class="ui-datepicker-month">April</span>&nbsp;<span class="ui-datepicker-year">2017</span></div></div><table class="ui-datepicker-calendar"><thead><tr><th class="ui-datepicker-week-end"><span title="Sunday">Su</span></th><th><span title="Monday">Mo</span></th><th><span title="Tuesday">Tu</span></th><th><span title="Wednesday">We</span></th><th><span title="Thursday">Th</span></th><th><span title="Friday">Fr</span></th><th class="ui-datepicker-week-end"><span title="Saturday">Sa</span></th></tr></thead><tbody><tr><td class=" ui-datepicker-week-end ui-datepicker-other-month ui-datepicker-unselectable ui-state-disabled">&nbsp;</td><td class=" ui-datepicker-other-month ui-datepicker-unselectable ui-state-disabled">&nbsp;</td><td class=" ui-datepicker-other-month ui-datepicker-unselectable ui-state-disabled">&nbsp;</td><td class=" ui-datepicker-other-month ui-datepicker-unselectable ui-state-disabled">&nbsp;</td><td class=" ui-datepicker-other-month ui-datepicker-unselectable ui-state-disabled">&nbsp;</td><td class=" ui-datepicker-other-month ui-datepicker-unselectable ui-state-disabled">&nbsp;</td><td class=" ui-datepicker-week-end " data-handler="selectDay" data-event="click" data-month="3" data-year="2017"><a class="ui-state-default" href="#">1</a></td></tr><tr><td class=" ui-datepicker-week-end " data-handler="selectDay" data-event="click" data-month="3" data-year="2017"><a class="ui-state-default" href="#">2</a></td><td class=" " data-handler="selectDay" data-event="click" data-month="3" data-year="2017"><a class="ui-state-default" href="#">3</a></td><td class=" " data-handler="selectDay" data-event="click" data-month="3" data-year="2017"><a class="ui-state-default" href="#">4</a></td><td class=" " data-handler="selectDay" data-event="click" data-month="3" data-year="2017"><a class="ui-state-default" href="#">5</a></td><td class=" " data-handler="selectDay" data-event="click" data-month="3" data-year="2017"><a class="ui-state-default" href="#">6</a></td><td class=" " data-handler="selectDay" data-event="click" data-month="3" data-year="2017"><a class="ui-state-default" href="#">7</a></td><td class=" ui-datepicker-week-end " data-handler="selectDay" data-event="click" data-month="3" data-year="2017"><a class="ui-state-default" href="#">8</a></td></tr><tr><td class=" ui-datepicker-week-end " data-handler="selectDay" data-event="click" data-month="3" data-year="2017"><a class="ui-state-default" href="#">9</a></td><td class=" " data-handler="selectDay" data-event="click" data-month="3" data-year="2017"><a class="ui-state-default" href="#">10</a></td><td class=" " data-handler="selectDay" data-event="click" data-month="3" data-year="2017"><a class="ui-state-default" href="#">11</a></td><td class=" " data-handler="selectDay" data-event="click" data-month="3" data-year="2017"><a class="ui-state-default" href="#">12</a></td><td class=" " data-handler="selectDay" data-event="click" data-month="3" data-year="2017"><a class="ui-state-default" href="#">13</a></td><td class=" " data-handler="selectDay" data-event="click" data-month="3" data-year="2017"><a class="ui-state-default" href="#">14</a></td><td class=" ui-datepicker-week-end " data-handler="selectDay" data-event="click" data-month="3" data-year="2017"><a class="ui-state-default" href="#">15</a></td></tr><tr><td class=" ui-datepicker-week-end " data-handler="selectDay" data-event="click" data-month="3" data-year="2017"><a class="ui-state-default" href="#">16</a></td><td class=" " data-handler="selectDay" data-event="click" data-month="3" data-year="2017"><a class="ui-state-default" href="#">17</a></td><td class=" " data-handler="selectDay" data-event="click" data-month="3" data-year="2017"><a class="ui-state-default" href="#">18</a></td><td class=" " data-handler="selectDay" data-event="click" data-month="3" data-year="2017"><a class="ui-state-default" href="#">19</a></td><td class="  ui-datepicker-current-day" data-handler="selectDay" data-event="click" data-month="3" data-year="2017"><a class="ui-state-default ui-state-active" href="#">20</a></td><td class=" " data-handler="selectDay" data-event="click" data-month="3" data-year="2017"><a class="ui-state-default" href="#">21</a></td><td class=" ui-datepicker-week-end " data-handler="selectDay" data-event="click" data-month="3" data-year="2017"><a class="ui-state-default" href="#">22</a></td></tr><tr><td class=" ui-datepicker-week-end " data-handler="selectDay" data-event="click" data-month="3" data-year="2017"><a class="ui-state-default" href="#">23</a></td><td class=" " data-handler="selectDay" data-event="click" data-month="3" data-year="2017"><a class="ui-state-default" href="#">24</a></td><td class=" " data-handler="selectDay" data-event="click" data-month="3" data-year="2017"><a class="ui-state-default" href="#">25</a></td><td class=" " data-handler="selectDay" data-event="click" data-month="3" data-year="2017"><a class="ui-state-default" href="#">26</a></td><td class=" " data-handler="selectDay" data-event="click" data-month="3" data-year="2017"><a class="ui-state-default" href="#">27</a></td><td class=" " data-handler="selectDay" data-event="click" data-month="3" data-year="2017"><a class="ui-state-default" href="#">28</a></td><td class=" ui-datepicker-week-end " data-handler="selectDay" data-event="click" data-month="3" data-year="2017"><a class="ui-state-default" href="#">29</a></td></tr><tr><td class=" ui-datepicker-week-end  ui-datepicker-today" data-handler="selectDay" data-event="click" data-month="3" data-year="2017"><a class="ui-state-default ui-state-highlight" href="#">30</a></td><td class=" ui-datepicker-other-month ui-datepicker-unselectable ui-state-disabled">&nbsp;</td><td class=" ui-datepicker-other-month ui-datepicker-unselectable ui-state-disabled">&nbsp;</td><td class=" ui-datepicker-other-month ui-datepicker-unselectable ui-state-disabled">&nbsp;</td><td class=" ui-datepicker-other-month ui-datepicker-unselectable ui-state-disabled">&nbsp;</td><td class=" ui-datepicker-other-month ui-datepicker-unselectable ui-state-disabled">&nbsp;</td><td class=" ui-datepicker-week-end ui-datepicker-other-month ui-datepicker-unselectable ui-state-disabled">&nbsp;</td></tr></tbody></table></div></div>-->
                    <div id="datepicker-calendar" class="right_calendar datepicker-calendar" data-lock=".calendar-ajax"
                         data-datepicker-ajax="true" data-timeselect="#time-calendar"
                         data-persons="#persons-calendar"></div>

                    <ul>
                        <li><img src="{{ asset('images/c1.png') }}" alt="m3">
                            <input type="hidden" name="ctime"
                                   value="{{ count(array_keys($reservationTimesArray)) >= 1 ? array_keys($reservationTimesArray)[0] : '' }}">
                            <select id="time-calendar" name="time" class="quantity2">
                            </select>
                        </li>
                        <li><img src="{{ asset('images/c2.png') }}" alt="m4">
                            <input type="hidden" name="cpersons"
                                   value="{{ ($userAuth && $userInfo->kids != 'null' && $userInfo->kids != NULL && $userInfo->kids != '[""]' ? $userInfo->kids : 1) }}">
                            <select id="persons-calendar" name="persons" class="quantity2">
                                <option value="0">Personen</option>
                                <?php $person_list = []; ?>
                                <?php
                                for ($i = 1; $i <= 10; $i++) {
                                if ($i == 2) {
                                ?>
                                <option value="{{ $i }}" data-value="{{ $i }}"
                                        selected="true">{{ $i }} {{ $i == 1 ? 'persoon' : 'personen' }}</option>
                                <?php } else { ?>
                                <option value="{{ $i }}"
                                        data-value="{{ $i }}">{{ $i }} {{ $i == 1 ? 'persoon' : 'personen' }}</option>
                                <?php } ?>
                                <?php $person_list[$i] = $i . " " . (($i == 1) ? 'persoon' : 'personen'); ?>
                                <?php } ?>
                            </select>
                        </li>
                    </ul>

                    @if($user)
                        <button id="submitField" class="more">Reserveer nu</button>
                    @else
                        <button id="submitField" class="more login guestClick">Reserveer nu</button>
                    @endif
                    {!! Form::close() !!}
                </div>
            </div>
            <div class="tabs-all">
                <ul class="tabs-link">
                    <li><a href="#t1" class="active">Over ons</a></li>
                    <li><a href="#t2" class="">Menu</a></li>
                    <li><a href="#t3" class="">Details</a></li>
                    <li><a href="#t4" class="">Contact</a></li>
                    <li><a href="#t5" class="">Nieuws</a></li>
                    <li><a href="#t6" class="">Groepen</a></li>
                    <li><a href="#t7" class="">Reviews</a></li>
                </ul>
                <div class="tabs-content">
                    <div id="t1" style="display: none;">
                        <div class="text3">
                            <strong>{!! $company->name !!}</strong>
                            <span class="city"><i class="material-icons">place</i>{{ $company->city }}</span>
                            <!-- <span class="stars"><img src="images/stars2.png" alt="stars2">4.50</span> -->
                            <p>    {!! $company->about_us !!}</p>
                        </div>
                    </div>
                    <div id="t2" style="display: block;">
                    @if(isset($deals) && count($deals))
                        @foreach($deals as $deal)                        
                            <!-- Menu -->                            
                            <div class="menu deals-list-page">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <h3 class="new">{{ $deal->name }}</h3> 
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-5">
                                        <div class="left_m">
                                            @if($deal->image!='')
                                                <a href="{{ url('future-deal/'.$company->slug).'?deal='.$deal->id }}"> <img
                                                            src="{{ asset('images/deals/'.$deal->image) }}"
                                                            alt="No Image Found" class="img-responsive">
                                                </a>

                                            @else
                                                <a href="{{ url('future-deal/'.$company->slug).'?deal='.$deal->id }}"> <img
                                                            src="{{ asset('images/deals/no-img.jpg') }}"
                                                            alt="No Image Found" class="img-responsive">
                                                </a>

                                            @endif
                                            {{--                            <ul class="price">
                                            <li><span>Verkocht<i>  &euro; {{ $deal->price_from }}  </i></span></li>
                                                        <li><span>Korting<i>50%</i></span></li>
                                                            </ul>--}}
                                        </div>
                                    </div>

                                    <div class="col-sm-7">
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <b class="right_m">
                                                    <div style="display: block">
                                                        @if($deal->price_from > 0.00)
                                                        <span class="old">  &euro; {{ $deal->price_from }}</span>
                                                        @endif
                                                        <strong class="new">&euro; {{ $deal->price }}</strong>
                                                    </div>
                                                        <br>
                                                        <b class="up">
                                                            <?php  echo strip_tags($deal->description); ?>                                           
                                                        </b>
                                                </b>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <br>                                    
                                                @if(!is_null($deal->getApprovedReviews))
                                                    <?php
                                                    $count = 1;
                                                    $total = 0;
                                                    ?>
                                                    @foreach($deal->getApprovedReviews as $review)
                                                        <?php
                                                        $avg = floor(($review->food + $review->service + $review->decor) / 3);
                                                        $total += $avg;
                                                        ?>
                                                        <div style="float: left;">
                                                            <div class="score">
                                                                Recencies:
                                                                <div class="ui star tiny rating no-rating disabled"
                                                                    data-rating="{{ $total/$count }}">
                                                                    <i class="icon-star active"></i>
                                                                </div>
                                                            </div>
                                                        </div>                                    
                                                        <?php $count++; ?>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>                                    
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <div id="koop">
                                                    @if(count($deals)==1)                                                   
                                                        <a class="more"
                                                        href="{{ url('future-deal/'.$company->slug).'?deal='.$deal->id }}">
                                                            Koop nu, reserveer later.
                                                        </a>
                                                    @else
                                                        <a class="more"
                                                        href="{{ url('future-deal/'.$company->slug).'?deal='.$deal->id }}">
                                                            Koop nu, reserveer later.
                                                        </a>                                                   
                                                    @endif  
                                                </div>                                                  
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div><!-- End of ROw -->
                        @endforeach
                    @endif
                    </div>
                    
                    <div id="t3" style="display: none;">
                        <div class="info">
                            @if ($company->preferences != NULL && $company->preferences != NULL && $company->preferences != '[""]')
                                <span>Voorkeuren</span>
                                <strong>
                                    @foreach (json_decode($company->preferences) as $id => $preferencesr)
                                        @if (isset($preferences[1][$preferencesr]))
                                            {{ ucfirst($preferences[1][$preferencesr]) }},
                                        @endif
                                    @endforeach
                                </strong>
                            @endif
                            @if ($company->kitchens != NULL && $company->kitchens != NULL && $company->kitchens != '[""]')
                                <span>Keuken</span>
                                <strong>
                                    @foreach (json_decode($company->kitchens) as $id => $kitchen)
                                        {{ ucfirst($preferences[2][$kitchen]) }},
                                    @endforeach
                                </strong>
                            @endif
                            @if ($company->price != NULL && $company->price != NULL && $company->price != '[""]')
                                <span>Soort</span>
                                <strong>
                                    @foreach (json_decode($company->price) as $id => $price)
                                        {{ ucfirst($preferences[4][$price]) }},
                                    @endforeach
                                </strong>
                            @endif
                            @if ($company->sustainability != NULL && $company->sustainability != NULL && $company->sustainability != '[""]')
                                <span>Duurzaamheid</span>
                                <strong>
                                    @foreach (json_decode($company->sustainability) as $id => $sustainability)
                                        {{ ucfirst($preferences[8][$sustainability]) }},
                                    @endforeach
                                </strong>
                            @endif
                            //<?php $discount = json_decode($company->discount);
//                            foreach ($discount as $key => $value) {
//                                if (is_null($value) || $value == 'NULL')
//                                    unset($discount[$key]);
//                            }?>
                            @if (!empty($discount))
                                <span>Korting</span>
                                <strong>
                                {{ $company->discount_comment }}
                                @foreach (json_decode($company->discount) as $id => $discount)
                                    {{ $discount }}
                                    <!-- 	@if (isset($preferences[5][$discount]))
                                        {{ ucfirst($preferences[5][$discount]) }}%
                                                    @endif -->
                                    @endforeach
                                </strong>

                                <span>Kortingsdagen</span>
                                <strong>
                                    <?php $dayNames = Config::get('preferences.days'); ?>
                                    @if ($company->days != 'null' && $company->days != NULL && $company->days != '[""]')
                                        <?php $i = 0; ?>
                                        @foreach (json_decode($company->days) as $id => $days)
                                            <?php $i++; ?>
                                            {{ $dayNames[$days] }}
                                            <?php echo($i < count(json_decode($company->days)) ? '-' : ''); ?>
                                        @endforeach
                                    @endif
                                </strong>
                            @endif

                        </div>
                        <a href="#" class="more">Een Tafel Reserveren</a>
                    </div>
                    <div id="t4" style="display: none;">
                        <div class="map">
                            <h3>{!! $company->zipcode !!}  {!! $company->city !!}</h3>
                            <span>{!! $company->address !!}<br/></span>
                            <span><a href="tel:{!! $company->phone !!}"
                                     target="_blank">{!! $company->phone !!} </a>
                                @if($company->website)
                                    <a href="http://{!! $company->website !!}"
                                       target="_blank">{!! " | ".$company->website !!} </a>
                                @endif
                                            </span>

                            @if(trim($company->contact_email) != '' || trim($company->email) != '')
                                <div class="send">
                                    {{ Form::open(array('url' => 'contact/'.$company->slug, 'method' => 'post', 'class' => 'form')) }}
                                    <label for="name">
                                        <span>Naam</span>
                                        {{ Form::text('name', (Sentinel::check() ? Sentinel::getUser()->name : ''), [ 'id' => 'name']) }}
                                    </label>


                                    <label for="email">
                                        <span>E-mail</span>
                                        {{ Form::text('email',  (Sentinel::check() ? Sentinel::getUser()->email : ''), ['id' =>'email']) }}
                                    </label>

                                    <label for="subject">
                                        <span>Onderwerp</span>
                                        {{ Form::text('subject',null,['id' => 'subject' ]) }}
                                    </label>

                                    <label for="content">
                                        <span>Bericht</span>
                                        {{ Form::textarea('content',null, ['id' => 'content']) }}
                                    </label>

                                    <label class="two fields">
                                        <!-- <div class="six wide field"> -->
                                    {!! captcha_image_html('ContactCaptcha') !!}
                                    <!-- </div> -->

                                        <label>
                                            <span>Typ de beveiligingscode over:</span>
                                            {{ Form::text('CaptchaCode', '', array('id' => 'CaptchaCode', 'placeholder' => 'beveiligingscode' )) }}
                                        </label>
                                    </label>

                                    <button type="submit" class="ui small blue button">VERZENDEN</button>
                                    {{ Form::close() }}
                                </div>
                            @endif
                            <div class="maps">
                                <div id="map"
                                     data-kitchen="{{ is_array(json_decode($company->kitchens)) ? str_slug(json_decode($company->kitchens)[0]) : '' }}"
                                     data-url="{{ url('restaurant/'.$company->slug) }}"
                                     data-name="{{ $company->name }}"
                                     data-address="{{ $company->address }}"
                                     data-city="{{ $company->city }}"
                                     data-zipcode="{{ $company->zipcode }}"></div>
                            </div>
                        </div>
                    </div>
                    <div id="t5" style="display: none;">
                    @if($news->count() >= 1)
                        @foreach($news as $article)
                            <?php $newsMedia = $article->getMedia(); ?>
                            <!-- News -->
                                <div class="news">
                                    <div class="ob">
                                        @if($newsMedia != '[]')
                                            <img class="ui small image"
                                                 src="{{ url('public/'.$newsMedia->last()->getUrl()) }}"/>
                                        @elseif($media != '[]')
                                            <img class="ui small image"
                                                 src="{{ url('public/'.$media->last()->getUrl()) }}"/>
                                        @endif
                                    </div>
                                    <div class="in">
                                        <h2><a href="{{ url('news/'. $article->slug) }}"
                                               class="header">{{ $article->title }}</a></h2>

                                        <span>Geplaatst op {{ date('d-m-Y H:i:s', strtotime($article->created_at)) }}</span>
                                        <p>{{ implode(' ', array_slice(explode(' ', strip_tags($article->content)), 0, 100)) }}
                                            ... <a href="{{ url('news/'. $article->slug) }}">Read more</a>
                                        </p>
                                    </div>
                                </div>
                                <!-- Pages -->
                            {!! $news->appends($paginationQueryString)->render() !!}

                            <!--<div class="pages">
                                            <a href="#" class="prev2">&lt;</a>
                                            <ul>
                                    <li><a href="#">1</a></li>
                                                    <li><a href="#" class="active">2</a></li>
                                                    <li><a href="#">...</a></li>
                                                    <li><a href="#">8</a></li>
                                            </ul>
                                            <a href="#" class="next2">&gt;</a>
                                    </div> -->
                            @endforeach
                        @else
                            <span>Er zijn geen nieuwsberichten gevonden.</span>
                        @endif
                    </div>
                    <div id="t6" style="display: none;">
                        <div class="send">

                            {!! Form::open(array('id' => 'reservationForm', 'url' => 'restaurant/reservation/'.$company->slug, 'method' => 'PUT', 'class' => 'form')) !!}
                            {{ Form::hidden('group_reservation', 1) }}
                            {{ Form::hidden('setTimeBack', 0) }}
                            {!! Form::hidden('company_id', $company->id) !!}
                            {{ Form::hidden('date') }}

                            {!! isset($contentBlock[59]) ? $contentBlock[59] : '' !!}


                            <label for="date">
                                <span>Datum</span>
                                {{ Form::text('date_input', '', array('data-datepicker-ajax' => 'true','data-timeselect' => '#time-dropdown', 'data-group' => '1', 'data-persons' => '#persons-dropdown','id' => 'datepicker-dropdown')) }}

                            </label>

                            <label for="time-dropdown">
                                <span>Tijm</span>
                                <div class="details">
                                {{ Form::select("time",[],Request::get('time'),[ 'class' => 'quantity2', 'id' => 'time-dropdown']) }}
                                <!-- <select id="time-dropdown" name="time" class="quantity2"></select>-->
                                </div>
                            </label>

                            <label for="persons">
                                <span>Personen</span>
                                <div class="details">
                                    {{ Form::select("persons",$person_list,1,[ 'class' => 'quantity2', 'id' => 'persons-dropdown']) }}
                                </div>
                                <!-- Form::text('persons') -->
                            </label>

                            <label for="name">
                                <span>Naam</span>
                                {!! Form::text('name', (Sentinel::check() ? Sentinel::getUser()->name : '')) !!}
                            </label>

                            <label for="email">
                                <span>E-mail</span>
                                {!! Form::text('email',  (Sentinel::check() ? Sentinel::getUser()->email : '')) !!}
                            </label>

                            <label for="phone">
                                <span>Telefoon</span>
                                {!! Form::text('phone',  (Sentinel::check() ? Sentinel::getUser()->phone : '')) !!}
                            </label>


                            <label for="comment">
                                <span>Opmerking</span>
                                {!! Form::textarea('comment') !!}
                            </label>

                            <button type="submit" class="ui small blue button">Reserveren</button>
                            {{ Form::close() }}

                        </div>
                    </div>
                    <div id="t7" style="display: none;">
                        @if(count($reviews) >= 1)
                            @foreach($reviews as $review)
                                <div class="reviews">
                                    <div class="avatar">
                                        <div class="wr"><img src="images/a1.png" alt="a"></div>
                                        <span>{{ $review->name }}</span>
                                        <span>{{ $reviewModel->countReviews($review->user_id) }} {{ $reviewModel->countReviews($review->user_id) == 1 ? 'recensie' : 'recensies' }}</span>
                                    </div>
                                    <div class="rev">
                                        <p>{{ $review->content }}</p>
                                        <div class="rr">
                                            <span>{{ date('d-m-Y', strtotime($review->created_at)) }}</span>
                                            <i>{{ $reviewModel->getAverage(array($review->food,  $review->service, $review->decor)) }}</i>
                                            <div class="score">
                                                Eten
                                                <div class="ui star tiny rating no-rating"
                                                     data-rating="{{ $review->food }}"></div>
                                                <br/>
                                                Service
                                                <div class="ui star tiny rating no-rating"
                                                     data-rating="{{ $review->service }}"></div>
                                                <br/>
                                                decor
                                                <div class="ui star tiny rating no-rating"
                                                     data-rating="{{ $review->decor }}"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <label>
                                Er zijn nog geen recensies gegeven. Hier gegeten? Laat je recensie achter!
                            </label>
                        @endif
                        <div class="send_review">
                            @if($user)
                                {{ Form::open(array('url' => 'restaurant/reviews/'.$company->slug, 'method' => 'post','id' => 'reviews', 'class' => 'form')) }}
                                {{ Form::hidden('food', 1) }}
                                {{ Form::hidden('service', 1) }}
                                {{ Form::hidden('decor', 1) }}
                                {{ Form::hidden('dealId', !empty($_GET['deal']) ? $_GET['deal'] : '') }}
                                <input type="hidden" id="csrf-token" value="{{ csrf_token() }}">

                                <div>
                                    <h5>SCHRIJF EEN BEOORDELING</h5>
                                </div>

                                <div>
                                    <div>
                                        Eten
                                        <i id="food" class="ui star rating" data-rating="1"></i>
                                    </div>
                                    <div>
                                        Service
                                        <i id="service" class="ui star rating" data-rating="1"></i>
                                    </div>
                                    <div>
                                        Decor
                                        <i id="decor" class="ui star rating" data-rating="1"></i>
                                    </div>
                                </div>

                                <label for="idcontent">
                                    <span>Recensie</span>
                                    <span id="msg"></span>
                                    {{ Form::textarea('content',NULL,['id' => 'idcontent']) }}
                                </label>

                                <button type="button" onclick='checkWords();' class="ui small blue button"
                                        id="reviewsSubmit">VERZENDEN
                                </button>

                                {{ Form::close() }}
                            @else
                                <p><strong>U moet eerst ingelogd zijn om te kunnen reageren.</strong></p>
                                <a href="{{ url('restaurant/'.$company->slug) }}"
                                   data-redirect="{{ url('restaurant/'.$company->slug) }}"
                                   data-type="login"
                                   class="ui login button">
                                    Inloggen
                                </a><br/>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <style>
            .more {
                background: #333399 none repeat scroll 0 0;
                border-radius: 5px;
                box-shadow: 3px 4px 5px 0 rgba(0, 0, 0, 0.15);
                color: #fff;
                display: inline-block;
                font: 18px "LatoRegular";
                margin-top: 5px;
                padding: 11px 31px;
                position: relative;
                text-align: center;
                text-transform: uppercase;
                top: -10px;
            }

            #msg {
                color: red;
            }
        </style>
        <div class="clear">
        </div>
    </div>
    <script>
        var activateAjax = 'restaurant';
        //		$('body').on('keydown','#idcontent',function(){
        function checkWords() {
            var data = $('#reviews').serializeArray();
            console.log(data);
            var token = $('#csrf-token').val();
            $.ajax({
                type: 'post',
                data: data,
                url: "{{ url('restaurant/unwanted/getUnwantedWords') }}",
                dataType: "json"
            }).done(function (result) {
                if (result.length > 0) {
                    $('#msg').html('Helaas het woord ...... is niet toegestaan');
                    $(this).css('color', 'red');
                } else {
                    console.log("abcd");
                    $('#reviews').submit();
                }
            }).fail(function () {
            });
        }
    </script>
@stop
@section('scripts')
    <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-5751e9a264890504"></script>
@stop
