<?php

use App\Models\Company;
?>
@extends('template.theme')

@section('scripts')
<script type="text/javascript">
    $(document).ready(function () {
        $('#removeButton').click(function () {
            swal({
                title: "Weet u het zeker?",
                text: "Weet u zeker dat u uw recensie(s) wil verwijderen?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Ja, ik weet het zeker!",
                closeOnConfirm: false
            }, function () {
                $('.ui.form').submit();
            });
        });
    });
</script>
@stop

@section('content')
<div class="content">
    <div class="ui breadcrumb">
        <a href="{{ url('/') }}" class="section">Home</a>
        <i class="right chevron icon divider"></i>

        <a href="#" class="sidebar open" data-activates="slide-out">Menu</a>
        <i class="right chevron icon divider"></i>

        <div class="active section">Mijn recencies</div>
    </div>

    <div class="ui divider"></div>

    @if(isset($data) && count($data) >= 1)
<?php echo Form::open(array('class' => 'ui form', 'method' => 'post')) ?>
    <div class="ui grid">
        <div class="left floated six wide column">
            <button id="removeButton" type="button" name="action" value="remove" class="ui icon grey button">
                <i class="trash icon"></i>
            </button>
        </div>
    </div>

    <table class="ui very basic collapsing celled table list">
        <thead>
            <tr>
                <th class="disabled one wide">
        <div class="ui master checkbox">
            <input type="checkbox">
            <label></label>
        </div>
        </th>
        <th class="two wide">Bedrijf</th>
        <th class="four wide">Recensie</th>
        <th class="one wide">Eten</th>
        <th class="one wide">Service</th>
        <th class="one wide">Decor</th>
        <th class="one wide"></th>
        </tr>
        </thead>
        <tbody class="list search">
            @if(isset($data))
            @foreach($data as $data)
            <tr>
                <td>
                    <div class="ui child checkbox">
                        <input type="checkbox" name="id[]" value="{{ $data->id }}">
                        <label></label>
                    </div>
                </td>
                <td><a href="{{ url('restaurant/'.$data->companySlug) }}">{{ $data->companyName }}</a></td>
                <td>{{ $data->content }}</td>
                <td><div class="ui star medium  rating no-rating" data-rating="{{ $data->food }}"></div></td>
                <td><div class="ui star medium  rating no-rating" data-rating="{{ $data->service }}"></div></td>
                <td><div class="ui star medium  rating no-rating" data-rating="{{ $data->decor }}"></div></td>
                <td>
                    <a href="{{ url('account/reviews/edit/'.$data->id) }}" class="ui label">Wijzig</a><br /><br />
                    <a href="{{ url('review/'.$data->id) }}" class="ui label">Delen</a>
                </td>
            </tr>
            @endforeach
            @endif
        </tbody>
    </table>
<?php echo Form::close(); ?>
    @else
    <div class="ui three mini steps">
        <a href="{{ url('/') }}" class="link step">
            <i class="search icon"></i>
            <div class="content">
                <div class="title">Zoek een restaurant naar keuze</div>
            </div>
        </a>

        <div class="step">
            <i class="save icon"></i>
            <div class="content">
                <div class="title">Tevreden of ontevreden?</div>
            </div>
        </div>

        <div class=" step">
            <i class="check mark icon"></i>
            <div class="content">
                <div class="title">Schrijf een beoordeling</div>
            </div>
        </div>
    </div>
    @endif
</div>
<div class="clear"></div>
@stop