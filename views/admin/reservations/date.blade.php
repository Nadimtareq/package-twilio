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
<script type="text/javascript">
    var activateAjax = 'reservationadmin';
</script>

<div class="content">
    <div class="ui breadcrumb">
        <a href="#" class="sidebar open">Admin</a>
        <i class="right chevron icon divider"></i>

        <a href="{{ url('admin/reservations'.(isset($companyParam) && $userCompany == TRUE ? '/clients'.$companyId : '')) }}" class="section">
            <div class="ui normal scrolling bread pointing dropdown item">
                <div class="text">Reserveringen</div>

                <div class="menu">
                    @if($userCompanies)
                         @include('template/navigation/company')
                    @endif
                    
                    @include('template/navigation/admin')
                </div>
            </div>
        </a>

        <i class="right chevron icon divider"></i>

        <div class="active section">{{ date('d-m-Y', strtotime($date)) }}</div>
    </div>

    <div class="ui divider"></div>

    <?php echo Form::open(array('id' => 'formList', 'url' => 'admin/'.$slugController.'/time/update', 'method' => 'post')) ?>
        <input type="hidden" name="redirectUrl" value="{{ url('admin/reservations/date/'.$company) }}">
        <input type="hidden" name="company" value="{{ $company }}">
        <input type="hidden" name="date" value="{{ $date }}">
        <input type="hidden" id="actionMan" name="action">

        <div class="buttonToolbar">
            <div class="ui grid">
                <div class="left floated sixteen wide mobile seven wide computer column">
                    <button id="openButton" type="submit" value="open" class="ui disabled green button">
                        <i class="unlock icon"></i> Open
                    </button>

                    <button id="closeButton" type="submit" value="lock" class="ui disabled red button">
                        <i class="lock icon"></i> Sluit
                    </button>

                    <button type="submit" value="update" id="personButton" class="ui button">
                        <i class="users icon"></i> Wijzig plaatsen
                    </button>
                </div>

                <div class="right floated sixteen wide mobile nine wide computer column">
                
                <div class="ui grid">
                        <div class="four column row">
                            <div class="column">
                                <div class="ui icon fluid input">
                                    <input type="text" class="ajax-datepicker" placeholder="Datum" />
                                    <i class="calendar icon"></i>
                                </div>
                            </div>

                            <div class="column">
                                 <?php 
                                 echo Form::select(
                                    'days[]', 
                                    array_values(config('preferences.days')), 
                                    Request::input('dayno'),
                                    array(
                                        'id' => 'daySelect', 
                                        'class' => 'multipleSelect', 
                                        'data-placeholder' => 'Dagen', 
                                        'multiple' => 'multiple'
                                    )
                                ); 
                                ?>
                            </div>

                            <div class="column">
                                <?php 
                                 echo Form::select(
                                    'time[]', 
                                    array_combine($times, $times), 
                                    Request::input('time'),
                                    array(
                                        'id' => 'timeSelect', 
                                        'class' => 'multipleSelect', 
                                        'data-placeholder' => 'Tijden', 
                                        'multiple' => 'multiple'
                                    )
                                ); 
                                ?>
                            </div>

                            <div class="column">
                                <button id="filterDayTime" data-url="{{ url('admin/reservations'.(isset($companyParam) && $userCompany == TRUE ? $companyParam : '')) }}" class="btn waves" type="button">
                                    <i class="filter icon"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div><br />

        <table class="ui sortable very basic collapsing celled unstackable table" style="width: 100%;">
            <thead>
            	<tr>
            		<th class="disabled"><div class="ui master checkbox"><input type="checkbox"></div></th>
                    <th data-slug="date" class="three wide">Datum</th>
                    <th data-slug="time" class="two wide">Tijd</th>
                    <th data-slug="available_persons" class="one wide">Plaatsen</th>
                    <th data-slug="available" class="two wide">Vrij</th>
                    <th data-slug="persons" class="two wide">Bezet</th>
                    <th data-slug="locked" class="two wide">Zichtbaar</th>
                    <th class="two wide"></th>
            	</tr>
            </thead>
            <tbody class="list search">
                @if(count($data) >= 1)
                	@include('admin/reservations.list-date')
                @else
                    <tr>
                        <td colspan="2"><div class="ui error message">Er is geen data gevonden.</div></td>
                    </tr>
            	@endif
            </tbody>
   		</table>
	<?php echo Form::close(); ?>

    
    {!! with(new \App\Presenter\Pagination($data->appends($paginationQueryString)))->render() !!}
</div>
<div class="clear"></div>

@stop