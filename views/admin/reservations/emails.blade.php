@extends('template.theme')

@section('scripts')
    @include('admin.template.remove_alert')
@stop

@section('fixedMenu')
<a href="{{ url('faq/8/reserveren') }}" class="ui black icon big launch right attached fixed button">
    <i class="question mark icon"></i>
    <span class="text">Veelgestelde vragen</span>
</a><br />
@stop

@section('content')
<div class="content">
    <div class="ui breadcrumb">
        <a href="#" class="sidebar open">Admin</a>
        <i class="right chevron icon divider"></i>

        <div class="active section">E-mails</div>
    </div>

    <div class="ui divider"></div>

    <div class="ui grid">
        <div class="five wide column">
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
                            href="{{ url('admin/reservations/emails?'.http_build_query(array_add($queryString, 'network', 'couverts'))) }}"
                            data-value="couverts">
                        Couverts
                        </a>

                        <a class="item" 
                            href="{{ url('admin/reservations/emails?'.http_build_query(array_add($queryString, 'network', 'eetnu'))) }}"
                            data-value="eetnu">
                        Eet.nu
                        </a>

                        <a class="item" 
                            href="{{ url('admin/reservations/emails?'.http_build_query(array_add($queryString, 'network', 'seatme'))) }}"
                            data-value="seatme">
                        SeatMe
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="five wide column">
            @include('admin.template.limit')
        </div>
    </div>

    Hier staan alle reserveringen vanit de e-mails die niet automatisch zijn goedgekeurd door een bepaalde reden.<br /><br />
   
    <?php echo Form::open(array('id' => 'formList', 'method' => 'post')) ?>
        <div class="ui buttons">
            <button name="status" value="accept" class="ui green button">Goedkeuren</button>
            <button name="status" value="decline" class="ui red button">Afkeuren</button>
        </div><br><br>

        <label class="ui label blue">{{ ($guestThirdPartyData['pending'] > 0 ? $guestThirdPartyData['pending'] - $guestThirdPartyData['success'] : 0) }}</label> openstaande reserveringen &nbsp;
        <label class="ui label green">{{ $guestThirdPartyData['success'] }}</label> goedgekeurde reserveringen

        <table class="ui sortable very basic collapsing celled unstackable table" style="width: 100%;">
            <thead>
                <tr>
                    <th class="one wide disabled" data-slug="disabled">
                        <div class="ui master checkbox"><input type="checkbox"></div>
                    </th>
                    <th data-slug="created_at" class="two wide">Datum</th>
                    <th data-slug="reservation_date" class="two wide">Reservering</th>
                    <th data-slug="network_status" class="one wide">Status</th>
                    <th data-slug="name" class="three wide">Naam</th>
                    <th data-slug="restaurant_name" class="three wide">Restaurant</th>
                    <th data-slug="reservation_number" class="one wide">Reserveringsnummer</th>
                    <th data-slug="network" class="one wide">Netwerk</th>
                </tr>
            </thead>
            <tbody class="list search">
                @foreach($data as $reservation)
                    <?php 
                    $resDate = $carbon->create(
                          date('Y', strtotime($reservation->reservation_date)), 
                          date('m', strtotime($reservation->reservation_date)), 
                          date('d', strtotime($reservation->reservation_date)), 
                          date('H', strtotime($reservation->reservation_date)), 
                          date('i', strtotime($reservation->reservation_date)), 
                          date('s', strtotime($reservation->reservation_date))
                    );  

                    $createdDate = $carbon->create(
                      date('Y', strtotime($reservation->created_at)), 
                      date('m', strtotime($reservation->created_at)), 
                      date('d', strtotime($reservation->created_at)), 
                      date('H', strtotime($reservation->created_at)), 
                      date('i', strtotime($reservation->created_at)), 
                      date('s', strtotime($reservation->created_at)) 
                  );  
                ?>
                <tr>
                    <td>
                        <div class="ui child checkbox">
                            <input type="checkbox" name="id[{{ $reservation->id }}]" value="{{ $reservation->id }}">
                            <label></label>
                        </div>
                    </td>
                    <td>
                        {{ $createdDate->formatLocalized('%d %b %Y') }} {{ date('H:i', strtotime($reservation->created_at)) }}
                    </td>
                    <td>
                        {{ $resDate->formatLocalized('%d %b %Y') }} {{ date('H:i', strtotime($reservation->reservation_date)) }}
                    </td>z
                    <td>
                        @if ($reservation->network_status == 'confirmed')
                        <span class="ui green fluid label">Reservering</span>
                        @elseif ($reservation->network_status == 'updated')
                        <span class="ui blue fluid label">Wijziging</span>
                        @elseif ($reservation->network_status == 'cancelled')
                        <span class="ui red fluid label">Annulering</span>
                        @endif
                    </td>
                    <td>
                        <strong>Naam</strong><br>
                        {{ $reservation->name }}<br>

                        <strong>Personen</strong><br>
                        {{ $reservation->persons }}
                    </td>
                    <td>
                        <?php 
                        echo Form::select(
                            'reservation['.$reservation->id.'][restaurant_id]', 
                            array_add($companies, 0, 'Selecteer een bedrijf'), 
                            0,
                            array( 'class' => 'ui normal search dropdown')
                        );  
                        ?>

                        <strong>Bedrijfsnaam in mail:</strong><br>
                        {{ $reservation->restaurant_name }}
                    </td>
                    <td>
                        <div class="ui small input field">
                            <?php echo Form::text('reservation['.$reservation->id.'][reservation_number]', $reservation->reservation_number); ?>
                        </div>
                    </td>
                    <td>{{ $reservation->network }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    <?php echo Form::close(); ?>

    {!! with(new \App\Presenter\Pagination($data->appends($paginationQueryString)))->render() !!}

    <div class="clear"></div><br />
</div>
<div class="clear"></div>
@stop