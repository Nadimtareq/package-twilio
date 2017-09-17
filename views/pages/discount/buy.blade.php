@extends('template.theme')

@section('slider')
<br>
@stop

{{--*/ $pageTitle = 'Voordeelpas' /*--}}

@section('content')
<div class="content">
    <div class="discount-card">
        <a href="{{ url(isset($discountSettings['discount_url3']) ? 'redirect_to?p=3&to='.App\Helpers\StrHelper::addScheme($discountSettings['discount_url3']) : 'voordeelpas/buy') }}" 
            {{ isset($discountSettings['discount_url3']) ? 'target="_blank"' : '' }} 
            class="discount-card buy-page mobile ui">

            @if ($company != NULL && $media != NULL && isset($media[0]))
                <img src="{{ url('public'.$media[0]->getUrl('mobileThumb')) }}" alt="Voordeelpas">
            @else
                @if (isset($discountSettings['discount_image3']))
                    <img src="{{ asset('public/'.$discountSettings['discount_image3']) }}"
                    {{ isset($discountSettings['discount_width3']) ? 'width='.$discountSettings['discount_width3'].'px' : '' }} 
                    {{ isset($discountSettings['discount_height3']) ? 'height='.$discountSettings['discount_height3'].'px' : '' }} 
                     alt="Voordeelpas">
                @else
                    <img src="{{ asset('public/images/front-page-banner.png') }}" alt="Voordeelpas">
                @endif
            @endif

            @if (
                isset($discountSettings['discount_old3'])
                && isset($discountSettings['discount_new3'])
                && $discountSettings['discount_old3'] > 0
                OR $discountSettings['discount_new3'] > 0
            )
            <div class="price">
                @if (isset($discountSettings['discount_old3']))
                <sub>&euro;{{ $discountSettings['discount_old3'] }}</sub>
                @else
                <sub>&euro;24,95</sub>
                @endif
                <br />

                @if (isset($discountSettings['discount_new3']))
                <strong>&euro;{{ $discountSettings['discount_new3'] }}</strong>
                @else
                <strong>&euro;24,95</strong>
                @endif
            </div>
            @endif
        </a>
    </div>

    <div class="ui breadcrumb">
        <a href="{{ url('/') }}" class="section">Home</a>

        <i class="right chevron icon divider"></i>
        <a href="{{ url('voordeelpas/buy') }}" class="section">Voordeelpas</a>

        <i class="right chevron icon divider"></i>
        <div class="active section"><h1>Koop uw voordeelpas</h1></div>
    </div>
    <div class="ui divider"></div>
<div>
		<img id="barcode5"/>
		<script>
			var repeat5 = function(){
				JsBarcode("#barcode5", Math.floor(1000000+Math.random()*9000000)+"",{displayValue:true,fontSize:20});
			};
			setInterval(repeat5,500);
			repeat5();
		</script>
	</div>
	
	
    <div class="discount-card">
        <a href="{{ url(isset($discountSettings['discount_url3']) ? 'redirect_to?p=3&to='.App\Helpers\StrHelper::addScheme($discountSettings['discount_url3']) : 'voordeelpas/buy') }}" 
            {{ isset($discountSettings['discount_url3']) ? 'target="_blank"' : '' }} 
            class="discount-card buy-page large">

            @if ($company != NULL && $media != NULL && isset($media[0]))
                <img src="{{ url('public'.$media[0]->getUrl('mobileThumb')) }}" alt="Voordeelpas">
            @else
                @if (isset($discountSettings['discount_image3']))
                    <img src="{{ asset('public/'.$discountSettings['discount_image3']) }}"
                    {{ isset($discountSettings['discount_width3']) ? 'width='.$discountSettings['discount_width3'].'px' : '' }} 
                    {{ isset($discountSettings['discount_height3']) ? 'height='.$discountSettings['discount_height3'].'px' : '' }} 
                     alt="Voordeelpas">
                @else
                    <img src="{{ asset('public/images/front-page-banner.png') }}" alt="Voordeelpas">
                @endif
            @endif

            @if (
                isset($discountSettings['discount_old3'])
                && isset($discountSettings['discount_new3'])
                && $discountSettings['discount_old3'] > 0
                OR $discountSettings['discount_new3'] > 0
            )
            <div class="price">
                @if (isset($discountSettings['discount_old3']))
                <sub>&euro;{{ $discountSettings['discount_old3'] }}</sub>
                @else
                <sub>&euro;24,95</sub>
                @endif
                <br />

                @if (isset($discountSettings['discount_new3']))
                <strong>&euro;{{ $discountSettings['discount_new3'] }}</strong>
                @else
                <strong>&euro;24,95</strong>
                @endif
            </div>
            @endif
        </a>
    </div>
    <div class="padded">
        {!! isset($contentBlock[10]) ? $contentBlock[10] : '' !!}<br />
        
        <?php echo Form::open(array('url' => 'voordeelpas/buy', 'method' => 'post', 'class' => 'ui form')) ?>
            @if($userAuth)
                @if($userInfo->terms_active == 0)
                 <div class="field">
                    <div class="ui checkbox">
                        <?php echo Form::checkbox('terms', 1); ?>
                        <label>Ik ga akkoord met de <a href="{{ url('algemene-voorwaarden') }}" target="_blank">voorwaarden</a></label>
                    </div>  
                </div>  
                @else
                <?php echo Form::hidden('terms', 1); ?>
                @endif
            @else
            <div class="field">
                    <div class="ui checkbox">
                        <?php echo Form::checkbox('terms', 1); ?>
                        <label>Ik ga akkoord met de <a href="{{ url('algemene-voorwaarden') }}" target="_blank">voorwaarden</a></label>
                    </div>  
                </div>  
            @endif

            @if($userAuth == FALSE)
                <a href="{{ url('voordeelpas/buy/direct') }}" data-redirect="{{ url('voordeelpas/buy/direct') }}"  class="ui button login blue" data-type="login">Koop nu</a>
                <a href="{{ url('account/barcodes') }}" data-redirect="{{ url('account/barcodes') }}" class="ui button login" data-type="login">Ik heb al een barcode</a>
            @else
                <button type="submit" class="ui button blue">Koop nu</button>
                <a href="{{ url('account/barcodes') }}" class="ui button">Ik heb al een barcode<a>
            @endif
        <?php echo Form::close(); ?> 
    </div>
</div>
@stop