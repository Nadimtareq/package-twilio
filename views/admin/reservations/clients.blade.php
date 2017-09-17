@extends('template.theme')

@section('fixedMenu')
<a href="{{ url('faq/8/reserveren') }}" class="ui black icon big launch right attached fixed button">
    <i class="question mark icon"></i>
    <span class="text">Veelgestelde vragen</span>
</a><br />
<script type="text/javascript">
        $("#pageLoader").fadeOut('slow');
</script>
@stop

@section('content')


<div class="content">
    <div class="ui breadcrumb">
        <a href="#" data-activates="slide-out" class="sidebar open">Menu</a>
        <i class="right chevron icon divider"></i>

        <a href="{{ url('admin/reservations'.(isset($companyParam) && $userCompany == TRUE ? '/clients/'.$company : '/clients')) }}" class="section">Reserveringen
        </a>

        <i class="right chevron icon divider"></i>

        @if(trim($date) != '')
        <div class="active section">{{ date('d-m-Y', strtotime($date)) }}</div>
        @else
        <div class="active section">Alle reserveringen</div>
        @endif
    </div>

    <div class="ui divider"></div>

    <input type="hidden" name="redirectUrl" value="{{ url('admin/reservations/clients'.(isset($companyInfo->name) ? '/'.$companyInfo->id : '')) }}">
    <input type="hidden" name="company" value="{{ isset($companyInfo->name) ? $companyInfo->id : '' }}">
    <input type="hidden" name="date" value="{{ $date }}">

    <div class="buttonToolbar">
        <div class="ui grid">
            <div class="row">
                <div class="left floated sixteen wide mobile ten wide computer column">
                    <a href="{{ url('admin/guests/create-reservation'.$companyParam) }}" class="ui icon button">
                        <i class="plus icon"></i>
                        Nieuw
                    </a>

                    @if ($userAdmin)
                    <div class="ui normal floating basic search selection dropdown">
                        <input type="hidden" name="companyCurrentId" value="{{ Request::segment(4) }}">

                        <div class="text">Bedrijf</div>
                        <i class="dropdown icon"></i>

                        <div class="menu">
                            @foreach($filterCompanies as $filterCompany)
                            <a class="item" data-value="{{ $filterCompany->id }}" href="{{ url('admin/reservations/clients/'.$filterCompany->id) }}">{{ $filterCompany->name }}</a>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    @if ($userAdmin)
                    <div class="ui normal floating basic search two wide compact selection dropdown">
                        <input type="hidden" name="source" value="{{ Request::input('source') }}">

                        <div class="text">Partij</div>
                        <i class="dropdown icon"></i>

                        <div class="menu">
                            <a href="{{ url('admin/reservations/clients/'.$companyParam.'?'.http_build_query(array_add($queryString, 'source', 'seatme'))) }}" data-value="seatme" class="item">SeatMe</a>
                            <a href="{{ url('admin/reservations/clients/'.$companyParam.'?'.http_build_query(array_add($queryString, 'source', 'eetnu'))) }}" data-value="eetnu" class="item">EetNU</a>
                            <a href="{{ url('admin/reservations/clients/'.$companyParam.'?'.http_build_query(array_add($queryString, 'source', 'couverts'))) }}" data-value="couverts" class="item">Couverts</a>
                            <a href="{{ url('admin/reservations/clients/'.$companyParam.'?'.http_build_query(array_add($queryString, 'source', 'wifi'))) }}" data-value="wifi" class="item">W-Fi</a>
                        </div>
                    </div>
                    @endif
                    <?php echo Form::select('city', (isset($preference[9]) ? $preference[9] : array()), Request::input('city'), array('id' => 'cityRedirect', 'class' => 'ui normal search three wide dropdown')); ?>

                </div>

                <div class="right floated sixteen wide mobile six wide computer column">
                     <div class="ui icon input">
                        <input type="text" class="ajax-datepicker" placeholder="Datum" data-value="{{ $date }}">
                        <i class="calendar icon"></i>
                    </div>

                    <a href="{{ url('admin/reservations'.$companyParam) }}" class="ui icon button">
                        <i class="options icon"></i>
                        Instellingen
                    </a>
                </div>

                <div class="left floated sixteen wide mobile sixteen wide computer column">
                    <div class="ui labeled green button" tabindex="0">
                        <a href="{{ url('admin/reservations/clients/'.(isset($companyInfo->name) ? $companyInfo->id : '').'/'.($date == null ? '' : date('Ymd', strtotime($date)))) }}"  class="ui green button">
                            <i class="white checkmark icon"></i> Gereserveerd
                        </a>

                        <a href="{{ url('admin/reservations/clients/'.(isset($companyInfo->name) ? $companyInfo->id : '').'/'.($date == null ? '' : date('Ymd', strtotime($date)))) }}" class="ui basic label">
                            {{ $statistics['allReservations'] }}
                        </a>
                    </div>

                    <div class="ui labeled orange button" tabindex="0">
                        <a href="{{ url('admin/reservations/clients/'.(isset($companyInfo->name) ? $companyInfo->id : '').'/'.($date == null ? '' : date('Ymd', strtotime($date))).'?cancelled=1') }}" class="ui orange button">
                            <i class="minus circle white icon"></i> Geannuleerd
                        </a>

                        <a href="{{ url('admin/reservations/clients/'.(isset($companyInfo->name) ? $companyInfo->id : '').'/'.($date == null ? '' : date('Ymd', strtotime($date))).'?cancelled=1') }}" class="ui basic label">
                            {{ $statistics['cancelledReservations'] }}
                        </a>
                    </div>

                    <div class="ui labeled green button" tabindex="0">
                        <a href="{{ url('admin/reservations/clients/'.(isset($companyInfo->name) ? $companyInfo->id : '').'/'.($date == null ? '' : date('Ymd', strtotime($date))).'?status=noshow') }}" class="ui red button">
                           <i class="remove circle white icon"></i> No Show
                        </a>

                        <a href="{{ url('admin/reservations/clients/'.(isset($companyInfo->name) ? $companyInfo->id : '').'/'.($date == null ? '' : date('Ymd', strtotime($date))).'?status=noshow') }}" class="ui basic label">
                            {{ $statistics['noShowReservations'] }}
                        </a>
                    </div>

                    <div class="ui labeled green button" tabindex="0">
                        <a href="{{ url('admin/reservations/clients/'.(isset($companyInfo->name) ? $companyInfo->id : '').'/'.($date == null ? '' : date('Ymd', strtotime($date))).'?status=iframe') }}" class="ui blue button">
                            <i class="user white icon"></i> Eigen
                        </a>

                        <a href="{{ url('admin/reservations/clients/'.(isset($companyInfo->name) ? $companyInfo->id : '').'/'.($date == null ? '' : date('Ymd', strtotime($date))).'?status=iframe') }}" class="ui basic label">
                            {{ $statistics['iframeReservations'] }}
                        </a>
                    </div>

                    <div class="ui labeled green button" tabindex="0">
                        <a href="{{ url('admin/reservations/clients/'.(isset($companyInfo->name) ? $companyInfo->id : '').'/'.($date == null ? '' : date('Ymd', strtotime($date))).'?status=email') }}" class="ui button">
                             <i class="mail white icon"></i> E-mail
                        </a>

                        <a class="ui basic label">
                             {{ $statistics['thirdPartyReservations'] }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div><br>
 </div>
 <div class="container">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 offset-l">
            <div class="table-responsive">
                <div id="formList">
                    <table id="tableClients" class="table table-striped table-hover table-bordered tables">
                        <thead>
                        <tr>
                            <th data-slug="disabled" class="one wide">Tafel</th>
                            <th data-slug="time" class="four wide">Datum reservering</th>
                            <th data-slug="persons" class="one wide">Gasten</th>
                            <th data-slug="name" class="four wide">Gereserveerd als</th>
                            <th data-slug="deal" class="four wide">Gereserveerd Deal</th>

                            <th data-slug="allergies" class="one wide">Allergie&euml;n</th>
                            <th data-slug="preferences" class="one wide">Voorkeuren</th>
                            <th data-slug="comment" class="two wide">Opmerking</th>

                            <th data-slug="saldo" class="one wide">Saldo</th>
                            <th data-slug="discount" class="one wide">Korting</th>
                            <th data-slug="restaurant_is_paid" class="one wide">Betaald</th>
                            @if ($company == NULL)
                                <th data-slug="name" class="two wide">Bedrijf</th>
                            @endif
                            <th data-slig="disabled" class="four wide" style="pointer-events: none;cursor: default;">Opties</th>
                        </tr>
                        </thead>
                        <tbody class="list search">
                        @if(count($data) >= 1)
                            @include('admin/reservations.list-clients')
                        @else
                            <tr>
                                <td colspan="2"><div class="ui error message">Er is geen data gevonden.</div></td>
                            </tr>
                        @endif
                        </tbody>
                    </table><br /><br />
                </div>
            </div>

        </div>
    </div>

</div>




    <div class="ui grid container">
        <div class="row">
            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                <div class="left floated sixteen wide mobile ten wide computer column">
                    {!! with(new \App\Presenter\Pagination($data->appends($paginationQueryString)))->render() !!}
                </div>
                <div class="col-lg-4 col-md-4 col-sn-4 col-xs-8">
                    <div class="right floated sixteen wide mobile sixteen wide tablet three wide computer column">
                        @include('admin.template.limit')
                    </div>
                </div>
            </div>
        </div>
    </div>
<div class="clear"></div>

@stop

