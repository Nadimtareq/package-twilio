@extends('template.theme')

@inject('preference', 'App\Models\Preference')
@inject('content', 'App\Models\Content')
@inject('affiliateHelper', 'App\Helpers\AffiliateHelper')

{{--*/ $pageTitle = $data->name /*--}}

@section('slider')
<br>
@stop

<div class="shop">
    <div class="container">
        <div class="up">
            <div class="more">
                <div>
                    <h2>{{$data->name}}</h2>
                    <div class="wrap"><img src="{{ url('images/affiliates/'. $data->affiliate_network .'/'.$data->program_id.'.'.$data->image_extension) }}" alt="more" /></div>
                    <div class="t2">
                        <strong>Wat u kunt sparen</strong>
                        @if(trim($data->compensations) != '')
                        @foreach($affiliateHelper->commissionUnique(json_decode($data->compensations)) as $key => $commission)
                        @if($commission['value'] > 0 && !isset($commission['noshow']))
                        @if($userAuth)
                        <span>{{ $affiliateHelper->amountAsUnit($commission['value'], $commission['unit']) }} {{ $commission['name'] }}</span>
                        @else
                        <span>{{ $commission['unit'] != '%' ? '&euro;' : '' }}{{ round($commission['value'] / 100 * 70, 2)  }}{{ $commission['unit'] == '%' ? '%' : '' }} {{ $commission['name'] }}</span>
                        @endif
                        @endif
                        @endforeach
                        @endif
                        <i>Standaard vergoeding</i>

                        @if ($userAuth)
                        <a id="visiteStore"
                           href="{{ $affiliateHelper->getAffiliateUrl($data, $userInfo->id) }}"
                           class="ui blue no-radius {{ $userInfo->cashback_popup == 0 ? 'cashback' : 'cashback' }} logged-in fluid button"
                           {{ $userInfo->cashback_popup == 1 ? 'target="_blank"' : '' }}>
                            Bezoek webwinkel
                        </a>
                        <br />

                        @if ($favoriteCompany >= 1)
                        <a  id="visiteStore"
                            href="{{ url('tegoed-sparen/delete-favorite/'.$data->id.'/'.$data->slug) }}"
                            class="ui fluid button">
                            Verwijder favoriet
                        </a>
                        @else
                        <a  id="visiteStore"
                            href="{{ url('tegoed-sparen/favorite/'.$data->id.'/'.$data->slug) }}"
                            class="ui yellow fluid button">
                            Bewaren
                        </a>
                        @endif
                        @else
                        <a  id="visiteStore"
                            href="{{ url('tegoed-sparen/company/'.$data->slug) }}?open_cashbackinfo=1"
                            class="ui blue no-radius login cashback fluid button"
                            data-type="login"
                            data-redirect="{{ url('tegoed-sparen/company/'.$data->slug) }}?open_cashbackinfo=1">
                            Bezoek webwinkel
                        </a>
                        @endif

                    </div>
                    <div class="t">
                        <h2>Voorwaarden</h2>
                        <p>
                            {!! $data->terms !!}
                            {!! isset($contentBlock[20]) ? $contentBlock[20] : '' !!}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('pages/cashback/companies')
</div>
