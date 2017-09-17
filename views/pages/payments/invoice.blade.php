@extends('template.theme')

@section('content')
    <div class="content">
        <div class="ui breadcrumb">
            <a href="{{ url('/') }}" class="section">Home</a>

            <i class="right chevron icon divider"></i>
            <a href="#" class="sidebar open" data-activates="slide-out">Menu</a>

            <i class="right chevron icon divider"></i>
            <a href="{{ url('voordeelpas') }}" class="section">Voordeelpas</a>

            <i class="right chevron icon divider"></i>
            <div class="active section">Factuur betalen</div>
        </div>
        <div class="ui divider"></div>

        <p>
            Uw factuur bedrag bedraagt &euro;{{ $invoice->type == 'products' ? number_format($totalPriceProducts, 2, ',', ' ') : number_format(($invoice->totalPersons * 1 * 1.21), 2, ',', ' ') }} , Klik op factuur betalen om uw factuur d.m.v. iDeal te voldoen.
        </p>

        <?php echo Form::open(array('id' => 'formList', 'url' => 'payment/pay-invoice/pay', 'method' => 'post', 'class' => 'ui form')) ?>
        <input id="actionMan" type="hidden" name="action">

        <div class="fields">
            <div class="four wide field">
                @if(isset($error))<span class="message" style="color: red;">{{ $error }}</span>@endif
                <?php echo Form::hidden('invoicenumber', $invoice->invoice_number, array('class' => 'ui normal fluid dropdown','required' => 'required')); ?>
            </div>
        </div>

        <button class="ui button" type="submit">Factuur betalen</button>

        <?php echo Form::close(); ?>
    </div>
@stop