@inject('affiliateHelper', 'App\Helpers\AffiliateHelper')



@if (Request::has('category') OR Request::has('subcategory') OR Request::segment(2) == 'category')

<script type="text/javascript">

    var activateAjax = 'searchCashback';

</script>

@endif



{{--*/ $pageTitle = 'Tegoed sparen' /*--}}





<div class="form">

    <div class="container container2">

        <form action="<?php echo url('tegoed-sparen/search'); ?>" method="GET" id="affiliateSearch-2" class="ui search">

            <!--<div id="affiliateSearch-2" class="ui search">-->

                <label for="search">

                    <input type="text" name="q" class="prompt" placeholder="Zoek webshop" />

                </label>

                <div class="results"></div>

            <!--</div>-->

            <span class="of">OF</span>

            <select name='category' class='category'>

                <option value='0' disabled="disabled" selected>Category</option>

                @foreach($categories as $category)

                @if ($category['countCategoryPrograms'] > 0)

                <option value="{{ $category['slug'] }}">{{ $category['name'] }}</option>

                @endif

                @endforeach

            </select>

            <select name='subcategory' class='subcategory'>

                <option value='0' disabled="disabled" selected>Subcategory</option>

            </select>

            <input type="submit" value='ZOEK' />

        </form>

    </div>

</div>

<script src="{{ asset('js/jquery-1.11.3.min.js') }} "></script>



@if (isset($mediaItems))

@foreach($mediaItems as $id => $ad)

<div class="leaderboard">

    <a href="{{ $page ? url($page->slug) : '' }}">

        <img src="{{ url(''.$ad->getUrl()) }}" class="ui image">

    </a>

</div>

@endforeach

@endif



<div class="goods">

    <div class="container">

        <div class="left-sidebar">

            @foreach($categories as $i => $category)

            @if ($category['countCategoryPrograms'] > 0)

            <section class="ac-container">

                <div>

                    @if (count($category['subcategories']) > 1 )

                    <input id="ac-{{$i}}" name="accordion-1" type="checkbox" />

                    <label for="ac-{{$i}}">{{ $category['name'] }}</label>

                    @else



                    <a href="{{ url('tegoed-sparen/category/'.$category['id'].'/'.$category['slug']) }}" class="firstitem">

                        {{ $category['name'] }}

                    </a>

                    @endif



                    <article class="ac-small">

                        @foreach($category['subcategories'] as $subcategory)

                        @if ($subcategory['name'] != NULL && $subcategory['countSubCategoryPrograms'] >= 1)

                        <a href="{{ url('tegoed-sparen/category/'.$subcategory['id'].'/'.$subcategory['slug']) }}" class="{{ ($subcategory['id'] == Request::segment(3) ? 'active' : '') }} subitem">

                            {{ $subcategory['name'] }}

                        </a>

                        @endif

                        @endforeach

                    </article>

                </div>

            </section>

            @endif

            @endforeach

        </div>



        <div class="right-content">

            <div class="sort">
            &nbsp;&nbsp;&nbsp;&nbsp;
                <!-- 
                <span class='ls'>Sort by:</span>

                <select>

                    <option value='0' disabled="disabled" selected>Max. spaartegoed</option>

                    <option>1</option>

                    <option>2</option>

                    <option>3</option>

                    <option>4</option>

                </select>
                -->

                <div class="r">

                    <span class='rs'>View</span>

                    <a href="#s1"><img src="{{asset('images/one.png')}}" alt="one" /></a>

                    <a href="#s2"><img src="{{asset('images/two.png')}}" alt="two" /></a>

                </div>

            </div>

            <div class="tabs-content">

                <ul class='lines' id='s2'>

                    @foreach($affiliates as $data)

                    <li>

                        <span class="wrap"><img src="{{ asset('images/affiliates/'.$data['affiliate_network'].'/'.$data['program_id'].'.'.$data['image_extension']) }}" alt="{{ $data['name'] }}" /></span>

                        <div class="t"><i>{{ $data['name'] }}<strong>{{ $data['commissions'] }}</strong></i>

                        </div>

                        <a href="{{ url('tegoed-sparen/company/'.$data['slug']) }}">{{ $data['name'] }}</a>

                    </li>

                    @endforeach

                </ul>

                <ul class='box' id='s1'>

                    @foreach($affiliates as $data)

                    <li><a href="{{ url('tegoed-sparen/company/'.$data['slug']) }}"><span class="wrap"><img src="{{ asset('images/affiliates/'.$data['affiliate_network'].'/'.$data['program_id'].'.'.$data['image_extension']) }}" alt="{{ $data['name'] }}" /><b>{{ $data['name'] }}</b></span>

                            <p>{{ $data['name'] }}<strong>{{ $data['commissions'] }}</strong></p></a>

                    </li>

                    @endforeach

                </ul>

            </div>

            <div class="pages">

                {!! $affiliates->appends($paginationQueryString)->render() !!}

            </div>

        </div>

    </div>

</div>



<script>

    $(document).ready(function() {



        $(".category").on('change', function(e) {

            slug=$("select.category option").filter(":selected").val();

            $.ajax({

                url: "tegoed-sparen/get-sub-cat/"+slug,

                type: 'GET',

                dataType: 'json', // added data type

                success: function(res) {

                    console.log(res['subcat'][0].name);

                    $('.subcategory option').remove();

                    $('.subcategory').append($('<option>').text('Subcategory').attr('value', 0));

                    $.each(res['subcat'], function(index, value) {

                        $('.subcategory').append($('<option>').text(value.slug).attr('value', value.name));

                    });

                }

            });

        });



    });

</script>