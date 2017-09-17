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

    <p>Hier kunt u uw giftcard verzilveren. Voer uw code in en klik op de Verzilveren knop.</p>
    

    <?php echo Form::open(array('id' => 'formList', 'url' => 'payment/paygiftcard', 'method' => 'post', 'class' => 'ui form')) ?>
    <input id="actionMan" type="hidden" name="action">

    @if (isset($error) && trim($error) != '') 
        <div class="ui red message">{{ $error }}</div>
    @endif

    <div class="fields">
        <div class="four wide field">
            <label>Giftcard Aantal</label>
            <?php echo Form::text('giftcard'); ?>
        </div>
    </div>

    <button class="ui button" type="submit">Verzilveren</button>
    <?php echo Form::close(); ?>
</div>
@stop