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
                <a href="{{ url('admin/reservations-options/create'.($slug != NULL ? '/'.$slug : '')) }}" class="ui icon blue button">
                    <i class="plus icon"></i> Nieuw
                </a>

                @if($admin)
                <button id="removeButton" type="submit" name="action" value="remove" class="ui disabled icon grey button">
                    <i class="trash icon"></i> Verwijderen
                </button>

                    <button id="deactivateButton" type="submit" name="action" value="deactivate" class="ui disabled icon red button">
                        <i class="cancel icon"></i> Veactiveren
                    </button>

                <button id="activateButton" type="submit" name="action" value="activate" class="ui disabled icon green button">
                    <i class="check icon"></i> Actief
                </button>
                @endif
            </div>
			 <div class=" row">
            <div class="right floated sixteen wide mobile  wide computer column">
                <div class="ui grid">
                    <div class="five column row" id="reservation-option-dropdown">
                        <div class="column">
                            @if ($admin)
                            <div class="ui normal icon search selection fluid dropdown">
                                <i class="filter icon"></i>

                                <div class="text">Bedrijf</div>

                                <i class="dropdown icon"></i>
                                <div class="menu">
                                    @if (count($companies) >= 1)
                                        @foreach ($companies as $company)
                                        <a class="item" href="{{ url('admin/reservations-options?company=').$company->id  }}">{{ $company->name }}</a>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                            @endif
                        </div>
                        <div class="column">
                            <div class="ui normal icon search selection fluid dropdown">
                                <i class="filter icon"></i>

                                <div class="text">Nieuwsbrief</div>

                                <i class="dropdown icon"></i>

                                <div class="menu">
                                    <a class="item" href="{{ url('admin/reservations-options?newsletter=1') }}">Ja</a>
                                    <a class="item" href="{{ url('admin/reservations-options?newsletter=0') }}">Nee</a>
                                </div>
                            </div>
                        </div>
                        <div class="column">
                            <div class="ui normal icon search selection fluid dropdown">
                                <i class="filter icon"></i>

                                <div class="text">Online</div>

                                <i class="dropdown icon"></i>

                                <div class="menu">
                                    <a class="item" href="{{ url('admin/reservations-options?online=1') }}">Yes</a>
                                    <a class="item" href="{{ url('admin/reservations-options?online=0') }}">No</a>
                                </div>
                            </div>
                        </div>
                        <div class="column">
                            @include('admin.template.limit')
                        </div>

                        <div class="column">
                            @include('admin.template.search.form')
                        </div>
                    </div>
                </div>
            </div>
            </div>
            <div class="sixteen wide mobile sixteen wide computer column" id="reservation-option-search-form">
            <form method="get" action="{{ url('admin/'.$slugController.'?'.http_build_query($queryString)) }}">
                <div class="ui input">
                    <?php
                    echo Form::text(
                            'city', old('city'),array('placeholder' => 'City')
                    );
                    ?>
                </div>
                <div class="ui input">
                    <?php
                    echo Form::text(
                            'from', old('from'), array(
                        'class' => 'datepicker_no_min_date',
                        'placeholder' => 'Online from',
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
                        'placeholder' => 'Online tot',
                        'style' => 'width: 300px;'
                            )
                    );
                    ?>
                </div>
                <button class="ui button" type="submit"><i class="search icon"></i></button>
            </form>
            </div>
        </div>
    </div>

    <?php echo Form::open(array('id' => 'formList', 'method' => 'post')) ?>
    <input type="hidden" id="action" name="action">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 offset-l">
                <div class="table-responsive">
                    <div id="formList">
                        <table id="tableClients" class="table table-striped table-hover table-bordered tables">
    {{--<table class="ui very basic sortable collapsing celled table list" style="width: 100%;">--}}
                    <thead>
                        <tr>
                            <th class="two wide" data-slug="disabled">
                                <div class="ui master checkbox">
                                    <input type="checkbox">
                                    <label></label>
                                </div>
                    </th>
                    @if($admin)
                        <th data-slug="name" class="three wide">Plaats</th>
                    @endif
                    <th data-slug="name" class="ten wide">Naam</th>
                    @if($admin)
                        <th data-slug="company_id" class="three wide">Bedrijf</th>
                    @endif
                    {{--  <th data-slug="city" class="three wide">City</th>  --}}
                    <th data-slug="total_amount" class="four wide">beschikbaar</th>
                    <th data-slug="total_res" class="four wide">verkocht</th>
                    <th data-slug="date_from" class="four wide">Online van</th>
                    <th data-slug="date_to" class="four wide">Online tot</th>
                    <th data-slug="price_from" class="four wide">prijs van</th>
                    <th data-slug="price" class="four wide">prijs voor</th>
                    <th data-slug="total_res" class="three wide">Online</th>
                    <th data-slug="newsletter" class="four wide">Nieuwsbrief</th>
                    <th data-slug="no_show" class="three wide">Show</th>
                    <th data-slug="reservated" class="three wide">Gereserveerd</th>
                    @if(Sentinel::inRole('admin'))
                        <th data-slug="status" class="four wide">Status</th>
                    @endif
                    <!-- <th data-slug="gasten" class="four wide">Gasten</th> -->
                    <th data-slug="disabled"></th>
                    </tr>
                    </thead>
                <tbody class="list">
                    @if(count($data) >= 1)
                        <?php $i=1;?>
                    @foreach($data as $result)

                    <tr>
                        <td>
                            <div class="ui child checkbox">
                                <input type="checkbox" name="id[]" value="{{ $result->id }}">
                                <label></label>
                            </div>
                        </td>

                        @if($admin)
                            <td>{{$i++}}</td>
                        @endif

                        <td>
                            {{ $result->name }}
                        </td>

                        @if($admin)
                            <td>
                                {{ $result->company_name }}
                             </td>
                        @endif

                        {{--  <td>
                            {{ $result->city }}
                        </td>  --}}
                        <td>
                            {{ $result->total_amount }}
                        </td>
                        <td>
                            {{ ($result->total_res)?$result->total_res:0 }}
                        </td>
                        <td>
                            <?php
                            if(isset($result->date_from)){
                             echo date('d-m-Y', strtotime($result->date_from));
                            }
                            ?>
                        </td>
                        <td>
                            <?php
                            if(isset($result->date_to)){
                                echo date('d-m-Y', strtotime($result->date_to));
                            }
                            ?>
                        </td>
                        <td>
                            {{ ($result->price_from)?$result->price_from:'' }}
                        </td>
                        <td>
                            {{ ($result->price)?$result->price:'' }}
                        </td>
                        <td>
                            <?php

                            $currentDate = date('Y-m-d');
                            ;
                            $contractDateBegin = date('Y-m-d', strtotime($result->date_from));
                            $contractDateEnd = date('Y-m-d', strtotime($result->date_to));
                            $result->date_from . '-' . $result->date_to;
                            if (($currentDate >= $contractDateBegin) && ($currentDate <= $contractDateEnd)) {
                                echo '<i class="icon green checkmark"></i>';
                            } else {
                                echo '<i class="icon red remove"></i>';
                            }
                            ?>
                        </td>
                        <td>
                            <?php
                            if ($result->newsletter != 0) {
                                echo '<i class="icon green checkmark"></i>';
                            } else {
                                echo '<i class="icon red remove"></i>';
                            }
                            ?>
                        </td>
                        <td>
                            <?php
                            if ($result->no_show != 0) {
                                echo '<i class="icon green checkmark"></i>';
                            } else {
                                echo '<i class="icon red remove"></i>';
                            }
                            ?>
                        </td>
                        <td>{{ $result->reservations }}</td>
                        @if(Sentinel::inRole('admin'))

                            <td class="text-center">
                                <div class="ui normal search selection fluid dropdown">
                                    <i class="icon checkmark {{ $result->no_show == 1 ? 'green' : 'red' }}"></i>
                                    <i class="dropdown icon"></i>
                                    <div class="menu">
                                        <a class="item" href="{{ url("admin/$slugController/show/status?id=$result->id&status=1") }}">ja</a>
                                        <a class="item" href="{{ url("admin/$slugController/show/status?id=$result->id&status=0") }}">nee</a>
                                    </div>
                                </div>
                            </td>

                        @endif
                        <td>
                            <div class="ui buttons">
                                <a href="{{ url('admin/companies/contract/'.$result->company_id.'/'.$result->slug) }}"
                                   target="_blank"
                                   class="ui icon tiny {{ (trim($result->signature_url) != '' ? 'red' : '') }} button">
                                    <i class="file pdf icon"></i>
                                </a>
                            <a href="{{ url('admin/'.$slugController.'/update/'.$result->id) }}" class="ui icon tiny button">
                                <i class="pencil icon"></i>
                            </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                    @else
                    <tr>
                        <td colspan="13"><div class="ui error message">Er is geen data gevonden.</div></td>
                    </tr>
                        @endif
                    </tbody>
                </table>
             </div>
           </div>
         </div>
        </div>
    </div>

<?php echo Form::close(); ?>

    {!! with(new \App\Presenter\Pagination($data->appends($paginationQueryString)))->render() !!}
</div>
<div class="clear"></div>
@stop

@section("after_scripts")
    <script>
        $(document).ready(function() {
        $('.ui.child.checkbox').checkbox({
            onChecked: function() {
                $('#removeButton').removeClass("disabled");
                $('#deactivateButton').removeClass("disabled");
                $('#activateButton').removeClass("disabled");
            }
        });


            $('#deactivateButton, #activateButton').click(function() {
            $('#action').val($(this).val());
            if ($('[name="id[]"]:checked').length == 0) {
                swal({
                    title: "Er is een fout opgetreden",
                    text: "U bent vergeten om een optie te selecteren.",
                    type: "warning"
                });
            } else {
                swal({
                    title: "Weet u het zeker?",
                    text: "Weet u zeker dat u deze actie wilt uitvoeren?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    cancelButtonText: "Nee",
                    confirmButtonText: "Ja, ik weet het zeker!",
                    closeOnConfirm: false
                }, function() {
                    $('#formList').submit();
                    return true;
                });
            }

            return false;
        });
        });
    </script>
@endsection