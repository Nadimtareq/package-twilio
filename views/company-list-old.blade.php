<?php use App\Http\Controllers\HomeController; $i = 0; ?>

@inject('discountHelper', 'App\Helpers\DiscountHelper')
@inject('companyReservation', 'App\Models\companyReservation')
@inject('FileHelper', 'App\Helpers\FileHelper')

 <ul>

@foreach ($companies as $data)

 
    @foreach ($data->ReservationOptions2()->get() as $deal)
	<li>
        <?php

        $media = $data->getMedia('default');

        $getRec        = HomeController::getPersons($deal->id);
        $count_persons = $getRec[0]->total_persons;

        ?>
        <div class="company"
             data-kitchen="{{ is_array(json_decode($data->kitchens)) ? str_slug(json_decode($data->kitchens)[0]) : '' }}"
             data-url="{{ url('restaurant/'.$data->slug) }}"
             data-name="{{ $data->name }}"
             data-address="{{ $data->address }}"
             data-city="{{ $data->city }}"
             data-zipcode="{{ $data->zipcode }}">
			 
		
            <div class="ob" >                                        
                    @if (isset($media[0]) && isset($media[0]->file_name) && file_exists(public_path($media[0]->disk. DIRECTORY_SEPARATOR . $media[0]->id . DIRECTORY_SEPARATOR . $media[0]->file_name)) )
@if($count_persons >= $deal->total_amount)
 <img width="420" src="{{ url('media/'.$media[0]->id.'/'.$media[0]->file_name) }}" alt="{{ $data->name }}"  class="thumbnails" />
@else
 <a href="{{ url('restaurant/'.$data->slug).'?deal='.$deal->id }}" title="{{ $data->name }}" >
                            <img width="420" src="{{ url('media/'.$media[0]->id.'/'.$media[0]->file_name) }}" alt="{{ $data->name }}"  class="thumbnails" />
                        </a>
@endif
                       
                    @else


					@if($count_persons >= $deal->total_amount)
						<img src="{{ url('images/placeholdimagerest.png') }}" alt="{{ $data->name }}" class="thumbnails"  />

					@else
                        <a href="{{ url('restaurant/'.$data->slug).'?deal='.$deal->id }}" title="{{ $data->name }}" data-url="">
                            <img src="{{ url('images/placeholdimagerest.png') }}" alt="{{ $data->name }}" class="thumbnails"  />
                        </a>
						@endif
                    @endif

                    {!! $discountHelper->replaceKeys(
                            $data,
                            $data->days,
                            (isset($contentBlock[44]) ? $contentBlock[44] : ''),
                            'ui green label'
                        )
                    !!}
					
					 <!--  After change template, it was on mobile section
						@if(isset($onFavoritePage))
                            <a href="{{ url('account/favorite/companies/remove/'.$data->id.'/'.$data->slug) }}">
                                <span><i class="save red icon"></i> Verwijder van favorieten</span>
                            </a>
                        @else
                            @if($userAuth == TRUE)
                                <a href="{{ url('account/favorite/companies/add/'.$data->id.'/'.$data->slug) }}">
                                    <span><i class="save icon"></i> Bewaren</span>
                                </a>
                            @else
                                <a class="login button"
                                   href="{{ url('account/favorite/companies/add/'.$data->id.'/'.$data->slug) }}"
                                   data-type-redirect="1"
                                   data-type="login"
                                   data-redirect="{{ url('account/favorite/companies/add/'.$data->id.'/'.$data->slug) }}">
                                    <span><i class="save icon"></i> Bewaren</span>
                                </a>
                            @endif
                        @endif -->
             </div>
			 
            <div class="text3" style="min-height: 280px;">
                <strong>
                    @if($count_persons >= $deal->total_amount)
                        {{ $deal->name }}
                    @else
                        <a href="{{ url('restaurant/'.$data->slug).'?deal='.$deal->id }}" title="{{ $data->name }}">{{ $deal->name }}</a>

                    @endif
				</strong>
                {{--<span> Van: <strike>{{ $data->price_from }}</strike> | Voor: {{ $data->price }}</span>--}}

			    <span class="city">
					<a href="{{ url('search?q='.$data->city) }}">{{ $data->name }} | <span>
					   <i class="marker icon"></i> {{ $data->city }}&nbsp;</span>
					</a>
				</span>

				<span class="stars"><img src="{{ asset('images/stars.png') }}" alt="stars">5.00</span>
				
				<?php
				if( $data->kitchens != 'null' && $data->kitchens != NULL && $data->kitchens != '[""]') 
				{
					$kitchens = json_decode($data->kitchens);
					echo '<a href="'.url('search?q='.$kitchens[0]).'"><i class="food icon"></i> '.ucfirst($kitchens[0]).'</a>';
				}
				?>

				@if(isset($onFavoritePage))
					<a href="{{ url('account/favorite/companies/remove/'.$data->id.'/'.$data->slug) }}">
						<span><i class="empty star red icon"></i> Verwijder van favorieten</span>
					</a>
				@else
					@if($userAuth == TRUE)
						<a class="save button" href="{{ url('account/favorite/companies/add/'.$data->id.'/'.$data->slug) }}">
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
				
              <p>{!!  $deal->description !!}</p>
			  <div class="wr">
                {!!
                    $companyReservation->getTimeCarouselHTML(
                        isset($reservationDate) ? $reservationDate : NULL,
                        $data,
                        Request::input('persons', 2),
                        $reservationTimesArray,
                        $tomorrowArray,
                        Request::input('date'),
                        Request::input('deal', $deal->id)
                    )
                !!}
                 <?php
                    $getRec        = HomeController::getPersons($deal->id);
                    $count_persons = $getRec[0]->total_persons;
                ?>
			  </div>
			  <span class="price">
			     &euro; {{ $deal->price_from }}
			  </span>
			  
			  <span class="price2">
			     &euro; {{ $deal->price }}
			  </span>
				@if($count_persons >= $deal->total_amount)
					<a class="more"  href="javascript:void(0)">SOLD OUT</a>
				@else
					<div class="d-inline-block">
						<a class="more"  href="{{ url('restaurant/'.$data->slug).'?deal='.$deal->id }}">MEER INFO</a>&nbsp;
						<a class="more"  href="{{ url('future-deal/'.$data->slug).'?deal='.$deal->id }}">KOOP DEAL</a>
					</div>
				@endif
					
           </div>
		
	  </li>
    @endforeach    
@endforeach
  </ul>
