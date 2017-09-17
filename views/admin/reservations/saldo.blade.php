@extends('template.theme')

@section('fixedMenu')
<a href="{{ url('faq/20/financieel') }}" class="ui black icon big launch right attached fixed button">
    <i class="question mark icon"></i>
    <span class="text">Veelgestelde vragen</span>
</a><br />
@stop

@section('content')
<div class="content">
    <div class="ui breadcrumb">
        <a href="{{ url('/') }}">Home</a>
        <i class="right chevron icon divider"></i>

        <a href="#" class="sidebar open">Menu</a>
        <i class="right chevron icon divider"></i>

        <div class="section">{{ $companyName }}</div>
        <i class="right chevron icon divider"></i>

        <a href="{{ url('admin/'.$slugController.(isset($slugParam) ? $slugParam : '')) }}">
            <div class="ui normal scrolling bread pointing dropdown item">
                <div class="text">Saldo</div>

                <div class="menu">
                    @if($userCompanies)
                         @include('template/navigation/company')
                    @endif

                    @include('template/navigation/admin')
                </div>
            </div>
        </a>

        @if(Request::has('month') && Request::has('year'))
        <i class="right chevron icon divider"></i>

        <div class="section">{{ $months[Request::get('month')] }}</div>
        <i class="right chevron icon divider"></i>

        <div class="section">{{ Request::get('year') }}</div>
        @endif
    </div>

    <div class="ui divider"></div>

    <?php echo Form::open(array('method' => 'GET', 'class' => 'ui form')); ?>
        <div class="fields">
            <div class="four wide field">
                @if($userAdmin)
                <div id="companiesSearch" class="ui search fluid">
                    <div class="ui icon fluid input">
                        <input class="prompt" type="text" placeholder="Typ een naam in..">
                        <i class="search icon"></i>
                    </div>

                    <div class="results"></div>
                </div>
                @endif
            </div>

            <div class="three wide field">
                <?php
                echo Form::select(
                    'month',
                    $selectMonths,
                    (Request::has('month') ? Request::has('month') : date('m')),
                    array(
                        'class' => 'ui normal dropdown'
                    )
                );
                ?>
            </div>

            <div class="field">
                <?php
                echo Form::select(
                    'year',
                    $selectYears,
                    (Request::has('year') ? Request::get('year') : date('Y')),
                    array(
                        'class' => 'ui normal dropdown'
                    )
                );
                ?>
            </div>

            <div class="one wide field">
                <button type="submit" class="ui blue icon fluid filter button">
                    <i class="filter icon"></i>
                </button>
            </div>

            <div class="field">
                <div class="ui normal selection source dropdown item">
                    <input type="hidden" name="saldo" value="{{ Request::get('source') }}">
                    <div class="text">
                         Partij
                    </div>
                    <i class="dropdown icon"></i>

                    <div class="menu">
                        <a class="item" data-value="seatme" href="{{ url('admin/'.$slugController.'?'.http_build_query(array_add($queryString, 'source', 'seatme'))) }}">SeatMe</a>
                        <a class="item" data-value="eetnu" href="{{ url('admin/'.$slugController.'?'.http_build_query(array_add($queryString, 'source', 'eetnu'))) }}">EetNu</a>
                        <a class="item" data-value="couverts" href="{{ url('admin/'.$slugController.'?'.http_build_query(array_add($queryString, 'source', 'couverts'))) }}">Couverts</a>
                        <a class="item" data-value="wifi" href="{{ url('admin/'.$slugController.'?'.http_build_query(array_add($queryString, 'source', 'wifi'))) }}">W-Fi</a>
                    </div>
                </div>
            </div>

            <div class="field">
                <div class="ui normal selection fluid dropdown item">
                    <input type="hidden" name="saldo" value="{{ Request::get('saldo') }}">
                    <div class="text">
                         Spaartegoed
                    </div>
                    <i class="dropdown icon"></i>

                    <div class="menu">
                        <a class="item" href="{{ url('admin/'.$slugController) }}">Alles</a>
                        <a class="item" data-value="1" href="{{ url('admin/'.$slugController.'?'.http_build_query(array_add($queryString, 'saldo', 1))) }}">Met spaartegoed</a>
                        <a class="item" data-value="0" href="{{ url('admin/'.$slugController.'?'.http_build_query(array_add($queryString, 'saldo', 0))) }}">Zonder spaartegoed</a>
                    </div>
                </div>
            </div>
        </div>
    <?php echo Form::close(); ?><br>

    @if ($userAdmin)
    <div class="ui grid">
        <div class="three wide column">
            <div class="ui normal floating basic search selection dropdown">
                <input type="hidden" name="companyCurrentId" value="{{ Request::segment(4) }}">

                <div class="text">Bedrijf</div>
                <i class="dropdown icon"></i>

                <div class="menu">
                    @foreach($filterCompanies as $filterCompany)
                    <a class="item" data-value="{{ $filterCompany->id }}" href="{{ url('admin/reservations/saldo/'.$filterCompany->slug) }}">{{ $filterCompany->name }}</a>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="five wide column">
            <?php echo Form::select('city', (isset($preference[9]) ? $preference[9] : array()), Request::input('city'), array('id' => 'cityRedirect', 'class' => 'ui normal search fluid dropdown')); ?>
        </div>

        <div class="five wide column">
            <div class="ui normal selection fluid dropdown item">
                <input type="hidden" name="caller_id" value="{{ Request::get('caller_id') }}">

                <div class="text">Beller</div>
                <i class="dropdown icon"></i>

                <div class="menu">
                    @foreach ($callcenterUsers as $user)
                    <a class="item" data-value="1" href="{{ url('admin/reservations/saldo?'.http_build_query(array_add($queryString, 'caller_id', $user->id))) }}">{{ $user->name }}</a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @endif

    <?php echo Form::open(array('id' => 'formList')) ?>
    @if(Sentinel::inRole('admin'))
        <table class="ui sortable very basic collapsing celled unstackable table" style="width: 100%;">
            <thead>
            <tr>
                <th data-slug="date" data-column-order="desc" class="three wide">Datum en tijd</th>
                <th data-slug="companyName" data-column-order="desc" class="two wide">Bedrijf</th>
                <th data-slug="name" data-column-order="desc" class="two wide">Gereserveerd als</th>
                <th data-slug="phone" data-column-order="desc" class="two wide">Omschrijving</th>
                <th data-slug="persons" data-column-order="desc" class="one wide">Personen</th>
                <th data-slug="saldo" class="three wide">Kosten</th>
                <th data-slug="disabled" class="disabled one wide">Betaald</th>
                <th data-slug="saldo" class="three wide">Saldo</th>
            </tr>
            </thead>
            <tbody class="list search">
            @if(count($data) >= 1)
                @include('admin/reservations.list-saldo')
                <tr>
                    <td colspan="4">Totaal</td>
                    <td > {{ $totalPersons }} </td>
                    <td colspan="1" ><i class="euro icon"></i> {{ $totalKosten }}</td>
                    <td></td>
                    <td colspan="5"><i class="euro icon"></i> {{ number_format($totalSaldo, 2, '.', '') }}</td>
                </tr>
                <tr>
                    <td colspan="7">Totaal bedrag</td>

                    <td><i class="euro icon"></i> {{ number_format($totalSaldo - $totalKosten, 2, '.', '') }}</td>
                </tr>
            @else
                <tr>
                    <td colspan="2"><div class="ui error message">Er is geen data gevonden.</div></td>
                </tr>
            @endif
            </tbody>
        </table>



    @else

    <table class="ui sortable very basic collapsing celled unstackable table" style="width: 100%;">
        <thead>
            <tr>
                <th data-slug="date" data-column-order="desc" class="three wide">Datum en tijd</th>
                <th data-slug="name" data-column-order="desc" class="two wide">Gereserveerd als</th>
                <th data-slug="phone" data-column-order="desc" class="ten wide">Omschrijving</th>
                <th data-slug="persons" data-column-order="desc" class="one wide">Personen</th>
                <th data-slug="saldo" class="three wide">Kosten</th>
                <th data-slug="disabled" class="disabled one wide">Betaald</th>
                <th data-slug="saldo" class="three wide">Saldo</th>
            </tr>
        </thead>
        <tbody class="list search">
             @if(count($data) >= 1)
                @include('admin/reservations.list-saldo')
                <tr>
                    <td colspan="3">Totaal</td>
                    <td > {{ $totalPersons }} </td>
                    <td colspan="1" ><i class="euro icon"></i> {{ $totalKosten }}</td>
                    <td></td>
                    <td colspan="5"><i class="euro icon"></i> {{ number_format($totalSaldo, 2, '.', '') }}</td>
                </tr>
                <tr>
                    <td colspan="6">Totaal bedrag</td>

                    <td><i class="euro icon"></i> {{ number_format($totalSaldo - $totalKosten, 2, '.', '') }}</td>
                </tr>
            @else
                <tr>
                    <td colspan="2"><div class="ui error message">Er is geen data gevonden.</div></td>
                </tr>
            @endif
        </tbody>
   	</table>
    @endif
    <?php echo Form::close(); ?>
    @include('admin.template.limit')

    {!! with(new \App\Presenter\Pagination($data->appends($paginationQueryString)))->render() !!}

</div>
<div class="clear"></div>
@stop
