@extends('template.theme')

@section('scripts')
    @include('admin.template.remove_alert')
@stop

@section('content')
<div class="content">
    @include('admin.template.breadcrumb')
    <div class="buttonToolbar">  
        <div class="ui grid">
            <div class="sixteen wide mobile four wide computer column">
                <a href="{{ url('admin/'.$slugController.'/create'.(isset($companyParam) ? '/'.$companyParam : '')) }}" class="ui icon blue button">
                    <i class="plus icon"></i> Nieuw
                </a>
                    
                <button id="removeButton" type="submit" name="action" value="remove" class="ui disabled icon grey button">
                    <i class="trash icon"></i> Verwijderen
                </button>
            </div>

            <div class="sixteen wide mobile eleven wide computer column">
                 <div class="ui grid">
                    <div class="four column row">
                        <div class="column">
                            @if ($userAdmin)
                            <div class="ui normal icon search selection fluid dropdown">
                                <i class="filter icon"></i>

                                <div class="text">Bedrijf</div>

                                <i class="dropdown icon"></i>
                                <div class="menu">
                                    @if (count($companies) >= 1)
                                        @foreach ($companies as $company)
                                        <a class="item" href="{{ url('admin/services/'.$company->slug)  }}">{{ $company->name }}</a>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                            @endif
                        </div>

                        <div class="column">
                            <div class="ui normal selection dropdown">
                                <input type="hidden" name="filters" value="{{ Request::input('q') }}">
                                <i class="filter icon"></i>
                              
                                <span class="text">Diensten</span>
                                <i class="dropdown icon"></i>

                                <div class="menu">
                                    @foreach($dropdownData as $dataFetch)
                                        <a class="item" 
                                            href="{{ url('admin/services?q='.$dataFetch->name) }}" 
                                            data-value="{{ $dataFetch->id }}">
                                            {{ $dataFetch->name }}
                                        </a>
                                    @endforeach
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
    </div>

    <?php echo Form::open(array('id' => 'formList', 'url' => 'admin/services/delete', 'method' => 'post')) ?>
    <table class="ui very basic collapsing sortable celled table list" style="width: 100%;">
        <thead>
            <tr>
            	<th data-slug="disabled" class="disabled one wide">
            		<div class="ui master checkbox">
    					<input type="checkbox">
    					<label></label>
    				</div>
    			</th>
                <th data-slug="name" clas="six wide">Naam</th>
                <th data-slug="start_date" clas="five wide">Start datum</th>
                <th data-slug="end_date" clas="five wide">Eind datum</th>
                <th data-slug="price" clas="six wide">Prijs</th>
                <th data-slug="tax" clas="six wide">Percentage</th>
                <th data-slug="company" class="three wide">Bedrijf</th>
                <th data-slug="disabled" class="disabled"></th>
            </tr>
        </thead>
        <tbody class="list search">
            @if(count($data) >= 1)
                @include('admin.services.list')
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