@extends('template.theme')

@section('scripts')
@include('admin.template.remove_alert')
@stop

@section('content')
<div class="content">
    @include('admin.template.breadcrumb')
    <div class="buttonToolbar">  
        <div class="ui grid">
            <div class="sixteen wide computer sixteen wide mobile column">
                <div class="ui grid">
                    <div class="five column row">
                        <div class="sixteen wide mobile four wide computer column">
                            <a href="{{ url('admin/'.$slugController.'/create') }}" class="ui icon blue button">
                                <i class="plus icon"></i> Nieuw
                            </a>

                            <button id="removeButton" type="submit" name="action" value="remove" class="ui disabled icon grey button">
                                <i class="trash icon"></i> Verwijderen
                            </button>   
                        </div>

                        <div class="sixteen wide mobile three wide computer column">
                            <?php echo Form::select('city', (isset($preference[9]) ? $preference[9] : array()), Request::input('city'), array('id' => 'cityRedirect', 'class' => 'ui normal search fluid dropdown')); ?>
                        </div>

                        <div class="sixteen wide mobile three wide computer column">
                            <div class="ui normal icon selection fluid dropdown">
                                <input type="hidden" name="status" value="{{ Request::input('status') }}">
                                <i class="filter icon"></i>

                                <span class="text">Status</span>

                                <i class="dropdown icon"></i>

                                <div class="menu">
                                    <div class="header">
                                        <i class="tags icon"></i>
                                        Status
                                    </div>

                                    <div class="scrolling menu">
                                        <a class="item" 
                                           href="{{ url('admin/'.$slugController.'?'.http_build_query(array_add($queryString, 'status', 'accepted'))) }}" 
                                           data-value="Goedgekeurd">
                                            <div class="ui green empty circular label"></div>
                                            Goedgekeurd
                                        </a>

                                        <a class="item" 
                                           href="{{ url('admin/'.$slugController.'?'.http_build_query(array_add($queryString, 'status', 'expired'))) }}" 
                                           data-value="Verlopen">
                                            <div class="ui blue empty circular label"></div>
                                            Vervallen
                                        </a>

                                        <a class="item" 
                                           href="{{ url('admin/'.$slugController.'?'.http_build_query(array_add($queryString, 'status', 'rejected'))) }}" 
                                           data-value="Afgekeurd">
                                            <div class="ui red empty circular label"></div>
                                            Afgekeurd
                                        </a>

                                        <a class="item" 
                                           href="{{ url('admin/'.$slugController.'?'.http_build_query(array_add($queryString, 'status', 'open'))) }}" 
                                           data-value=" In behandeling">
                                            <div class="ui orange empty circular label"></div>
                                            In behandeling
                                        </a>
                                    </div>
                                </div>
                                
                            </div>
                        </div>

                        <div class="sixteen wide mobile three wide computer column">
                            <div class="ui normal icon selection fluid dropdown">
                                <input type="hidden" name="filters" value="{{ Request::input('network') }}">
                                <i class="filter icon"></i>

                                <span class="text">Netwerk</span>

                                <i class="dropdown icon"></i>

                                <div class="menu">
                                    <div class="header">
                                        <i class="tags icon"></i>
                                        Netwerk
                                    </div>

                                    <div class="scrolling menu">
                                        <a class="item" 
                                           href="{{ url('admin/'.$slugController.'?'.http_build_query(array_add($queryString, 'network', 'affilinet'))) }}" 
                                           data-value="affilinet">
                                            Affilinet
                                        </a>

                                        <a class="item" 
                                           href="{{ url('admin/'.$slugController.'?'.http_build_query(array_add($queryString, 'network', 'daisycon'))) }}" 
                                           data-value="daisycon">
                                            Daisycon
                                        </a>

                                        <a class="item" 
                                           href="{{ url('admin/'.$slugController.'?'.http_build_query(array_add($queryString, 'network', 'tradedoubler'))) }}" 
                                           data-value="tradedoubler">
                                            Tradedoubler
                                        </a>

                                        <a class="item" 
                                           href="{{ url('admin/'.$slugController.'?'.http_build_query(array_add($queryString, 'network', 'tradetracker'))) }}" 
                                           data-value="tradetracker">
                                            Tradetracker
                                        </a>

                                        <a class="item" 
                                           href="{{ url('admin/'.$slugController.'?'.http_build_query(array_add($queryString, 'network', 'zanox'))) }}" 
                                           data-value="zanox">
                                            Zanox
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="sixteen wide mobile two wide computer column">
                            <div class="ui normal icon selection fluid dropdown">
                                <input type="hidden" name="source" value="{{ Request::input('source') }}">

                                <div class="text">Partij</div>
                                <i class="dropdown icon"></i>

                                <div class="menu">
                                    <a href="{{ url('admin/transactions?'.http_build_query(array_add($queryString, 'source', 'seatme'))) }}" data-value="seatme" class="item">SeatMe</a>
                                    <a href="{{ url('admin/transactions?'.http_build_query(array_add($queryString, 'source', 'eetnu'))) }}" data-value="eetnu" class="item">EetNU</a>
                                    <a href="{{ url('admin/transactions?'.http_build_query(array_add($queryString, 'source', 'couverts'))) }}" data-value="couverts" class="item">Couverts</a>
                                    <a href="{{ url('admin/transactions?'.http_build_query(array_add($queryString, 'source', 'wifi'))) }}" data-value="wifi" class="item">Wi-Fi</a>
                                </div>
                            </div>
                        </div>

                        <div class="sixteen wide mobile one wide computer column">
                            @include('admin.template.search.form')
                        </div>
                    </div>
                </div>
                <div class="five column row">
                    <div class="col-md-2">
                        <div class="sixteen wide mobile two wide computer column">
                            <div class="ui normal icon selection fluid dropdown">
                                <input type="hidden" name="aff" value="{{ Request::input('aff') }}">

                                <div class="text">Reference</div>
                                <i class="dropdown icon"></i>

                                <div class="menu">
                                    <a href="{{ url('admin/transactions?'.http_build_query(array_add($queryString, 'aff', 'BULL'))) }}" data-value="Extern" class="item">Extern</a>
                                    <a href="{{ url('admin/transactions?'.http_build_query(array_add($queryString, 'aff', 'NULL'))) }}" data-value="Eigen" class="item">Eigen</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div><br>

    <div class="ui grid">
        <div class="sixteen wide mobile four wide computer column">
            <div class="ui normal selection fluid dropdown item">
                <i class="list icon"></i>

                <div class="text">
                    {{ (isset($limit) ? $limit : 15) }} 
                </div>
                <i class="dropdown icon"></i>

                @if(isset($limit))
                <div class="menu">
                    <a class="item" href="{{ url('admin/'.$slugController.'?'.http_build_query(array_add($queryString, 'limit', '15'))) }}">15</a>
                    <a class="item" href="{{ url('admin/'.$slugController.'?'.http_build_query(array_add($queryString, 'limit', '30'))) }}">30</a>
                    <a class="item" href="{{ url('admin/'.$slugController.'?'.http_build_query(array_add($queryString, 'limit', '45'))) }}">45</a>
                </div>
                @endif
            </div>
        </div>

        <div class="sixteen wide mobile twelve wide computer column">
            <form method="get" action="{{ url('admin/'.$slugController.'?'.http_build_query($queryString)) }}">
                <div class="ui input">
                    <?php
                    echo Form::text(
                            'from', old('from'), array(
                        'class' => 'datepicker_no_min_date',
                        'placeholder' => 'Datum van',
                        'style' => 'width: 300px;'
                            )
                    );
                    ?>
                </div>

                <div class="ui input">
                    <?php
                    echo Form::text(
                            'to', old('to'), array(
                        'class' => 'datepicker_no_min_date',
                        'placeholder' => 'Datum tot',
                        'style' => 'width: 300px;'
                            )
                    );
                    ?>
                </div>
                <button class="ui button" type="submit"><i class="search icon"></i></button>
            </form>
        </div>
    </div>

    <?php echo Form::open(array('id' => 'formList', 'method' => 'post')) ?>

    <table class="ui sortable very basic collapsing celled unstackable table" style="width: 100%;">
        <thead>
            <tr>
                <th data-slug="disabled" class="one wide">
        <div class="ui master checkbox"><input type="checkbox"></div>
        </th>
        <th data-slug="created_at" class="two wide">Datum</th>
        <th data-slug="program_id" class="two wide">Programma</th>
        <th data-slug="amount" class="two wide">Totaal bedrag</th>
        <th data-slug="name" class="three wide">Gebruiker</th>
        <th data-slug="affiliate_network" class="two wide">Netwerk</th>
        <th data-slug="status" class="two wide">Status</th>
        <th data-slug="created_at" class="three wide">Vervalt op</th>
        <th data-slug="disabled" class="three wide"></th>
        </tr>
        </thead>
        <tbody class="list search">
            @if(count($data) >= 1)
            @foreach($data as $transaction)
            <tr>
                <td>
                    <div class="ui child checkbox">
                        <input type="checkbox" name="id[]" value="{{ $transaction->id }}">
                        <label></label>
                    </div>
                </td>
                <td>{{ date('d-m-Y', strtotime($transaction->created_at)) }}</td>
                <td>
                    <a href="{{ url('admin/transactions?shop='.strtolower($transaction->programSlug)) }}">
                        {{ $transaction->programName }}
                    </a>
                </td>
                <td>&euro; {{ $transaction->amount }}</td>
                <td>
                    @if (trim($transaction->name) != '')
                    <a href="{{ url('admin/users/update/'.$transaction->user_id) }}">
                        {{ $transaction->name }}
                    </a>
                    @else
                    <small>Onbekende gebruiker (geen subid)</smal>
                        @endif
                </td>
                <td>
                    <a href="{{ url('admin/transactions?network='.strtolower($transaction->affiliate_network)) }}">
                        {{ $transaction->affiliate_network }}
                    </a>
                </td>

                <td>
                    @if($transaction->status == 'accepted')
                    <div class="ui green empty circular label"></div> Goedgekeurd
                    @elseif($transaction->status == 'open')
                    <div class="ui orange empty circular label"></div> In behandeling (open)
                    @elseif($transaction->status == 'open')
                    <div class="ui red empty circular label"></div>  Verlopen
                    @elseif($transaction->status == 'rejected')
                    <div class="ui red empty circular label"></div> Afgekeurd
                    @elseif($transaction->status == 'expired')
                    <div class="ui red empty circular label"></div> Vervallen    
                    @endif
                </td>
                <td>{{ $transaction->getExpiredDate() }}</td>

                <td>
                    <a href="{{ url('admin/'.$slugController.'/update/'.$transaction->id) }}" class="ui icon tiny button">
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
    {!! with(new \App\Presenter\Pagination($data->appends($paginationQueryString)))->render() !!}
</div>
@stop