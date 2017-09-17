@extends('template.theme')

{{--*/ $pageTitle = 'Reserveren bij '.$company->name /*--}}

@section('content')
<script type="text/javascript">
    var activateAjax = 'reservation';
    var user_current_balance = 0;
    var user_authenticate = false;
</script>
@if($iframe==1)
<style>
    .space-header{
        padding-top:0px !important;
    }
    .mdg{
        margin-top: -50px;
    }
</style>
@endif
<?php if (($userAuth == TRUE) && isset($userInfo->saldo)): ?>
    <script type="text/javascript"> user_current_balance = "<?php echo $userInfo->saldo; ?>";
        user_authenticate = true;</script>
<?php endif; ?>
<div class="container mdg">
    @if (!isset($iframe))
    <div class="ui breadcrumb">
        <a href="{{ url('/') }}" class="section">Home</a>
        <i class="right chevron icon divider"></i>

        <a href="{{ url('restaurant/'.$company->slug) }}" class="section">{{ $company->name }}</a>

        <i class="right arrow icon divider"></i>

        <span class="active section"><h1>Reserveren bij {{ $company->name }}</h1></span>
    </div>
    @endif
    <div class="ui grid">
        <div class="row">
            <div class="col-md-3 col-md-12">

                @if(!empty($mediaItems) && isset($mediaItems[0]))
                @if($iframe!=1 && isset($iframe))
                <img id="image" src="{{ url('images/deals/'.$deal->image) }}" class="img-responsive1 img_responsive image_rest" alt="" />
                @endif
                {{--
                <img id="image" src="{{ url($mediaItems[0]->getUrl('175Thumb')) }}" class="img-responsive" alt="" />
                --}}
                @endif
            </div>
            <?php if ($deal): ?>
                <div class="col-md-9 col-md-12">
                    <h4 style="color:#333399;">{{$deal->name}}
                        <div class="mdg_price">
                            <span>
                                &euro; {{ $deal->price }}
                            </span>
                        </div>
                    </h4>
                    <div style="color:#999999;"><?php echo html_entity_decode($deal->description); ?></div>

                </div>

            <?php endif; ?>
        </div>
    </div>
    @if(isset($iframe))
    <div style="width: 100%;">
        @endif
        <?php echo Form::open(array('id' => 'reservationForm', 'url' => URL::full(),
            'method' => 'post', 'class' => 'ui form')) ?>
        <?php echo Form::hidden('date_hidden',
                date('Y-m-d', strtotime(Request::get('date')))); ?>
        <?php echo Form::hidden('company_id', $company->id); ?>
        <?php echo Form::hidden('iframe', $iframe); ?>
        <?php echo Form::hidden('encode_url', 1); ?>
        <?php echo Form::hidden('setTimeBack', 0); ?>
        <?php
        /*  echo "<pre>";
          print_r($deal);exit; */
        if ($deal):
            ?>
            <?php echo Form::hidden('reservations_options',
                    $deal->id); ?>
            <input type="hidden" name="deal_price" class="deal_price" id="deal_price" value="<?php echo $deal->price ?>">
<?php endif; ?>
<?php echo Form::hidden('reservation_url',
        URL::to('restaurant/reservation/' . $company->slug)); ?>
        @if(isset($iframe))<br>
        @if($userAuth == FALSE)
        <button data-type="iframe" class="ui blue fluid login button" data-redirect="{{ URL::full() }}">
            <i class="sign in icon"></i> Login met uw UWvoordeelpas account
        </button><br><br>
        @endif
        <div class="ui grid">
            <div class="row">
                <div class="six wide column">
                    <div class="field">
                        <label>Datdsum</label>
<?php echo Form::text('date', '',
        array('class' => 'reservationDatepicker')); ?>
                    </div>
                </div>

                <div class="five wide column">
                    <div class="field">
                        <label>Tijd</label>
                        <div id="timeField" class="ui normal selection compact dropdown time timeRefresh">
                            <input id="timeInput" name="time" type="hidden" value="<?php echo date('H:i',
        strtotime(Request::get('time'))); ?>">

                            <i class="time icon"></i>

                            <div class="default text">Tijd</div>
                            <i class="dropdown icon"></i>

                            <div class="menu">
                                <div class="item" data-value="<?php echo date('H:i',
        strtotime(Request::get('time'))); ?>"><?php echo date('H:i',
        strtotime(Request::get('time'))); ?></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="five wide column">
                    <div class="field">
                        <label>Personen</label>

                        <div id="personsField" class="ui normal compact selection dropdown persons searchReservation">
                            <input type="hidden" name="persons" value="<?php echo Request::get('persons'); ?>">

                            @if(!isset($iframe))
                            <i class="male icon"></i>
                            @endif

                            <div class="default text">Personen</div>
                            <i class="dropdown icon"></i>
                            <div class="menu">
<?php
for ($i = 1; $i <= 10; $i++) {
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

            <div class="<?php echo ((isset($iframe) ? 'two' : 'three')); ?> column row">

                        <?php if ($deal): ?>
    <?php
    $deal_reservation_charge = (float) ($deal->price * Request::get('persons'));
    echo Form::hidden('saldo', $deal_reservation_charge,
            array('min' => 0, 'max' => 500));
    ?>
<?php else: ?>
                    <div class="column">
                        <div class="field">
                            <label>Allergie&euml;n</label>
                            <?php echo Form::select('allergies[]',
                                    array_combine(json_decode($company->allergies),
                                            array_map('ucfirst',
                                                    json_decode($company->allergies))),
                                    ($user && $user->allergies != NULL ? json_decode($user->allergies)
                                                : ''),
                                    array('class' => 'ui normal dropdown', 'data-placeholder' => 'Allergieen',
                                'multiple' => 'multiple')); ?>
                        </div>
                    </div>

<?php endif; ?>
                <div class="column">
                    <div class="field">
                        <label>Voorkeuren</label>
                        <?php
                        echo Form::select(
                                'preferences[]',
                                array_combine(
                                        json_decode($company->preferences),
                                        array_map('ucfirst',
                                                json_decode($company->preferences))
                                ),
                                ($user && $user->preferences != NULL ? json_decode($user->preferences)
                                            : ''),
                                array(
                            'class' => 'ui normal dropdown',
                            'data-placeholder' => 'Voorkeuren',
                            'multiple' => 'multiple'
                                )
                        );
                        ?>
                    </div>
                </div>

                @if($iframe==1)
                <div class="column" style="display:none;">
                    <div class="field">
                        <label>Spaartegoed {{ $userAuth ? '&euro;'.$user->saldo : '' }}</label>
                        <?php echo Form::hidden('saldo',
                                $userAuth ? $user->saldo : '',
                                array('min' => 0, 'max' => 500)); ?>
                    </div>
                </div>
                @else
                <div class="column">
                    <div class="field">
                        <label>Spaartegoed {{ $userAuth ? '&euro;'.$user->saldo : '' }}</label>
<?php echo Form::text('saldo',
        $userAuth ? $user->saldo : '', array('min' => 0, 'max' => 500)); ?>
                    </div>
                </div>
                @endif
            </div>

            <div class="three column row">
                <div class="column">
                    <div class="field">
                        <label>Naam</label>
<?php echo Form::text('name',
        $userAuth ? $user->name : ''); ?>
                    </div>
                </div>
                <div class="column">
                    <div class="field">
                        <label>Telefoonnummer</label>
                <?php echo Form::text('phone',
                        $userAuth ? $user->phone : ''); ?>
                    </div>
                </div>
                <div class="column">
                    <div class="field">
                        <label>E-mailadres</label>
<?php echo Form::text('email',
        $userAuth ? $user->email : ''); ?>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="column">
                    <div class="field">
                        <label>Opmerking</label>
<?php echo Form::textarea('comment',
        (isset($company->lastComment) ? $company->lastComment : ''),
        array('rows' => 2)); ?>
                    </div>
                </div>
            </div>
        </div><br>
        @else
        <div class="ui divider"></div>

        {{-- {!! $discountMessage !!}--}}

        <div class="three fields">
            <div class="field">
                <label>Datum</label>
<?php echo Form::text('date', '',
        array('class' => 'reservationDatepicker')); ?>
            </div>

            <div class="field">
                <label>Tijd</label>
                <div id="timeField" class="ui normal selection dropdown time timeRefresh">
                    <input id="timeInput" name="time" type="hidden" value="<?php echo date('H:i',
        strtotime(Request::get('time'))); ?>">

                    <i class="time icon"></i>
                    <div class="default text">Tijd</div>
                    <i class="dropdown icon"></i>

                    <div class="menu">
                        <div class="item" data-value="<?php echo date('H:i',
                    strtotime(Request::get('time'))); ?>"><?php echo date('H:i',
                    strtotime(Request::get('time'))); ?></div>
                    </div>
                </div>
            </div>

            <div class="field">
                <label>Personen</label>
                <div id="personsField" class="ui normal compact selection dropdown persons searchReservation">
                    <input type="hidden" name="persons" value="<?php echo Request::get('persons'); ?>">
                    <i class="male icon"></i>

                    <div class="default text">Personen</div>
                    <i class="dropdown icon"></i>
                    <div class="menu">
                        @for($i = 1; $i <= 10; $i++)
                        <div class="item"  data-value="<?php echo $i; ?>"><?php echo $i; ?> <?php echo $i ==
                1 ? 'persoon' : 'personen'; ?></div>
                        @endfor
                    </div>
                </div>
            </div>
        </div>

        <div class="<?php echo ($deal) ? 'two fields' : 'three fields'; ?>" >
                <?php if ($deal): ?>
                    <?php
                    $deal_reservation_charge = (float) ($deal->price * Request::get('persons'));
                    echo Form::hidden('saldo', $deal_reservation_charge,
                            array('min' => 0, 'max' => 500));
                    ?>
<?php else: ?>

                <div class="field">
                    <label>Spaartegoed {{ $userAuth ? '&euro;'.$user->saldo : '' }}</label>
    <?php echo Form::text('saldo',
            $userAuth ? $user->saldo : '', array('min' => 0, 'max' => 500)); ?>
                </div>

<?php endif; ?>
            <div class="field">
                <label>Voorkeuren</label>
                <?php echo Form::select('preferences[]',
                        ($userAuth ? array_combine(json_decode($company->preferences),
                                        array_map('ucfirst',
                                                json_decode($company->preferences)))
                                    : array()),
                        ($user && $user->preferences != NULL ? json_decode($user->preferences)
                                    : ''),
                        array('class' => 'multipleSelect', 'data-placeholder' => 'Voorkeuren',
                    'multiple' => 'multiple')); ?>
            </div>

            <div class="field">
                <label>Allergie&euml;n</label>
<?php echo Form::select('allergies[]',
        ($userAuth ? array_combine(json_decode($company->allergies),
                        array_map('ucfirst', json_decode($company->allergies))) : array()),
        ($user && $user->allergies != NULL ? json_decode($user->allergies) : ''),
        array('class' => 'multipleSelect', 'data-placeholder' => 'Allergieen', 'multiple' => 'multiple')); ?>
            </div>
        </div>

        <div class="two fields">
            <div class="field">
                <label>Naam</label>
<?php echo Form::text('name', $userAuth ? $user->name : ''); ?>
            </div>

            <div class="field">
                <label>Telefoonnummer</label>
<?php echo Form::text('phone', $userAuth ? $user->phone : ''); ?>
            </div>

            <div class="field">
                <label>E-mailadres</label>
<?php echo Form::text('email', $userAuth ? $user->email : ''); ?>
            </div>
        </div>



        <div class="field">
            <label>Opmerking</label>
<?php echo Form::textarea('comment',
        (isset($company->lastComment) ? $company->lastComment : '')); ?>
        </div>
        @endif

        @if ($userAuth == TRUE)
        @if($company->newsletter == 0)
        <div class="field">
            <div class="ui checkbox">
<?php echo Form::checkbox('newsletter', 1); ?>
                <label>Wilt u de nieuwsbrief van {{ $company->name }} ontvangen?</label>
            </div>
        </div>
        @endif
        @else
        <div class="field">
            <div class="ui checkbox">
<?php echo Form::checkbox('newsletter', 1); ?>
                <label>Wilt u de nieuwsbrief van {{ $company->name }} ontvangen?</label>
            </div>
        </div>
        @endif

        @if ($userAuth == TRUE)
        @if($userInfo->terms_active == 0)
        <div class="field">
            <div class="ui checkbox">
<?php echo Form::checkbox('av', 1); ?>
                <label>Ik ga akkoord met de <a href="{{ url('algemene-voorwaarden') }}" target="_blank">voorwaarden</a></label>
            </div>
        </div>
        @else
<?php echo Form::hidden('av', 1); ?>
        @endif
        @else
        <div class="field">
            <div class="ui checkbox">
<?php echo Form::checkbox('av', 1); ?>
                <label>Ik ga akkoord met de <a href="{{ url('algemene-voorwaarden') }}" target="_blank">voorwaarden</a></label>
            </div>
        </div>
        @endif
        {{-- CODE FOR SUBMIT BUTTON AND FORM END --}}
        <div id="normal_case">
            <button class="ui tiny button" type="submit"><i class="plus icon"></i> Bevestig</button>
<?php echo Form::close(); ?>
        </div>
        {{-- END CODE FOR SUBMIT BUTTON AND FORM END --}}
        @if(isset($iframe))
    </div>
    @endif
</div>
<div class="clear"></div>
@stop


@push('inner_scripts')
<script type="text/javascript">
    $(function () {
        $('#personsField').find('.item').on('click', function () {
            person = $(this).data('value');
            deal_price = $('#deal_price').val();

            amout = parseFloat(deal_price) * parseInt(person);
            if (!isNaN(amout))
                $('[name="saldo"]').val(amout);
            $('[name="persons"]').val(person);
        });
        $('#reservationForm').submit(function () {
            person = $('[name="persons"]').val();
            deal_price = $('#deal_price').val();
            amout = parseFloat(deal_price) * parseInt(person);
            /*if ((user_authenticate) && (amout > user_current_balance)) {
             $('#charge_amount').val();
             var charge_balance = amout - user_current_balance;
             $('#charge_amount').val(charge_balance);
             $('#formList').submit();
             return false;
             }*/
            return true;
        });
    });

</script>
@endpush