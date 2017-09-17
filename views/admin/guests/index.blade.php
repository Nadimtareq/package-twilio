@extends('template.theme')

@section('scripts')
    @include('admin.template.remove_alert')
@stop

@section('content')
<div class="content">
    @include('admin.template.breadcrumb')

    <div class="buttonToolbar">  
        <div class="ui grid">
            <div class="left floated sixteen wide mobile seven wide computer column">
                <a href="{{ url('admin/guests/create/'.$slug) }}" class="ui icon blue button"><i class="plus icon"></i> Nieuw</a>
                <a href="{{ url('admin/guests/create-reservation/'.$slug) }}" class="ui icon blue button"><i class="plus icon"></i> Nieuwe reservering</a>
                        
                <button id="removeButton" type="submit" name="action" value="remove" class="ui disabled icon grey button">
                    <i class="trash icon"></i> Verwijderen
                </button>
            </div>

            <div class="right floated sixteen wide mobile nine wide computer column">
                 <div class="ui form">
                    <div class="four fields">
                        <div class="field">
                            @if($userAdmin)
                                <?php 
                                echo Form::select(
                                    'city[]', 
                                    isset($preference[9]) ? $preference[9] : array(),
                                    Request::input('city'),
                                    array(
                                        'id' => 'citySelect', 
                                        'multiple' => 'multiple',
                                        'data-placeholder' => 'Stad',
                                        'class' => 'multipleSelect'
                                    )
                                ); 
                                ?>
                            @endif
                        </div>

                        <div class="field">
                            <?php 
                            $preferencesCompany = array();

                            if (is_array(json_decode($companyInfo['preferences']))) {
                                $preferencesCompany = array_combine(
                                    json_decode($companyInfo['preferences']), 
                                    array_map('ucfirst', json_decode($companyInfo['preferences']))
                                );
                            }

                            echo Form::select(
                                'preferences[]', 
                                $preferencesCompany,
                                Request::input('preferences'),
                                array(
                                    'id' => 'preferencesSelect', 
                                    'multiple' => 'multiple',
                                    'data-placeholder' => 'Voorkeuren',
                                    'class' => 'multipleSelect'
                                )
                            ); 
                            ?>
                        </div>

                        <div class="field">
                            <?php 
                            $allergiesCompany = array();

                            if (is_array(json_decode($companyInfo['allergies']))) {
                                $allergiesCompany = array_combine(
                                    json_decode($companyInfo['allergies']), 
                                    array_map('ucfirst', json_decode($companyInfo['allergies']))
                                );
                            }

                            echo Form::select(
                                'allergies[]', 
                                $allergiesCompany,
                                Request::input('allergies'),
                                array(
                                    'id' => 'allergiesSelect',
                                    'multiple' => 'multiple',
                                    'data-placeholder' => 'Alergie&euml;n',
                                    'class' => 'multipleSelect'
                                )
                            ); 
                            ?>
                        </div>

                        <div class="field">
                            <button data-url="{{ url('admin/guests/'.$slug) }}" id="filterGuests" class="ui blue icon  button" type="button">
                                <i class="filter icon"></i> 
                            </button>

                            @include('admin.template.search.form')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div><br />

    <div class="ui small left floated buttons">
        <a href="{{ url('admin/guests/'.$slug) }}" class="ui {{ Request::has('newsletter') == FALSE ? 'active' : ' ' }} button">Alles</a>
        <a href="{{ url('admin/guests/'.$slug) }}?newsletter=0" class="ui {{ Request::has('newsletter') && Request::get('newsletter') == 0 ? 'active' : ' ' }} button">Geen nieuwsbrief</a>
        <a href="{{ url('admin/guests/'.$slug) }}?newsletter=1" class="ui {{ Request::has('newsletter') && Request::get('newsletter') == 1 ? 'active' : ' ' }}  button">Wel nieuwsbrief</a>
    </div>

    <div class="ui small right floated buttons">
        <a href="{{ url('admin/guests/export/'.$slug) }}" class="ui icon button"><i class="download icon"></i> Exporteer</a>
        <a href="{{ url('admin/guests/import/'.$slug) }}" class="ui icon button"><i class="plus icon"></i> Importeer</a>
    </div><br /><br />

    <?php echo Form::open(array('id' => 'formList', 'url' => 'admin/guests/delete/'.$slug, 'method' => 'post')) ?>
        <table class="ui very basic sortable collapsing celled table list" style="width: 100%;">
            <thead>
                <tr>
                    <th data-slug="disabled" class="disabled">
                        <div class="ui master checkbox">
                            <input type="checkbox" name="example">
                            <label></label>
                        </div>
                    </th>
                    <th data-slug="name">Naam</th>
                    <th data-slug="city">Stad</th>
                    <th data-slug="gender">Geslacht</th>
                    <th data-slug="phone">Telefoon</th>
                    <th data-slug="email">E-mailadres</th>
                    <th class="disabled"  data-slug="disabled">Voordeelpas</th>
                    <th class="disabled" data-slug="disabled">Nieuwsbrief</th>
                    <th data-slug="created_at">Geregistreerd op</th>
                    <th class="disabled" data-slug="disabled">Laatst gereserveerd op</th>
                    @if(Sentinel::inRole('admin') != FALSE)
                        <th class="disabled" data-slug="disabled"></th>
                    @endif
                </tr>
            </thead>
            <tbody class="list">
                @if(count($data) >= 1)
                    @foreach($data as $result)
                    <tr>
                        <td>
                            <div class="ui child checkbox">
                                <input type="checkbox" name="id[]" value="{{ $result->user_id }}">
                                <label></label>
                            </div>
                        </td>
                        <td>
                            <a href="{{ url('account/reservations/'.$slug.'/user/'.$result->user_id) }}">
                                {{ $result->name }}
                            </a>
                        </td>
                        <td>
                            @if(is_array(json_decode($result->city)) >= 1)
                                @foreach(json_decode($result->city) as $city)
                                    @if(isset($regio[$city]))
                                        {{ $regio[$city] }}
                                    @endif
                                @endforeach
                            @endif
                        </td>
                        <td>{{ ($result->gender == 1 ? 'Man' : $result->gender == 2 ? 'Vrouw' : '') }}</td>
                        <td>{{ $result->phone }}</td>
                        <td>{{ $result->email }}</td>
                        <td>
                            @if($result->discountCard == 1)
                            <a href="{{ url('admin/barcodes/'.$slug.'?user='.$result->id) }}">
                                Bekijk voordeelpas 
                            </a>
                            @else
                            Niet beschikbaar
                            @endif
                        </td>
                        <td><i class="circle small {{ $result->newsletter >= 1 ? 'green' : 'red' }} icon"></i></td>
                        <td>
                            {{ date('d-m-Y', strtotime($result->created_at)) }} 
                            om {{ date('H:i', strtotime($result->created_at)) }}
                        </td>
                        <td>
                            @if($result->last_reservation)
                            {{ date('d-m-Y', strtotime($result->last_reservation)) }} 
                            @endif

                            @if($result->last_reservation_time)
                            om {{ date('H:i', strtotime($result->last_reservation_time)) }}
                            @endif
                        </td>
                        @if(Sentinel::inRole('admin') != FALSE)
                            <td>
                                <a href="{{ url("admin/users/update/{$result->id}") }}" class="ui icon tiny button">
                                    <i class="pencil icon"></i>
                                </a>
                            </td>
                        @endif
                    </tr>
                    @endforeach
                 @else
                    <tr>
                        <td colspan="2"><div class="ui error message">Er is geen data gevonden.</div></td>
                    </tr>
                @endif
            </tbody>
        </table><br /><br />

    <?php echo Form::close(); ?>
     @include('admin.template.limit')
            {!! with(new \App\Presenter\Pagination($data->appends($paginationQueryString)))->render() !!}

</div>
<div class="clear"></div>
@stop