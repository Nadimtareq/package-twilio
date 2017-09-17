@inject('discountHelper', 'App\Helpers\DiscountHelper')

<div class="slideShowHeight">
	<div class="sliderLeft">
		<ul class="slideshow">
			@if($media != '[]')
				@foreach ($media as $mediaItem)
				<li>
				<a href="{{ url($mediaItem->getUrl()) }}" data-lightbox="roadtrip">
                                    <img class="ui image" src="{{ url($mediaItem->getUrl()) }}"></a>

	                {!! $discountHelper->replaceKeys(
	                        $company, 
	                        $company->days, 
	                        (isset($contentBlock[44]) ? $contentBlock[44] : ''),
	                        'ribbon-wrapper thumb-discount-label'
	                    ) 
	                !!}
				</li>
				@endforeach
			@endif
		</ul>
	</div>

	<div class="sliderRight">
		<div id="bx-pager">
			@if($media != '[]')
				@foreach ($media as $key => $mediaItem)
			  	<a href="#" data-slide-index="{{ $key }}">
			  		<img class="ui image" src="{{ url($mediaItem->getUrl('175Thumb')) }}" />
			  	</a>
				@endforeach
			@endif
		</div>
	</div>
</div>