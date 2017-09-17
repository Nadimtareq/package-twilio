@extends('template.theme')

@section('scripts')
    @include('admin.template.remove_alert')
@stop

@section('content')
<div class="content">
    @include('admin.template.breadcrumb')

    <div class="buttonToolbar">  
        <div class="ui grid container">
            <div class="sixteen wide mobile twelve wide computer column">
                <a href="{{ url('admin/mailtemplates/create'.(isset($companyParam) ? '/'.$companyParam : '')).(Request::has('type') ? '?type='.Request::input('type') : '') }}" class="ui icon blue button"><i class="plus icon"></i> Nieuw</a>

                <a href="{{ url('admin/mailtemplates'.(isset($companyParam) ? '/'.$companyParam : '').'?type=call') }}" class="ui icon blue button"><i class="phone icon"></i> Bellen</a>
                <a href="{{ url('admin/mailtemplates'.(isset($companyParam) ? '/'.$companyParam : '').'?type=mail') }}" class="ui icon blue button"><i class="envelope icon"></i> Mail</a>
                <a href="{{ url('admin/mailtemplates'.(isset($companyParam) ? '/'.$companyParam : '').'?type=push') }}" class="ui icon blue button"><i class="announcement icon"></i> Push</a>
                <a href="{{ url('admin/mailtemplates'.(isset($companyParam) ? '/'.$companyParam : '').'?type=message') }}" class="ui icon blue button"><i class="comment icon"></i> SMS</a>
                <a href="{{ url('admin/mailtemplates'.(isset($companyParam) ? '/'.$companyParam : '').'?type=notifications') }}" class="ui icon blue button"><i class="announcement icon"></i> Notificaties</a>
    		  <a href="{{ url('admin/mailtemplates/createPackage'.(isset($companyParam) ? '/'.$companyParam : '')).(Request::has('type') ? '?type='.Request::input('type') : '') }}" class="ui icon blue button">
				<i class="plus icon"></i> Create Package</a>

                @if ($userAdmin)  
                <button id="removeButton" type="submit" name="action" value="remove" class="ui disabled icon grey button">
                    <i class="trash icon"></i>
                </button>

                <a href="{{ url('admin/mailtemplates/settings') }}" class="ui icon button">
                    <i class="wrench icon"></i> 
                </a>
                @endif

                @include('admin.template.search.form')
            </div>

            <div class="three wide column">
                @if ($userAdmin)
                    <div class="ui normal icon search selection fluid dropdown">
                        <i class="filter icon"></i>

                        <div class="text">Bedrijf</div>

                        <i class="dropdown icon"></i>
                        
                        <div class="menu">
                            @if (count($companies) >= 1)
                                @foreach ($companies as $company)
                                <a class="item" href="{{ url('admin/mailtemplates/'.$company->slug)  }}">{{ $company->name }}</a>
                                @endforeach
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @if (Request::input('type') == 'mail')
    <div class="ui icon info message">
        <i class="info icon"></i>

        <div class="content">
            <div class="header">
                OPGELET!
            </div>
            <p>
                Dit is het overzicht van uw mailverkeer, iedere mail kunt u aan/uit zetten en qua inhoud aanpassen.
            </p>
        </div>
    </div>
    @endif

    @if (Request::input('type') == 'call')
    <div class="ui icon info message">
        <i class="info icon"></i>

        <div class="content">
            <div class="header">
                OPGELET!
            </div>
            <p>
                Dit is het overzicht van uw belverkeer, iedere gesprek kunt u aan/uit zetten en qua inhoud aanpassen.
            </p>
        </div>
    </div>
    @endif

    @if (Request::input('type') == 'message')
    <div class="ui icon info message">
        <i class="info icon"></i>

        <div class="content">
            <div class="header">
                OPGELET!
            </div>
            <p>
                Dit is het overzicht van uw smsverkeer, iedere bericht kunt u aan/uit zetten en qua inhoud aanpassen.
            </p>
        </div>
    </div>
    @endif

    @if (Request::input('type') == 'push')
    <div class="ui icon info message">
        <i class="info icon"></i>

        <div class="content">
            <div class="header">
                OPGELET!
            </div>
            <p>
                Dit is het overzicht van uw push notificaties verkeer, iedere bericht kunt u aan/uit zetten en qua inhoud aanpassen.
            </p>
        </div>
    </div>
    @endif

    @if (Request::input('type') == 'notifications')
    <div class="ui icon info message">
        <i class="info icon"></i>

        <div class="content">
            <div class="header">
                OPGELET!
            </div>
            <p>
                Dit is het overzicht van uw notificaties verkeer, iedere bericht kunt u aan/uit zetten en qua inhoud aanpassen.
            </p>
        </div>
    </div>
    @endif

    @if (isset($companyParam) && Request::input('type') == 'call')
    <?php echo Form::open(array('class' => 'ui form', 'method' => 'post')) ?>
    <div class="three fields">
        <div class="field">
             <label>Stem</label>
            <?php 
            echo Form::select(
                'company', 
                array(
                    'Man',
                    'Vrouw'
                ), 
                '', 
                array(
                    'id' => 'companySelectAppointment', 
                    'class' => 'ui normal search dropdown'
                )
            );  
            ?>
        </div>       


        <div class="field">
             <label>Taal</label>
            <?php 
            echo Form::select(
                'company', 
                array(
                    'Nederlands',
                    'Engels (Groot-Brittannië)',
                    'Engels (Amerika)',
                    'Engels (Australie)',
                    'Spaans',
                    'Spaans (Mexico)',
                    'Spaans (Amerika)',
                    'Frans',
                    'Frans (Canada)',
                    'Russisch',
                    'Duits',
                    'Italiaans',
                ), 
                '', 
                array(
                    'id' => 'companySelectAppointment', 
                    'class' => 'ui normal search dropdown'
                )
            );  
            ?>
        </div>

        <div class="field">
            <label>Beluisteren</label>
            <button id="exampleButton" class="ui icon button">
                <i class="sound icon"></i> Voorbeeld
            </button>
        </div>

 
    </div>
    <?php echo Form::close(); ?>
    @endif

  


    <?php echo Form::open(array('id' => 'formList', 'url' => 'admin/mailtemplates/delete/'.$companyParam, 'method' => 'post')) ?>
        <table class="ui very basic collapsing  sortable celled table list" style="width: 100%;">
            <thead>
            	<tr>
            		<th data-slug="disabled" class="disabled one wide">
                        <div class="ui master checkbox">
                            <input type="checkbox">
                        </div>
    				</th>
                    <th data-slug="subject">Onderwerp</th>
                    <th data-slug="type">Soort</th>
                    <th data-slug="name">Bedrijf</th>
                    <th data-slug="count">count</th>
                    <th data-slug="price">price</th>
                    <th data-slug="tot_price">total price</th>
                    <th data-slug="is_active">Actief</th>
     
                    <th data-slug="disabled"></th>
                    <th data-slug="disabled"></th>
            	</tr>
            </thead>

            <tbody class="list search">
                @if(count($data) >= 1)
                	@include('admin/mailtemplates/list')
                @else
                    <tr>
                        <td colspan="2">
                            <div class="ui error message">Er is geen data gevonden.</div>
                        </td>
                    </tr>
            	@endif
            </tbody>
            <thead>
                <tr>
                    <th data-slug="disabled" class="disabled one wide">
                        <div class="ui master checkbox">
                            <input type="checkbox">
                        </div>
                    </th>
                    <th data-slug="subject">Onderwerp</th>
                    <th data-slug="type">Soort</th>
                    <th data-slug="name">Bedrijf</th>
                     <th data-slug="name">count</th>
                      <th data-slug="name">Price</th>
                       <th data-slug="name">Total Price</th>
                    <th data-slug="is_active">Actief</th>
                    <th data-slug="disabled"></th>
                </tr>
            </thead>

            <tbody class="list search">
                @if(count($data) >= 1)
                    @include('admin/mailtemplates/list')
                @else
                    <tr>
                        <td colspan="2">
                            <div class="ui error message">Er is geen data gevonden.</div>
                        </td>
                    </tr>
                @endif
            </tbody>
   		</table>
    <?php echo Form::close(); ?>


    {!! with(new \App\Presenter\Pagination($data->appends($paginationQueryString)))->render() !!}
    <div class="clear"></div>
    
    <div class="container"><br />
    @include('admin.template.limit')
    </div>
 
</div>
<div class="clear"></div>
@stop