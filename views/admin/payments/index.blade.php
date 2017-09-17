@extends('template.theme')

@section('scripts')
@include('admin.template.remove_alert')
@stop

@section('content')
<div class="content">
    @include('admin.template.breadcrumb')
    
    <div class="buttonToolbar">  
        <div class="ui grid">
            <div class="left floated nine wide computer sixteen wide mobile column">
                <?php echo Form::open(array('method' => 'GET', 'class' => 'ui form')); ?>
                <div class="three fields">
                    <div class="field">
                        <?php 
                        echo Form::select(
                            'month', 
                            array_unique($months), 
                            Request::has('month') ? Request::has('month') : date('m'), 
                            array(
                                'class' => 'multipleSelect', 
                                'data-placeholder' => 'Maand'
                                )
                            ); 
                            ?>
                        </div>

                        <div class="field">
                           <?php echo Form::select('year', array_unique($years), (Request::has('year') ? Request::get('year') : date('Y')), array('class' => 'multipleSelect', 'data-placeholder' => 'Jaar')); ?>
                       </div>

                       <div class="field">
                        <input type="submit" class="ui blue fluid filter button" value="Filteren" />
                    </div>
                </div>
                <?php echo Form::close(); ?>
            </div>

            <div class="right floated sixteen wide mobile seven wide computer column">
               <div class="ui grid">
                <div class="three column row">
                    <div class="sixteen wide mobile four wide computer column">
                        @include('admin.template.limit')
                    </div>

                    <div class="sixteen wide mobile eight wide computer column">
                        <div class="ui normal icon fluid selection dropdown">
                            <input type="hidden" name="filters">
                            <i class="filter icon"></i>

                            <span class="text">Filter</span>
                            <i class="dropdown icon"></i>

                            <div class="menu">
                                <div class="header">
                                    <i class="tags icon"></i>
                                    Status
                                </div>

                                <div class="scrolling menu">
                                    <a class="item" 
                                    href="{{ url('admin/'.$slugController.'?'.http_build_query(array_add($queryString, 'status', 'paid'))) }}" 
                                    data-value="Betaald">
                                    <div class="ui green empty circular label"></div>
                                    Betaald
                                </a>

                                <a class="item" 
                                href="{{ url('admin/'.$slugController.'?'.http_build_query(array_add($queryString, 'status', 'pending'))) }}" 
                                data-value="In behandeling">
                                <div class="ui orange empty circular label"></div>
                                In behandeling
                            </a>

                            <a class="item" 
                            href="{{ url('admin/'.$slugController.'?'.http_build_query(array_add($queryString, 'status', 'open'))) }}" 
                            data-value="Open">
                            <div class="ui blue empty circular label"></div>
                            Open
                        </a>
                        <a class="item"
                            href="{{ url('admin/'.$slugController.'?'.http_build_query(array_add($queryString, 'status', 'cancelled'))) }}"
                            data-value="Cancelled">
                            <div class="ui red empty circular label "></div>
                            Geannuleerd
                        </a>
                    <a class="item" 
                    href="{{ url('admin/'.$slugController.'?'.http_build_query(array_add($queryString, 'status', 'expired'))) }}"
                    data-value="Expired">
                    <div class="ui light-red empty circular label"></div>
                    Verlopen
                </a>
            </div>
        </div>
    </div>
</div>

<div class="sixteen wide mobile four wide computer column">
     @include('admin.template.search.form') 
</div>
</div>
</div>
</div>
</div>

<div class="col-xs-12">

    <div class="col-sm-6">
        <div class="ui normal icon selection fluid dropdown">
            <input type="hidden" name="source" value="{{ Request::input('source') }}">

            <div class="text">Partij</div>
            <i class="dropdown icon"></i>

            <div class="menu">
                <a href="{{ url('admin/payments?'.http_build_query(array_add($queryString, 'source', 'seatme'))) }}" data-value="seatme" class="item">SeatMe</a>
                <a href="{{ url('admin/payments?'.http_build_query(array_add($queryString, 'source', 'eetnu'))) }}" data-value="eetnu" class="item">EetNU</a>
                <a href="{{ url('admin/payments?'.http_build_query(array_add($queryString, 'source', 'couverts'))) }}" data-value="couverts" class="item">Couverts</a>
                <a href="{{ url('admin/payments?'.http_build_query(array_add($queryString, 'source', 'wifi'))) }}" data-value="wifi" class="item">Wi-Fi</a>
            </div>
        </div>
    </div>

    <div class="col-sm-6">
        <?php echo Form::select('city', (isset($preference[9]) ? $preference[9] : array()), Request::input('city'), array('id' => 'cityRedirect', 'class' => 'ui normal search fluid dropdown')); ?>
    </div>
</div>
</div><br>

<?php echo Form::open(array('id' => 'formList', 'method' => 'post')) ?>
<button id="removeButton" type="submit" name="action" value="remove" class="ui disabled icon grey button">
    <i class="trash icon"></i> Verwijderen
</button>   

<table class="ui sortable very basic collapsing celled unstackable table" style="width: 100%;">
    <thead>
        <tr>
            <th data-slug="disabled" class="disabled">
                <div class="ui master checkbox"><input type="checkbox"></div>
            </th>
            <th data-slug="created_at" class="four wide">Datum</th>
            <th data-slug="amount" class="two wide">Bedrag</th>
            <th data-slug="name" class="three wide">Gebruiker</th>
            <th data-slug="name" class="three wide">Email</th>
            <th data-slug="mollie_id" class="two wide">Payment Type</th>
            <th data-slug="mollie_id" class="two wide">Mollie ID</th>
            <th data-slug="payment_type" class="two wide">Betalingswijze</th>
            <th data-slug="status" class="three wide">Status</th>
            <th data-slug="disabled" class="one disabled wide"></th>
        </tr>
    </thead>
    <tbody class="list search">
        @if(count($payments) >= 1)
        @foreach($payments as $payment)
        <tr>
         <td>
            <div class="ui child checkbox">
                <input type="checkbox" name="id[]" value="{{ $payment->id }}">
                <label></label>
            </div>
        </td>
        <td>{{ date('d-m-Y', strtotime($payment->created_at)) }} om {{ date('H:i', strtotime($payment->created_at)) }}</td>
        <td>&euro; {{ $payment->amount }}</td>
        <td><a href="{{ url('admin/users/update/'.$payment->user_id) }}">{{ $payment->name }}</a></td>
        <td><a href="{{ url('admin/users/update/'.$payment->user_id) }}">{{ $payment->email }}</a></td>
        <td>{{ $payment->type }}</td>
        <td>{{ $payment->mollie_id }}</td>
        <td>
            <span class="method-icon micon" data-method="{{ $payment->payment_type }}"></span> 
            {{ ucfirst($payment->payment_type) }}
        </td>
        <td>
            @if($payment->status == 'paid')
            <div class="ui green empty circular label"></div>
            @elseif($payment->status == 'open')
            <div class="ui blue empty circular label"></div>
            @elseif($payment->status == 'pending')
            <div class="ui orange empty circular label"></div>
            @elseif($payment->status == 'cancelled')
            <div class="ui red empty circular label"></div> 
            @elseif($payment->status == 'expired')
            <div class="ui light-red empty circular label"></div>
            @endif
        </td>
        <td>
            <a href="{{ url('admin/payments/update/'.$payment->id) }}" class="ui small icon button">
                <i class="pencil icon"></i>
            </a>
        </td>
    </tr>
    @endforeach
    @else
    <tr>
        <td colspan="2"><div class="ui error message">Er is geen data gevonden.</div></td>
    </tr>
    @endif
</tbody>
</table>
<?php echo Form::close(); ?>
@if($totalAmountForQuery)
    <div class="clearfix">&nbsp;</div>
    <div class="ui grid">
        <div class="row">
            <div class="four wide column">
                <div class="ui red segment">
                    <strong class="">Totaal bedrag: &euro;{{$totalAmountForQuery}}</strong><br>
                </div>
            </div>
        </div>
    </div>
    <div class="clearfix">&nbsp;</div>
@endif

{!! with(new \App\Presenter\Pagination($payments->appends($paginationQueryString)))->render() !!}<br><br>

</div>
@stop