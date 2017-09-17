@extends('template.theme')

@section('scripts')
    @include('admin.template.remove_alert')
@stop

@section('content')
<div class="content">
    @include('admin.template.breadcrumb')
    <div class="buttonToolbar">  
        <div class="ui grid">
            <div class="sixteen wide mobile five wide computer column">
                <a href="{{ url('admin/'.$slugController.'/create') }}" class="ui icon blue button"><i class="plus icon"></i> Nieuw</a>
                        
                <button id="removeButton" type="submit" name="action" value="remove" class="ui disabled icon grey button">
                    <i class="trash icon"></i> Verwijderen
                </button>
                
            </div>
            <div class="sixteen wide mobile eleven wide computer column">
                <div class="ui grid">
                    <div class="four column row">
                        <div class="column">
                            <div class="ui normal icon search selection fluid dropdown">
                                <i class="filter icon"></i>

                                <div class="text">Status</div>

                                <i class="dropdown icon"></i>

                                <div class="menu">
                                    <a class="item" href="{{ url('admin/giftcards?status=1') }}">Geactiveerd</a>
                                    <a class="item" href="{{ url('admin/giftcards?status=0') }}">Niet geactiveerd</a>
                                </div>
                            </div>
                        </div>

                        <div class="column">
                            @if ($userAdmin)
                            <div class="ui normal icon search selection fluid dropdown">
                                <i class="filter icon"></i>

                                <div class="text">Bedrijf</div>

                                <i class="dropdown icon"></i>
                                <div class="menu">
                                    @if (count($companies) >= 1)
                                        @foreach ($companies as $company)
                                        <a class="item" href="{{ url('admin/giftcards?company=').$company->slug  }}">{{ $company->name }}</a>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                            @endif
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

     @if ($userAdmin)
        <a href="{{ url('admin/giftcards/export') }}" class="ui icon button"><i class="download icon"></i> Exporteer</a>
        <a href="{{ url('admin/giftcards/import') }}" class="ui icon button"><i class="plus icon"></i> Importeer</a>
    @endif
    <?php echo Form::open(array('id' => 'formList', 'url' => 'admin/'.$slugController.'/delete', 'method' => 'post')) ?>
        <table class="ui sortable very basic collapsing celled table list" style="width: 100%;">
            <thead>
            	<tr>
            		<th data-slug="disabled" class="disabled one wide">
            			<div class="ui master checkbox">
    					  	<input type="checkbox">
    					  	<label></label>
    					</div>
    				</th>
                    <th data-slug="is_active">Status</th>
                    <th data-slug="code">Code</th>
                    <th data-slug="amount">Saldo</th>
                    <th data-slug="max_usage">Maximale gebruik</th>
                    <th data-slug="used">Gebruikt</th>
                    <th data-slug="companyName">Bedrijf</th>
                    <th data-slug="created_at">Geactiveerd op</th>
                    <th data-slug="disabled" class="disabled"></th>
            	</tr>
            </thead>
            <tbody class="list search">
                @if(count($data) >= 1)
                	@include('admin/'.$slugController.'.list')
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