<a href="{{ url(isset($discountSettings['discount_url']) ? 'redirect_to?to='.App\Helpers\StrHelper::addScheme($discountSettings['discount_url']) : 'voordeelpas/buy') }}"
    {{ isset($discountSettings['discount_url']) ? 'target="_blank"' : '' }} class="discount-card">
    @if (isset($discountSettings['discount_image']))
        <img src="{{ asset($discountSettings['discount_image']) }}"
            {{ isset($discountSettings['discount_width']) ? 'width='.$discountSettings['discount_width'].'px' : '' }} 
            {{ isset($discountSettings['discount_height']) ? 'height='.$discountSettings['discount_height'].'px' : '' }} 
            alt="Voordeelpas">
    @else
        <img src="{{ asset('images/front-page-banner.png') }}" alt="Voordeelpas">
    @endif

    @if (
        isset($discountSettings['discount_old'])
        && isset($discountSettings['discount_new'])
        && $discountSettings['discount_old'] > 0
        OR $discountSettings['discount_new'] > 0
    )
    <div class="price">
        @if (isset($discountSettings['discount_old']))
        <sub>&euro;{{ $discountSettings['discount_old'] }}</sub>
        @else
        <sub>&euro;24,95</sub>
        @endif
        <br />

        @if (isset($discountSettings['discount_new']))
        <strong>&euro;{{ $discountSettings['discount_new'] }}</strong>
        @else
        <strong>&euro;24,95</strong>
        @endif
    </div>
    @endif
</a>

<div class="ui vertically divided grid">
    <div class="row computer tablet only">
        <div class="column">
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
            Er zijn geen nieuwsberichten gevonden.
            @endif
        </div>
    </div>
</div>