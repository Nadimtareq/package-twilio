@extends('template.theme')

@section('scripts')
    @include('admin.template.remove_alert')
@stop

@section('content')
<script type="text/javascript">
    var activateAjax = 'callcenteradmin';
</script>

<div class="content">
    @include('admin.template.breadcrumb')
    <input type="hidden" name="redirectUrl" value="{{ url('admin/companies/callcenter') }}">

    <div class="buttonToolbar">  
        <div class="ui grid">
            <div class="left floated sixteen wide mobile nine wide computer column">
                <a href="{{ url('admin/companies/callcenter/create') }}" class="ui icon blue button"><i class="plus icon"></i> Nieuw</a>
                            
                <button id="removeButton" type="submit" name="action" value="remove" class="ui disabled icon grey button">
                    <i class="trash icon"></i> 
                </button>

                @if (!Request::has('favorite'))
                <button id="favoriteButton" type="submit" name="action" value="favorite" class="ui icon grey button">
                    <i class="save icon"></i> Favoriet
                </button>
                @else
                <button id="favoriteButton" type="submit" name="action" value="unfavorite" class="ui icon grey button">
                    <i class="save icon"></i> Onfavoriet
                </button>
                @endif

                <button id="winButton" type="submit" name="action" value="win" class="ui icon green button">
                    <i class="smile icon"></i> Win
                </button>

                <button id="loseButton" type="submit" name="action" value="lose" class="ui icon red button">
                    <i class="frown icon"></i> Lose
                </button>
            </div>

            <div class="right floated sixteen wide mobile seven wide computer column">
                 <div class="ui grid">
                    <div class="three column row">
                        <div class="column">
                            <div class="ui icon fluid input">
                                <input type="text" class="ajax-datepicker" placeholder="Datum" />
                                 <i class="calendar icon"></i>
                            </div>
                        </div>
                        
                        <div class="column">
                            @if ($userAdmin)
                            <div class="ui normal selection fluid dropdown item">
                                <input type="hidden" name="saldo" value="{{ Request::get('saldo') }}">
                                <div class="text">
                                     Beller
                                </div>
                                <i class="dropdown icon"></i>

                                <div class="menu">
                                     <a class="item" href="{{ url('admin/'.$slugController) }}">Alles</a>

                                    @foreach ($callcenterUsers as $user)
                                    <a class="item" data-value="1" href="{{ url('admin/'.$slugController.'?'.http_build_query(array_add($queryString, 'caller_id', 1))) }}">{{ $user->name }}</a>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        </div>

                        <div class="column">
                            @include('admin.template.search.form')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="ui buttons">
        <a class="ui {{ Request::has('score') && Request::get('score') == 'open' ? 'active' : '' }} button" href="{{ url('admin/companies/callcenter?score=open') }}">
        Open
        </a>

        <a class="ui {{ Request::has('score') && Request::get('score') == 'won' ? 'active' : '' }} button" href="{{ url('admin/companies/callcenter?score=won') }}">
        Won
        </a>

        <a class="ui {{ Request::has('score') && Request::get('score') == 'lose' ? 'active' : '' }} button" href="{{ url('admin/companies/callcenter?score=lose') }}">
        Lose
        </a>
    </div>

    <div class="ui buttons">
        <a class="ui button" href="{{ url('admin/companies/callcenter?favorite=1') }}">
        Favoriet 
        </a>
    </div>

    @if ($userAdmin)
        <a href="{{ url('admin/companies/callcenter/export') }}" class="ui icon button"><i class="download icon"></i> Exporteer</a>
        <a href="{{ url('admin/companies/callcenter/import') }}" class="ui icon button"><i class="plus icon"></i> Importeer</a>
    @endif

   <?php echo Form::open(array('id' => 'formList', 'url' => 'admin/companies/callcenter/delete', 'method' => 'post')) ?>
    	<input type="hidden" id="actionMan" name="action">
        <table class="ui very basic sortable collapsing celled table list" style="width: 100%;">
            <thead>
            	<tr>
            		<th data-slug="disabled" class="disabled one wide">
            			<div class="ui master checkbox">
    					  	<input type="checkbox">
    					  	<label></label>
    					</div>
    				</th>
                    <th data-slug="name" class="two wide">Naam</th>
                    <th data-slug="address" class="two wide">Adres</th>
                    <th data-slug="city">Stad</th>
                    <th data-slug="contact_name">Contact</th>
                    <th data-slug="called_at" class="three wide">Gebeld op</th>
                    <th data-slug="callback_at" class="two wide">Terug bellen op</th>
                    <th data-slug="score" class="two wide">Status</th>
                    <th data-slug="click_registration" class="two wide">Link geklikt</th>
                    <th data-slug="disabled" class="disabled six wide"></th>
            	</tr>
            </thead>
            <tbody class="search list">
                @if(count($data) >= 1)
                	@foreach($data as $result)

                    <?php $documentItems = $result->getMedia('documents'); ?>
                	<tr>
                		<td>
                			<div class="ui child checkbox">
        					  	<input type="checkbox" name="id[]" value="{{ $result->id }}">
        					  	<label></label>
        					</div>
        				</td>
                        <td>{{ $result->name }}</td>
                        <td>{{ $result->address }}</td>
                        <td>
                            <a href="{{ url('admin/companies/callcenter?city='.$result->city) }}">
                                {{ $result->city }}
                            </a>
                        </td>
                        <td>
                            <a href="{{ url('admin/'.$slugController.'/update/'.$result->id.'/'.$result->slug) }}">
                                {{ $result->contact_name }}
                            </a>
                        </td>
                        <td>{{ date('d-m-Y', strtotime($result->called_at)).' om '.date('H:i', strtotime($result->called_at)) }}</td>
                        <td>
                            @if ($result->callback_at != '0000-00-00 00:00:00')
                            {{ date('d-m-Y', strtotime($result->callback_at)).' om '.date('H:i', strtotime($result->callback_at)) }}
                            @else
                            <span class="ui small fluid label red">Nog niet terug gebeld</span>
                            @endif
                        </td>
                        <td>
                            @if ($result->score == 1)
                            <div style="color: green;"><i class="smile icon green"></i> <strong>+1</strong> Won</div>
                            @elseif ($result->score == 2)
                            <div style="color: red;"><i class="frown icon red"></i> <strong>+1</strong> Lose</div>
                            @else
                            <div style="color: blue;"><i class="meh icon blue"></i> <strong>+1</strong> Open</div>
                            @endif
                        </td>
                        <td>
                            @if ($result->click_registration == 1)
                                <i class="check mark green large center aligned icon"></i>
                            @else
                                <i class="remove red large icon"></i>
                            @endif
                        </td>
                		<td>
                            <div class="ui buttons">
                                <a href="{{ url('admin/'.$slugController.'/contract/'.$result->id.'/'.$result->slug) }}" target="_blank" class="ui icon tiny {{ (trim($result->signature_url) != '' ? 'red' : '') }}  button">
                                    <i class="file pdf icon"></i>
                                </a>

                                <a href="{{ url('admin/'.$slugController.'/update/'.$result->id.'/'.$result->slug.(Request::has('city') ? '?city='.Request::get('city')  : '')) }}" class="ui icon tiny button">
                                    <i class="phone icon"></i>
                                </a>
                                <a href="{{ url('admin/appointments/create/'.$result->slug) }}" class="ui icon  {{ (isset($result->companyId) ? 'green' : 'orange') }}  tiny button">
                                    <i class="calendar icon"></i>
                                </a>

                                @if ($result->caller_id == NULL)
                                <a href="{{ url('admin/'.$slugController.'/favorite/'.$result->slug) }}" class="ui icon blue tiny button">
                                    <i class="save icon"></i>
                                </a>
                                @elseif($result->caller_id != NULL && Request::has('favorite'))
                                <a href="{{ url('admin/'.$slugController.'/favorite/'.$result->slug) }}" class="ui icon red tiny button">
                                    <i class="save icon"></i>
                                </a>
                                @else
                                <span class="ui icon blue disabled tiny button">
                                    <i class="save icon"></i>
                                </span>
                                @endif
                            </div>
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

            {!! with(new \App\Presenter\Pagination($data->appends($paginationQueryString)))->render() !!}


    <div class="clear"></div><br />
    @include('admin.template.limit')

</div>
<div class="clear"></div>
@stop