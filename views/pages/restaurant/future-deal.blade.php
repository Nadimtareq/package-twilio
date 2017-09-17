@extends('template.theme')

{{--*/ $pageTitle = 'Reserveren bij '.$company->name /*--}}

@section('content')
    <div class="container mdg">
        <div class="ui breadcrumb">
            <a href="{{ url('/') }}" class="section">Home</a>
            <i class="right chevron icon divider"></i>

            <a href="{{ url('restaurant/'.$company->slug) }}" class="section">{{ $company->name }}</a>

            <i class="right arrow icon divider"></i>

            <span class="active section"><h1>Reserveren bij {{ $company->name }}</h1></span>
        </div>
        <div class="clear">&nbsp;</div>
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
                    <div style="color:#999999;">
                        <span class="more">
                            <?php echo html_entity_decode($deal->description); ?>
                        </span>
                    </div>
                </div>

                <?php endif; ?>
            </div>
        </div>
        <div class="clear">&nbsp;</div>
        <?php echo Form::open(array('id' => 'futureDealForm', 'url' => url('future-deal/'.$company->slug.'?deal='.$deal->id), 'method' => 'post', 'class' => 'ui form')) ?>
        <?php echo Form::hidden('saldo', $deal->price); ?>
        <div class="ui grid">
            <div class="three column row">
                <div class="column"  style="position: relative; left: -14px;">
                    <div class="field">
                        <label>Personen</label>

                        <div id="personsField" class="ui normal compact selection dropdown persons searchReservation">
                            <?php echo Form::hidden('persons', ((old('persons')) ? old('persons') : 2)); ?>
                            @if(!isset($iframe))
                                <i class="male icon"></i>
                            @endif

                            <div class="default text">Personen</div>
                            <i class="dropdown icon"></i>
                            <div class="menu">
                                <?php
                                for ($i = 1; $i <= 15; $i++) {
                                ?>
                                <div class="item" data-value="<?php echo $i; ?>"><?php echo $i; ?></div>
                                <?php
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                @if ($userAuth == FALSE)
                    <div class="column" style="position: relative; left: -14px;">
                        <div class="field">
                            <label>Uw regio</label>
                            <input type="hidden" name="city" value="{{ ucfirst($regio) }}">
                            <input type="text" name="city" value="{{ ucfirst($regio) }}" disabled>
                      </div>
                    </div>
                @else
                    <div class="column"> &nbsp;</div>
                @endif
                <div class="column"> &nbsp;</div>
            </div>
            @if ($userAuth == FALSE)
                <div class="three column row">
                    <div class="column">
                        <div class="field">
                            <label>Naam</label>
                            <?php echo Form::text('name', ''); ?>
                        </div>
                    </div>
                    <div class="column">
                        <div class="field">
                            <label>Telefoonnummer</label>
                            <?php echo Form::text('phone', ''); ?>
                        </div>
                    </div>
                    <div class="column">
                        <div class="field">
                            <label>E-mailadres</label>
                            <?php echo Form::text('email', ''); ?>
                        </div>
                    </div>
                </div>
            @endif
            <div class="one column row">
                @if (($userAuth == FALSE) OR (!empty($userInfo) && ($userInfo->newsletter == 0)))
                    <div class="column">
                        <div class="field">
                            <div class="ui checkbox">
                                <?php echo Form::checkbox('newsletter', 1); ?>
                                <label>Wilt u de nieuwsbrief van {{ $company->name }} ontvangen?</label>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="column">
                    <div class="field">
                        <div class="ui checkbox">
                            <?php echo Form::checkbox('av', 1); ?>
                            <label>Ik ga akkoord met de <a href="{{ url('algemene-voorwaarden') }}" target="_blank">voorwaarden</a></label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="one column row">
                <div class="column">
                    <div class="field">
                        <button class="ui tiny button" type="submit"><i class="plus icon"></i> Bevestig</button>
                    </div>
                </div>
            </div>
        </div>
        <?php echo Form::close(); ?>
    </div>
    <div class="clear"></div>
@stop
@push('inner_scripts')
<script type="text/javascript">
    var deal_price = "<?php echo $deal->price?>";
    $(function () {
        $('select.regionSelect').select();
        $('#personsField').find('.item').on('click', function () {
            person = $(this).data('value');
            amout = parseFloat(deal_price) * parseInt(person);
            $('#deal_amount').html(amout.toFixed(2));
            $('[name="saldo"]').val(amout);
            $('[name="persons"]').val(person);

        });
        curr_person = $('#futureDealForm').find('input[name="persons"]').val();
        if(curr_person){
            var default_amout = parseFloat(deal_price) * parseInt(curr_person);
            $('#deal_amount').html(default_amout.toFixed(2));
        }
    });
    $(document).ready(function() {
        // Configure/customize these variables.
        var showChar = 100;  // How many characters are shown by default
        var ellipsestext = "...";
        var moretext = '<button class="ui tiny button">Lees meer</button>';
        var lesstext = '<button class="ui tiny button">Toon minder tekst</button>';


        $('.more').each(function() {
            var content = $(this).html();

            if(content.length > showChar) {

                var c = content.substr(0, showChar);
                var h = content.substr(showChar, content.length - showChar);

                var html = c + '<span class="moreellipses">' + ellipsestext+ '&nbsp;</span><span class="morecontent"><span>' + h + '</span>&nbsp;&nbsp;<a href="" class="morelink">' + moretext + '</a></span>';

                $(this).html(html);
            }

        });

        $(".morelink").click(function(){
            if($(this).hasClass("less")) {
                $(this).removeClass("less");
                $(this).html(moretext);
            } else {
                $(this).addClass("less");
                $(this).html(lesstext);
            }
            $(this).parent().prev().toggle();
            $(this).prev().toggle();
            return false;
        });
    });
</script>
@endpush