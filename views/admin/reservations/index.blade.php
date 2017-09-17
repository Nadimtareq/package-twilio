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
    var activateAjax = 'reservationindexadmin'; 
</script>

<div class="content">
    <div class="ui breadcrumb">
        <a href="#" class="sidebar open">Admin</a>
        <i class="right chevron icon divider"></i>

        <a href="{{ url('admin/reservations'.(isset($owner['company_id']) ? '/clients/'.$owner['company_id'] : '')) }}" class="section">
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

       <i class="right chevron icon divider"  style="margin-left:100px;"></i>

        <div class="active section" style="margin-top:5px;">Overzicht</div>
    </div>

    <div class="ui divider"></div>

    <?php echo Form::open(array('id' => 'formList', 'url' => 'admin/'.$slugController.'/date/update'.(Request::has('company') ? '?company='.Request::get('company') : ''), 'method' => 'post')) ?>
        <input type="hidden" id="redirectUrl" name="redirectUrl" value="<?php echo Request::url(); ?>" /> 
        <input type="hidden" id="actionMan" name="action" /> 

        <div class="buttonToolbar">
            <div class="ui grid">
                <div class="sixteen wide mobile seven wide computer column">
                    @if ($userAdmin)
                    <a href="{{ url('admin/reservations/create'.$slug) }}" class="ui compact icon blue button">
                        <i class="plus icon"></i>   
                    </a>
                    @endif

                    @if (isset($owner['company_id']))
                    <a href="{{ url('admin/reservations/update/'.(isset($owner['company_id']) ? $owner['company_id'].'?add=1' : '')) }}" class="ui compact icon orange button">
                        <i class="plus icon"></i>   
                    </a>
                    @endif

                    @if ($userAdmin)
                    <button id="removeButton" type="submit" name="action" value="remove" class="ui compact disabled icon grey button">
                        <i class="trash icon"></i>
                    </button>
                    @endif

                    <button id="personButton" type="submit" name="action" value="update" class="ui compact disabled button">
                        <i class="users icon"></i> Wijzig
                    </button>

                    <button id="openButton" type="submit" name="action" value="open" class="ui compact disabled green button">
                        <i class="unlock icon"></i> Open
                    </button>

                    <button id="closeButton" type="submit" name="action" value="close" class="ui compact disabled red button">
                        <i class="lock icon"></i> Sluit
                    </button>
                </div>

                <div class="sixteen wide mobile nine wide computer column">
                     <div class="ui grid">
                        <div class="five column row">
                            <div class="sixteen wide mobile four wide computer column">
                                <div class="ui icon fluid input">
                                    <input type="text" class="ajax-datepicker" placeholder="Datum" />
                                    <i class="calendar icon"></i>
                                </div>
                            </div>

                            <div class="sixteen wide mobile four wide computer column">
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

                            <div class="sixteen wide mobile three wide computer column">
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

                            <div class="sixteen wide mobile four wide computer column">
                                <button data-url="{{ url('admin/reservations'.$slug) }}" id="filterDayTime" class="ui blue icon button" type="button">
                                    <i class="filter icon"></i>
                                </button>

                                @include('admin.template.search.form')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div><br />
        
        <div class="buttonToolbar">
            @if($admin == TRUE)
            <div class="ui normal floating basic selection search large dropdown">
                <div class="text">Filter op bedrijf</div>
                <i class="dropdown icon"></i>

                <div class="menu">
                    @foreach($companies as $company)
                    <a class="item" href="{{ url('admin/reservations/'.$company->slug) }}">{{ $company->name }}</a>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <table class="ui sortable very basic collapsing celled unstackable table" style="width: 100%;">
            <thead>
            	<tr>
            		<th class="one wide disabled" data-slug="disabled">
            			<div class="ui master checkbox"><input type="checkbox"></div>
    				</th>
                    <th data-slug="date" class="three wide">Datum</th>
                    @if(Request::has('time'))
                    <th data-slug="time" class="two wide">Tijd</th>
                    @else
                    <th data-slug="start_time" class="two wide">Starttijd</th>
                    <th data-slug="end_time" class="two wide">Eindtijd</th>
                    @endif
                    <th data-slug="available_persons" class="one wide">Plaats</th>
                    <th data-slug="available_persons" class="one wide">Vrij</th>
                    <th data-slug="persons" class="one wide">Bezet</th>
                    <th data-slug="company_name" class="two wide">Bedrijf</th>
                    <th data-slug="is_locked" class="one wide">Status</th>
            	</tr>
            </thead>
            <tbody class="list search">
                @if(count($data) >= 1)
                	@include('admin/reservations/list')
                @else
                    <tr>
                        <td colspan="2"><div class="ui error message">Er is geen data gevonden.</div></td>
                    </tr>
            	@endif
            </tbody>
   		</table>

        @include('admin.template.limit')
	<?php echo Form::close(); ?>

    {!! with(new \App\Presenter\Pagination($data->appends($paginationQueryString)))->render() !!}

</div>
<div class="clear"></div>
@stop
