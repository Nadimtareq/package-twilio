@extends('template.theme')

@section('content')
<div class="content">
    @include('admin.template.breadcrumb')
    <div class="ui grid">
        <div class="sixteen wide mobile four wide computer column">
            <div class="ui normal floating basic search selection dropdown">
                <input name="source" type="hidden" value="{{ Request::input('source') }}">
                    <div class="text">
                        Partij
                    </div>
                    <i class="dropdown icon">
                    </i>
                    <div class="menu">
                        <a class="item" data-value="seatme" href="{{ url('admin/statistics/reservations?'.http_build_query(array_add($queryString, 'source', 'seatme'))) }}">
                            SeatMe
                        </a>
                        <a class="item" data-value="eetnu" href="{{ url('admin/statistics/reservations?'.http_build_query(array_add($queryString, 'source', 'eetnu'))) }}">
                            EetNU
                        </a>
                        <a class="item" data-value="couverts" href="{{ url('admin/statistics/reservations?'.http_build_query(array_add($queryString, 'source', 'couverts'))) }}">
                            Couverts
                        </a>
                        <a class="item" data-value="wifi" href="{{ url('admin/statistics/reservations?'.http_build_query(array_add($queryString, 'source', 'wifi'))) }}">
                            Wi-Fi
                        </a>
                        @if (count($sources) >= 1)
                            @foreach ($sources as $source)
                        <a class="item" data-value="{{ $source }}" href="{{ url('admin/statistics/reservations?'.http_build_query(array_add($queryString, 'source', str_slug($source)))) }}">
                            {{ $source }}
                        </a>
                        @endforeach
                        @endif
                    </div>
                </input>
            </div>
        </div>
        <div class="sixteen wide mobile ten wide computer column">
            <?php echo Form::open(array('method' =>
            'get')) ?>
            <div class="ui input">
                <?php
                    echo Form::text(
                        'from',
                        '',
                        array(
                            'class' =>
                'datepicker_no_min_date',
                            'placeholder' => 'Startdatum'
                        )
                    );
                    ?>
            </div>
            <div class="ui input">
                <?php
                    echo Form::text(
                        'to',
                        '',
                        array(
                            'class' =>
                'datepicker_no_min_date',
                            'placeholder' => 'Einddatum'
                        )
                    );
                    ?>
            </div>
            <button class="ui button" type="submit">
                <i class="search icon">
                </i>
            </button>
            <?php echo Form::close(); ?>
        </div>
    </div>
    <div class="ui statistics">
        <div class="statistic">
            <div class="value">
                {{--{{ $topStatistics->topUsers }}--}}
                    {{ count(\App\User::all()) }}
            </div>
            <div class="label">
                gebruikers
            </div>
        </div>
        <div class="statistic">
            <div class="value">
                {{--{{ $topStatistics->topCompanies }}--}}
                    {{ count(\App\Models\Company::all()) }}
            </div>
            <div class="label">
                bedrijven
            </div>
        </div>
        <div class="statistic">
            <div class="value">
                {{--{{ $topStatistics->topAffiliate }}--}}
                    {{ count(\App\Models\Affiliate::all()) }}
            </div>
            <div class="label">
                affiliaties
            </div>
        </div>
        <div class="statistic">
            <div class="value">
                {{--{{ $topStatistics->topTransactions }}--}}
                    {{ $totalTransactions }}
            </div>
            <div class="label">
                transacties
            </div>
        </div>
        <div class="statistic">
            <div class="value">
                {{--{{ $topStatistics->topReservations }}--}}
                    {{ $totalReservation }}
            </div>
            <div class="label">
                reserveringen
            </div>
        </div>
    </div>
    <br>
        <br>
            <div class="ui grid">
                <div class="row">
                    <div class="four wide column">
                        <div class="ui red segment">
                            <strong class="">
                                Top reservering: dagen
                            </strong>
                            <br>
                                <div class="ui divider">
                                </div>
                                <table class="ui very basic celled table">
                                    <thead>
                                        <tr>
                                            <th>
                                                Dag
                                            </th>
                                            <th>
                                                Aantal
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (count($topDays) >= 1)
                                @foreach ($topDays as $topDay)
                                        <tr>
                                            <td>
                                                {{ $dayName[$topDay->nameRow + 1] }}
                                            </td>
                                            <td>
                                                {{ $topDay->countRow }}x
                                            </td>
                                        </tr>
                                        @endforeach
                            @endif
                                    </tbody>
                                </table>
                            </br>
                        </div>
                    </div>
                    <div class="four wide column">
                        <div class="ui blue segment">
                            <strong class="">
                                Top reservering: tijden
                            </strong>
                            <br>
                                <div class="ui divider">
                                </div>
                                <table class="ui very basic celled table">
                                    <thead>
                                        <tr>
                                            <th>
                                                Tijd
                                            </th>
                                            <th>
                                                Aantal
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (count($topTimes) >= 1)
                                @foreach ($topTimes as $topTime)
                                        <tr>
                                            <td>
                                                {{ date('H:i', strtotime($topTime->nameRow)) }}
                                            </td>
                                            <td>
                                                {{ $topTime->countRow }}x
                                            </td>
                                        </tr>
                                        @endforeach
                            @endif
                                    </tbody>
                                </table>
                            </br>
                        </div>
                    </div>
                    <div class="four wide column">
                        <div class="ui yellow segment">
                            <strong class="">
                                Top reservering: personen
                            </strong>
                            <br>
                                <div class="ui divider">
                                </div>
                                <table class="ui very basic celled table">
                                    <thead>
                                        <tr>
                                            <th>
                                                Aantal personen
                                            </th>
                                            <th>
                                                Aantal
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (count($topPersons) >= 1)
                                @foreach ($topPersons as $topPerson)
                                        <tr>
                                            <td>
                                                {{ $topPerson->nameRow }} {{ $topPerson->nameRow == 1 ? 'persoon' : 'personen' }}
                                            </td>
                                            <td>
                                                {{ $topPerson->countRow }}x
                                            </td>
                                        </tr>
                                        @endforeach
                            @endif
                                    </tbody>
                                </table>
                            </br>
                        </div>
                    </div>
                    <div class="four wide column">
                        <div class="ui red segment">
                            <strong>
                                Reserveringen: Spaartegoed
                            </strong>
                            <br>
                                <div class="ui divider">
                                </div>
                                <table class="ui very basic celled table">
                                    <thead>
                                        <tr>
                                            <th>
                                                Spaartegoed
                                            </th>
                                            <th>
                                                Aantal
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                Wel spaartegoed
                                            </td>
                                            <td>
                                                reservationSaldo
                                            </td>
                                            {{--
                                            <td>
                                                {{ $topStatistics->reservationsSaldo }}x
                                            </td>
                                            --}}
                                        </tr>
                                        <tr>
                                            <td>
                                                Geen spaartegoed
                                            </td>
                                            <td>
                                                reservations
                                            </td>
                                            {{--
                                            <td>
                                                {{ $topStatistics->reservationsWithoutSaldo }}x
                                            </td>
                                            --}}
                                        </tr>
                                    </tbody>
                                </table>
                            </br>
                        </div>
                    </div>
                </div>
                

                <div class="row">
                    <div class="four wide column">
                        <div class="ui red segment">
                            <strong class="">
                                Top reservering: bedrijven
                            </strong>
                            <br>
                                <div class="ui divider">
                                </div>
                                <table class="ui very basic celled table">
                                    <thead>
                                        <tr>
                                            <th>
                                                Bedrijf
                                            </th>
                                            <th>
                                                Aantal
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (count($topCompanies) >= 1)

                                        {{ json_encode($topCompanies)}}
                                @foreach ($topCompanies as $topCompany)
                                        <tr>
                                            <td>
                                                {{ $topCompany->nameRow }}
                                            </td>
                                            <td>
                                                {{ $topCompany->countRow }}x
                                            </td>
                                        </tr>
                                        @endforeach
                            @endif
                                    </tbody>
                                </table>
                            </br>
                        </div>
                    </div>
                    <div class="four wide column">
                        <div class="ui red segment">
                            <strong class="">
                                Top kliks: bedrijven
                            </strong>
                            <br>
                                <div class="ui divider">
                                </div>
                                <table class="ui very basic celled table">
                                    <thead>
                                        <tr>
                                            <th>
                                                Bedrijf
                                            </th>
                                            <th>
                                                Aantal
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (count($topClicksCompanies) >= 1)
                                @foreach ($topClicksCompanies as $topClicksCompany)
                                        <tr>
                                            <td>
                                                {{ $topClicksCompany->nameRow }}
                                            </td>
                                            <td>
                                                {{ $topClicksCompany->countRow }}x
                                            </td>
                                        </tr>
                                        @endforeach
                            @endif
                                    </tbody>
                                </table>
                            </br>
                        </div>
                    </div>
                    <div class="four wide column">
                        <div class="ui red segment">
                            <strong class="">
                                Top kliks: FAQ
                            </strong>
                            <br>
                                <div class="ui divider">
                                </div>
                                <table class="ui very basic celled table">
                                    <thead>
                                        <tr>
                                            <th>
                                                Vraag
                                            </th>
                                            <th>
                                                Aantal
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (count($topClicksFaqs) >= 1)
                                @foreach ($topClicksFaqs as $topClicksFaq)
                                        <tr>
                                            <td>
                                                {{ $topClicksFaq->nameRow }}
                                            </td>
                                            <td>
                                                {{ $topClicksFaq->countRow }}x
                                            </td>
                                        </tr>
                                        @endforeach
                            @endif
                                    </tbody>
                                </table>
                            </br>
                        </div>
                    </div>
                    <div class="four wide column">
                        <div class="ui red segment">
                            <strong class="">
                                Top kliks: affiliaties
                            </strong>
                            <br>
                                <div class="ui divider">
                                </div>
                                <table class="ui very basic celled table">
                                    <thead>
                                        <tr>
                                            <th>
                                                Webshop
                                            </th>
                                            <th>
                                                Aantal
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (count($topClicksAffiliates) >= 1)
                                @foreach ($topClicksAffiliates as $topClicksAffiliate)
                                        <tr>
                                            <td>
                                                {{ $topClicksAffiliate->nameRow }}
                                            </td>
                                            <td>
                                                {{ $topClicksAffiliate->countRow }}x
                                            </td>
                                        </tr>
                                        @endforeach
                            @endif
                                    </tbody>
                                </table>
                            </br>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="four wide column">
                        <div class="ui red segment">
                            <strong class="">
                                Top kliks: voorkeuren
                            </strong>
                            <br>
                                <div class="ui divider">
                                </div>
                                <table class="ui very basic celled table">
                                    <thead>
                                        <tr>
                                            <th>
                                                Voorkeur
                                            </th>
                                            <th>
                                                Aantal
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (count($topClicksPreferences) >= 1)
                                @foreach ($topClicksPreferences as $topClicksPreference)
                                        <tr>
                                            <td>
                                                {{ $topClicksPreference->nameRow }}
                                            </td>
                                            <td>
                                                {{ $topClicksPreference->countRow }}x
                                            </td>
                                        </tr>
                                        @endforeach
                            @endif
                                    </tbody>
                                </table>
                            </br>
                        </div>
                    </div>
                </div>
            </div>
        </br>
    </br>
</div>
<div class="clear">
</div>
@stop
