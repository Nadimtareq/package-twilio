@extends('template.theme')


@section('scripts')
@include('admin.template.remove_alert')
@stop

@section('content')
<div class="content">
    @include('admin.template.breadcrumb')

    <div class="container">
        <div class="row">
            <div class="col-md-4 col-sm-4 col-xs-6">
                <a href="{{ url('admin/'.$slugController.'/create') }}" class="ui icon blue button" style="width:130px"><i
                            class="plus icon"></i> Nieuw</a>

                <button id="removeButton" type="submit" name="action" value="remove" class="ui disabled icon grey button">
                    <i class="trash icon"></i> Verwijderen
                </button>
                <a href="{{ url('admin/'.$slugController.'/list') }}" class="ui blue button" style="width: 130px">
                    Lijst</a>
            </div>

            <div class="col-md-2 col-sm-2 col-xs-6">
                <div class="sixteen wide mobile four wide computer column">
                    <div class="ui normal  search selection fluid dropdown"> <!-- icon class deleted-->
                        <input type="hidden" name="companiesId">
                        <i class="filter icon"></i>

                        <span class="text">Bedrijf</span>

                        <i class="dropdown icon"></i>

                        <div class="menu">
                            @foreach ($companies as $company)
                                <a class="item" href="{{ url('admin/guests/'.$company->slug) }}" data-value="{{ $company->id }}">
                                    {{ $company->name }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-2 col-sm-2 col-xs-6">
                <div class="sixteen wide mobile four wide computer column">
                    <?php echo Form::select('city', (isset($preference[9]) ? $preference[9] : array()), Request::input('city'), array('id' => 'cityRedirect', 'class' => 'ui normal search fluid dropdown')); ?>
                </div>
            </div>

            <div class="col-md-2 col-sm-2 col-xs-6">
                <div class="sixteen wide mobile four wide computer column">
                    <div class="ui normal floating basic search selection dropdown">
                        <input type="hidden" name="source" value="{{ Request::input('source') }}">

                        <div class="text">Partij</div>
                        <i class="dropdown icon"></i>

                        <div class="menu">
                            <a href="{{ url('admin/users?'.http_build_query(array_add($queryString, 'source', 'seatme'))) }}" data-value="seatme" class="item">SeatMe</a>
                            <a href="{{ url('admin/users?'.http_build_query(array_add($queryString, 'source', 'eetnu'))) }}" data-value="eetnu" class="item">EetNU</a>
                            <a href="{{ url('admin/users?'.http_build_query(array_add($queryString, 'source', 'couverts'))) }}" data-value="couverts" class="item">Couverts</a>
                            <a href="{{ url('admin/users?'.http_build_query(array_add($queryString, 'source', 'wifi'))) }}" data-value="wifi" class="item">Wi-Fi</a>
                        </div>
                    </div>
                </div>

            </div>

        </div><br>

        <div class="row">
            <div class="col-md-2 col-sm-2 col-xs-6">
                <div class="sixteen wide mobile four wide computer column">
                    <div class="ui normal floating basic search selection dropdown">
                        <input type="hidden" name="role" value="{{ Request::input('role') }}">

                        <div class="text">Soort</div>
                        <i class="dropdown icon"></i>

                        <div class="menu">
                            <a href="{{ url('admin/users?role=admin') }}" data-value="admin" class="item">Admin</a>
                            <a href="{{ url('admin/users?role=bedrijf') }}" data-value="bedrijf" class="item">Bedrijf</a>
                            <a href="{{ url('admin/users?role=barcode_user') }}" data-value="barcode_user" class="item">Barcode</a>
                            <a href="{{ url('admin/users?role=bediening') }}" data-value="bediening" class="item">Bediening</a>
                            <a href="{{ url('admin/users?role=callcenter') }}" data-value="callcenter" class="item">Call Center</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-2 col-sm-2 col-xs-6">
                <div class="sixteen wide mobile four wide computer column">
                    <div class="ui normal floating basic search selection dropdown">
                        <input type="hidden" name="has_saving" value="{{ Request::input('has_saving') }}">

                        <div class="text">Spaarhulp</div>
                        <i class="dropdown icon"></i>

                        <div class="menu">
                            <a href="{{ url('admin/users?'.http_build_query(array_add($queryString, 'has_saving', '1'))) }}" data-value="1" class="item">Ja</a>
                            <a href="{{ url('admin/users?'.http_build_query(array_add($queryString, 'has_saving', '0'))) }}" data-value="0" class="item">Nee</a>

                            <a href="{{ url('admin/users?'.http_build_query(array_add($queryString, 'has_saving', '2'))) }}" data-value="2" class="item">Mislukt</a>
                        </div>

                    </div>
                </div>
            </div>
            <div class="col-md-2 col-sm-2 col-xs-6">
                <div class="sixteen wide mobile four wide computer column">
                    <div class="ui normal floating basic search selection dropdown">
                        <input type="hidden" name="user_from" value="{{ Request::input('user_from') }}">

                        <div class="text">Type gebruikers</div>
                        <i class="dropdown icon"></i>

                        <div class="menu">
                            <a href="{{ url('admin/'.$slugController) }}" data-value="1" class="item">gebruikers</a>
                            <a href="{{ url('admin/'.$slugController.'/guestwifi') }}" data-value="2" class="item">Gast wifi</a>

                            <a href="{{ url('admin/'.$slugController.'/guestthirdparty') }}" data-value="3" class="item">Derde partij</a>
                        </div>

                    </div>
                </div>
            </div>
            <div class="col-md-2 col-sm-2 col-xs-8">
                <div class="sixteen wide mobile one wide computer column">
                    @include('admin.template.search.form')
                </div>
            </div>
        </div>
    </div>
    <?php echo Form::open(array('id' => 'formList', 'url' => 'admin/' . $slugController . '/delete', 'method' => 'post')) ?>
    <table class="ui very basic sortable collapsing celled table list" style="width: 100%;">
        <thead>
            <tr>
                <th data-slug="disabled">
        <div class="ui master checkbox">
            <input type="checkbox">
            <label></label>
        </div>
        </th>
        <th data-slug="name">Naam</th>
        <th data-slug="city">Stad</th>
        <th data-slug="gender">Geslacht</th>
        <th data-slug="phone">Telefoon</th>
        <th data-slug="email">E-mailadres</th>
        <th data-slug="newsletter">Nieuwsbrief</th>
        <th data-slug="saldo">Saldo</th>
        <th data-slug="created_at">Geregistreerd op</th>
        <th data-slug="updated_at">Laatst ingelogd</th>
        <th data-slug="disabled"></th>
        </tr>
        </thead>
        <tbody class="list">
            @if(count($data) >= 1)
            @foreach($data as $result)
            <tr>
                <td>
                    <div class="ui child checkbox">
                        <input type="checkbox" name="id[]" value="{{ $result->id }}">
                        <label></label>
                    </div>
                </td>
                <td>

                    <a href="{{ url('admin/'.$slugController.'/update/'.$result->id) }}">
                        {{ $result->name?$result->name:'' }}
                    </a>
                </td>
               <td>
                    @if(is_array(json_decode($result->city)) >= 1)
                    @foreach(json_decode($result->city) as $city)
                    @if(isset($regio[$city]))
                    {{ $regio[$city] }}
                    @endif
                    @endforeach
                    @else
                    Geen stad opgegeven
                    @endif
                </td>
                <td>{{ ($result->gender == 1 ? 'Man' : 'Vrouw') }}</td>
                <td>
                    <p style="display:none;font-size:12px;" class="less-phone" onmouseout="$(this).parent().find('span').show();$(this).hide();">
                        {{ $result->phone }}</p>
                    <span style="font-size:12px;" class="full-phone" onmouseover="$(this).parent().find('p').show();$(this).hide();">
                        {{ substr($result->phone,0,10) }}
                    </span>
                </td>
                <td>{{ $result->email }}</td>
                <td class="text-center">
                    <div class="ui normal search selection fluid dropdown">
                        <i class="icon checkmark {{ $result->newsletter == 1 ? 'green' : 'red' }}"></i>
                        <i class="dropdown icon"></i>
                        <div class="menu">
                            <a class="item" href="{{ url("admin/$slugController/newsletter?id=$result->id&status=1") }}">ja</a>
                            <a class="item" href="{{ url("admin/$slugController/newsletter?id=$result->id&status=0") }}">nee</a>
                        </div>
                    </div>
                </td>
                <td>
                    <a href="{{ url('account/reservations/saldo/'.$result->id) }}" class="ui fluid text1 aligned center label">&euro;{{ $result->saldo }}</a>

                    <a href="{{ url('admin/users/saldo/reset/'.$result->id) }}" class="ui mini blue fluid button">Reset</a>
                </td>
                <td>{{ date('d-m-Y H:i', strtotime($result->created_at)) }}</td>
                <td>{{ date('d-m-Y H:i', strtotime($result->updated_at)) }}</td>
                <td>
                    <a href="{{ url('admin/'.$slugController.'/update/'.$result->id) }}" class="ui icon tiny button">
                        <i class="pencil icon"></i>
                    </a>

                    <a href="{{ url('admin/'.$slugController.'/login/'.$result->id) }}" class="ui icon tiny orange button loginAs" data-content="Inloggen als {{ $result->name }}">
                        <i class="key icon"></i>
                    </a>
                </td>
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
    <br>
    <div class="clearfix"></div>
   <div class="ui grid container">
        <div class="row">
            <div class="col-md-9 col-sm-9 col-xs-12">
                {!! with(new \App\Presenter\Pagination($data->appends($paginationQueryString)))->render() !!}
                <div class="col-sm-2 col-md-2 col-xs-6 pull-right" style="padding-top: 20px"> @include('admin.template.limit')</div>
            </div>


        </div>
    </div>
</div>
<div class="clear"></div>
@stop