@extends('template.theme')

@section('scripts')
@if (Request::has('direct'))
<script type="text/javascript">
    $('#formList').submit();
</script>
@endif
@stop

@section('content')
 <div class="content">
    <div class="ui breadcrumb">
        <a href="{{ url('/') }}" class="section">Home</a>

        <i class="right chevron icon divider"></i>
        <a href="#" class="sidebar open" data-activates="slide-out">Menu</a>

        <i class="right chevron icon divider"></i>
        <a href="{{ url('voordeelpas') }}" class="section">Voordeelpas</a>

        <i class="right chevron icon divider"></i>
        <div class="active section">Saldo opwaarderen</div>
    </div>

    <div class="ui divider"></div>

    @if (isset($discountSettings['discount_image2']))
    <div class="ui grid">
        <div class="ten wide column">
            Hier kunt u uw saldo opwaarderen, bij het klikken op 'Saldo opwaarderen' wordt u doorgeleid naar de betaalpagina.
        </div>

        <div class="five wide column">
            @if (isset($discountSettings['discount_image2']))
            <a href="{{ url(isset($discountSettings['discount_url']) ? 'redirect_to?p=2&to='.App\Helpers\StrHelper::addScheme($discountSettings['discount_url3']) : '#') }}" 
                {{ isset($discountSettings['discount_url3']) ? 'target="_blank"' : '' }} class="discount-card">
                 <img src="{{ asset(''.$discountSettings['discount_image2']) }}"

                {{ isset($discountSettings['discount_width2']) ? 'width='.$discountSettings['discount_width2'].'px' : '' }} 
                {{ isset($discountSettings['discount_height2']) ? 'height='.$discountSettings['discount_height2'].'px' : '' }} 
                 class="ui image" alt="Opwaarderen">
            </a>
            @endif
        </div>
    </div><br /><br />
    @else
        <p>Hier kunt u uw saldo opwaarderen, bij het klikken op 'Saldo opwaarderen' wordt u doorgeleid naar de betaalpagina.</p>
    @endif

    <?php echo Form::open(array('id' => 'formList', 'url' => 'payment/pay'.(Request::has('buy') ? '?buy=voordeelpas' : ''), 'method' => 'post', 'class' => 'ui form')) ?>
    <input id="actionMan" type="hidden" name="action">

    @if (isset($error) && trim($error) != '') 
        <div class="ui red message">{{ $error }}</div>
    @endif

    <div class="fields">
        <div class="four wide field">
            <label>Bedrag</label>
            <?php echo Form::text('amount', $restAmount); ?>
        </div>
    </div>
    <div class="fields">
        <div class="two field">
    <button class="ui button" type="submit">Saldo opwaarderen</button>
        </div>
        <div class="two field">
    <a href="{{ url('payment/giftcode')}}"><button class="ui button" type="button">Verzilver kadokaart</button></a>
        </div>
    </div>
    <?php echo Form::close(); ?>
</div>
@stop