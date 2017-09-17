@extends('template.theme')
@section('content')
<script type="text/javascript">
    var activateAjax = 'reservation';
</script>
<div class="container mdg">
    <div class="ui breadcrumb">
		<a href="{{ url('/') }}" class="section">Home</a>
		<i class="right chevron icon divider"></i>
        <a href="#" class="sidebar open" data-activates="slide-out">Menu</a>
	    <i class="right chevron icon divider"></i>
		<div class="active section"> Mijn vouchers</div>
    </div>
    <div class="ui grid">
        <div class="row">
            <div class="col-md-3 col-md-12">
                @if(!empty($mediaItems) && isset($mediaItems[0]))
                    <img id="image" src="{{ url('images/deals/'.$deal->image) }}" class="img-responsive1 img_responsive image_rest" alt="" />
                @endif
            </div>
            <?php if ($deal): ?>
            <div class="col-md-9 col-md-12">
                <h4 style="color: #333399;">{{$deal->name}}
                    <div class="mdg_price">
                    <span>
                        &euro; <span id="deal_amount">{{ $deal->price }}</span>
                    </span>
                    </div>
                </h4>
                <div style="color:#999999;"><?php echo html_entity_decode($deal->description); ?></div>
            </div>

            <?php endif; ?>
        </div>
    </div>
    <div class="clear" style="height: 80px;">&nbsp;</div>
    <?php echo Form::open(array('id' => 'reservationForm', 'url' => URL::full(), 'method' => 'post', 'class' => 'ui form')) ?>
    <?php echo Form::hidden('date_hidden', date('Y-m-d')); ?>
    <?php echo Form::hidden('company_id', $company->id); ?>    
    @if (count($errors) > 0)
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    <div class="ui grid custom-reserve-future-deal">
        <div class="row">
            <div class="six wide column mobile"> 
                <div class="field">
                    <label>Datum</label>
                    <?php echo Form::text('date', '', array('class' => 'reservationDatepicker')); ?>
                </div>	
            </div>
            <div class="five wide column"> 
                <div class="field">
                    <label>Tijd</label>
                    <div id="timeField" class="ui normal selection compact dropdown time timeRefresh">
                        <input id="timeInput" name="time" type="hidden" value="<?php echo date('H:i', strtotime(Request::get('time'))); ?>">

                        <i class="time icon"></i>

                        <div class="default text">Tijd</div>
                        <i class="dropdown icon"></i>

                        <div class="menu">
                            <div class="item" data-value="<?php echo date('H:i', strtotime(Request::get('time'))); ?>"><?php echo date('H:i', strtotime(Request::get('time'))); ?></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="five wide column"> 
                <div class="field">
                    <label>Personen</label>

                    

                    <div id="personsField" class="ui normal compact selection dropdown persons searchReservation">
                        <input type="hidden" name="persons" value="<?php echo Request::get('persons'); ?>">

                        <i class="male icon"></i>
                        <div class="default text">Personen</div>
                        
                         <i class="dropdown icon"></i> 
                         <div class="menu"> 
                            {{--  @for($j=1; $j <= 30; $j++)
                                <div class="item" data-value="{{$j}}"> {{$j}}</div>
                                @endfor  --}}
                            <?php
                             for ($i = 1; $i <= $futureDeal->persons_remain; $i++) {
                                ?>
                                <div class="item" data-value="<?php echo $i; ?>"><?php echo $i; ?></div>
                                <?php
                            }  
                            ?>
                         </div> 
                     </div> 
                </div>	
            </div>
        </div>
        <?php /*
          <div class="two column row">
          <div class="column">
          <div class="field">
          <label>Voorkeuren</label>
          <?php
          echo Form::select(
          'preferences[]', array_combine(
          json_decode($company->preferences), array_map('ucfirst', json_decode($company->preferences))
          ), ($user && $user->preferences != NULL ? json_decode($user->preferences) : ''), array(
          'class' => 'ui normal dropdown',
          'data-placeholder' => 'Voorkeuren',
          'multiple' => 'multiple'
          )
          );
          ?>

          </div>
          </div>

          <div class="column">
          <div class="field">
          <label>Allergie&euml;n</label>
          <?php echo Form::select('allergies[]', array_combine(json_decode($company->allergies), array_map('ucfirst', json_decode($company->allergies))), ($user && $user->allergies != NULL ? json_decode($user->allergies) : ''), array('class' => 'ui normal dropdown', 'data-placeholder' => 'Allergieen', 'multiple' => 'multiple')); ?>
          </div>
          </div>

          </div> */ ?>
        <div class="three column row"> 
            <div class="six wide column">
                <div class="field">
                    <label>Naam</label>
                    <?php echo Form::text('name', $userAuth ? $user->name : ''); ?>
                </div>	
            </div>	
            <div class="five wide column">
                <div class="field">
                    <label>Telefoonnummer</label>
                    <?php echo Form::text('phone', $userAuth ? $user->phone : ''); ?>
                </div>	
            </div>	
            <div class="five wide column">
                <div class="field">
                    <label>E-mailadres</label>
                    <?php echo Form::text('email', $userAuth ? $user->email : ''); ?>
                </div>
            </div>
        </div>
        <div class="row"> 
            <div class="column custom-comment">
                <div class="field">
                    <label>Opmerking</label>
                    <?php echo Form::textarea('comment', (isset($company->lastComment) ? $company->lastComment : ''), array('rows' => 2)); ?>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="six wide column"> 
                <div class="field">
                    <button class="ui tiny button" type="submit"><i class="plus icon"></i> Bevestig</button>
                </div>	
            </div>
        </div>
    </div>
    <?php echo Form::close(); ?>
</div>
@stop