@extends('template.theme')

@inject('invoiceModel', 'App\Models\Invoice')

@section('scripts')
@include('admin.template.remove_alert')

<script type="text/javascript">
    $(document).ready(function() {
        $('.payment-status').on('change', function() {
         $('#formList').submit();
     });
    });
</script>
@stop

@section('content')
<div class="content">
    @include('admin.template.breadcrumb')
    
    <div class="buttonToolbar">  
        <div class="ui grid">
            @if ($userAdmin)
            <div class="sixteen wide mobile six wide tablet four wide computer column">
                <a href="{{ url('admin/invoices/create') }}" class="ui icon blue button">
                    <i class="plus icon"></i> Nieuw
                </a>

                <button id="removeButton" type="submit" name="action" value="remove" class="ui disabled icon grey button">
                    <i class="trash icon"></i> Verwijderen
                </button>
            </div>
            @endif

            <div class="sixteen wide mobile ten wide tablet twelve wide computer column">
                <div class="ui grid">
                    <div class="three column row">
                        <div class="sixteen wide mobile nine wide tablet ten wide computer column">
                            <?php echo Form::open(array('method' => 'GET', 'class' => 'ui form')); ?>
                            <div class="three fields">
                                <div class="field">
                                    <?php echo Form::select('month', isset($selectMonths) ? $selectMonths : '', (Request::has('month') ? Request::has('month') : date('m')), array('class' => 'multipleSelect', 'data-placeholder' => 'Maand')); ?>
                                </div>

                                <div class="field">
                                    <?php echo Form::select('year', isset($selectYears) ? $selectYears : '', (Request::has('year') ? Request::get('year') : date('Y')), array('class' => 'multipleSelect', 'data-placeholder' => 'Jaar')); ?>
                                </div>     

                                <div class="field">
                                    <button type="submit" class="ui icon fluid filter button">
                                        <i class="filter icon"></i> 
                                    </button>
                                </div>
                            </div>

                            <?php echo Form::close(); ?>
                        </div>

                        <div class="sixteen wide mobile three wide tablet two wide computer column">
                            @include('admin.template.search.form')
                        </div>

                        <div class="sixteen wide mobile four wide tablet four wide computer column">
                            <div class="ui normal selection fluid dropdown">
                                <input type="hidden" name="paid" value="{{ Request::input('paid') }}">
                                <i class="dropdown icon"></i>

                                <div class="default text">Betaal status</div>

                                <div class="menu">
                                    <a href="{{ url('admin/invoices'.($slug ? '/overview/'.$slug : '').'?paid=1') }}" class="item" data-value="1">Voldaan</a>
                                    <a href="{{ url('admin/invoices'.($slug ? '/overview/'.$slug : '').'?paid=0') }}" class="item" data-value="0">Niet voldaan</a>
                                    <a href="{{ url('admin/invoices'.($slug ? '/overview/'.$slug : '').'?paid=2') }}" class="item" data-value="2">Geannuleerd</a>
                                    <a href="{{ url('admin/invoices'.($slug ? '/overview/'.$slug : '').'?paid=3') }}" class="item" data-value="3">Geincasseerd</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <br />

    <?php echo Form::open(array('url' => 'admin/invoices/action', 'method' => 'post', 'id' => 'formList', 'class' => 'ui form')) ?><table class="ui sortable very basic collapsing celled unstackable table" style="width: 100%;">
            <thead>
                <tr>
                    @if ($userAdmin)
                    <th data-slug="disabled" class="disabled one wide">
                        <div class="ui master checkbox">
                           <input type="checkbox" name="example">
                           <label></label>
                       </div>
                   </th>
                   @endif
                   <th data-slug="invoice_number" class="one wide">Factuurnummer</th>
                   <th data-slug="start_date" class="one wide">Startdatum</th>
                   <th data-slug="disabled" class="one wide">Verloopdatum</th>
                   <th data-slug="total_persons" class="one wide">Te betalen</th>
                   <th data-slug="total_persons" class="one wide">Te ontvangen</th>
                   <th data-slug="name" class="two wide">Restaurant</th>
                    <th data-slug="paid" class="two wide">Betaald</th><!-- 
                    <th data-slug="type" class="one wide">Soort</th> -->
                    <th data-slug="disabled" class="disabled two wide"></th>
                </tr>
            </thead>
            <tbody class="list search">
                @if(count($invoices) >= 1)
                {{ $slug }}
                @foreach($invoices as $invoice)
                <tr>
                    @if ($userAdmin)
                    <td>
                        <div class="ui child checkbox">
                            <input type="checkbox" name="id[]" value="{{ $invoice->id }}">
                            <label></label>
                        </div>
                    </td>
                    @endif
                    <td>{{ $invoice->invoice_number }}{{ ($userAdmin == 1 && $invoice->debit_credit == 'credit' ? '-'.$invoice->debit_credit : '') }}</td>
                    <td>{{ date('d-m-Y', strtotime($invoice->start_date)) }}</td>
                    <td>{{ date('d-m-Y', strtotime($invoice->start_date.' +14 days')) }}</td>
                    <td>
                        @if ($slug == null)
                        @if ($invoice->type != 'products')
                        &euro;{{ $invoice->total_saldo }}
                        @endif
                        @else
                        @if ($invoice->type == 'products')
                        {{ $invoiceModel->getTotalProductsSaldo($invoice->products) }}
                        @else
                        &euro;{{ ($invoice->total_persons * 1.21) }}
                        @endif
                        @endif
                    </td>
                    <td>
                        @if ($slug == null)
                        @if ($invoice->type == 'products')
                        {{ $invoiceModel->getTotalProductsSaldo($invoice->products) }}
                        @else
                        &euro;{{ ($invoice->total_persons * 1.21) }}
                        @endif
                        @else
                        @if ($invoice->type != 'products')
                        &euro;{{ $invoice->total_saldo }}
                        @endif
                        @endif
                    </td>
                    <td>{{ $invoice->name }}</td>
                    <td>
                        @if ($userAdmin)
                        <div class="ui normal selection dropdown payment-status">
                            <input type="hidden" name="paid" value="{{ $invoice->paid }}">
                            <i class="dropdown icon"></i>

                            <div class="default text">Status</div>

                            <div class="menu">
                                <a href="{{ url('admin/invoices/setpaid?paid=1&invoice_id='.$invoice->id) }}" class="item" data-value="1">Voldaan</a>
                                <a href="{{ url('admin/invoices/setpaid?paid=0&invoice_id='.$invoice->id) }}" class="item" data-value="0">Niet voldaan</a>
                                <a href="{{ url('admin/invoices/setpaid?paid=2&invoice_id='.$invoice->id) }}" class="item" data-value="2">Geannuleerd</a>
                                <a href="{{ url('admin/invoices/setpaid?paid=3&invoice_id='.$invoice->id) }}" class="item" data-value="3">Geincasseerd</a>
                            </div>
                        </div>
                        @else
                        @if ($invoice->paid == 0)
                        <span class="ui label fluid red">Niet betaald</span>
                        @elseif ($invoice->paid == 2)
                        <span class="ui label fluid red">Geannuleerd</span>
                        @else
                        <span class="ui label fluid green">Betaald</span>
                        @endif
                        @endif
                    </td>
                    <td>
                        <div class="ui buttons">
                            @if ($userAdmin)
                            <a href="{{ url('admin/invoices/send/'.$invoice->invoice_number) }}"  data-email="{{ $invoice->financial_email == NULL ? 'Geen emailadres' : $invoice->financial_email }}" class="ui icon invoices send {{ $invoice->getMeta('invoice_send') == 1 ? 'yellow' : '' }} button">
                                <i class="envelope icon"></i>
                            </a>

                            <a href="{{ url('admin/invoices/update/'.$invoice->id) }}" class="ui icon button">
                                <i class="pencil icon"></i>
                            </a>
                            @endif

                            <a href="{{ url('admin/invoices/download/'.$invoice->invoice_number) }}" class="ui icon button">
                                <i class="download icon"></i>
                            </a>
                            <span class="ui icon label">
                                {{-- <span class="method-icon micon" data-method="{{ $invoice->payment_method }}"></span> --}}

                                <a href="{{ url('payment/pay-invoice/pay/'.$invoice->invoice_number) }}" class="method-icon micon">
                                </a> 
                            </span>
                        </div>
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

    {!! with(new \App\Presenter\Pagination($invoices->appends($paginationQueryString)))->render() !!}

</div>
@stop