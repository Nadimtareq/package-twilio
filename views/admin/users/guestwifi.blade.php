@extends('template.theme')


@section('scripts')
@include('admin.template.remove_alert')
@stop

@section('content')
<div class="content">
    @include('admin.template.breadcrumb')

    <div class="buttonToolbar">  
        <div class="ui grid">
            <div class="row">                
                <div class="sixteen wide mobile four wide computer column">
                    <a href="{{ url('admin/'.$slugController.'/create') }}" class="ui icon blue button"><i class="plus icon"></i> Nieuw</a>

                    <button id="removeButton" type="submit" name="action" value="remove" class="ui disabled icon grey button">
                        <i class="trash icon"></i> Verwijderen
                    </button>
                </div>

                
            </div>            
            <div class="row">                
                
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
			<div class="three column row">
                            <div class="sixteen wide mobile four wide computer column">
                                <div class="ui normal  search selection fluid dropdown"> <!-- icon class deleted-->
                                    <input type="hidden" name="companiesId">
                                    <i class="filter icon"></i>

                                    <span class="text">Bedrijf</span>

                                    <i class="dropdown icon"></i>

                                    <div class="menu">
                                        @foreach ($companies as $company)
                                        <a class="item" href="{{ url('admin/users/guestwifi?company='.$company->slug) }}" data-value="{{ $company->id }}">
                                            {{ $company->name }}
                                        </a>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
							
                            <div class="sixteen wide mobile four wide computer column">
                                <?php echo Form::select('city', (isset($preference[9]) ? $preference[9] : array()), Request::input('city'), array('id' => 'cityRedirect', 'class' => 'ui normal search fluid dropdown')); ?>
                            </div>
							</div>
        </div>
    </div><br />

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
		<th data-slug="email">E-mailadres</th>
        <th data-slug="phone">Telefoon</th>  
		<th data-slug="city">Stad</th>
		<th data-slug="company">Restaurant</th>
        <th data-slug="created_at">Gemaakt op</th>
        <th data-slug="updated_at">Bijgewerkt op</th>
       
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
               <td>{{ $result->email }}</td>
                <td>
                    <p style="display:none;font-size:12px;" class="less-phone" onmouseout="$(this).parent().find('span').show();$(this).hide();">
                        {{ $result->phone }}</p>
                    <span style="font-size:12px;" class="full-phone" onmouseover="$(this).parent().find('p').show();$(this).hide();">
                        {{ substr($result->phone,0,10) }}
                    </span>
                </td>
               <td>
                    @if(is_array(json_decode($result->regio)) >= 1)
                    @foreach(json_decode($result->regio) as $city)
                    @if(isset($regio[$city]))
                    {{ $regio[$city] }}
                    @endif
                    @endforeach
                    @else
                    Geen stad opgegeven
                    @endif
                </td>
               <td>{{ $result->companyName }}</td>
                <td>{{ date('d-m-Y H:i', strtotime($result->created_at)) }}</td>
                <td>{{ date('d-m-Y H:i', strtotime($result->updated_at)) }}</td>
               
                
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

    {!! with(new \App\Presenter\Pagination($data->appends($paginationQueryString)))->render() !!}
</div>
<div class="clear"></div>
@stop