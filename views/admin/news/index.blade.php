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
                <a href="{{ url('admin/'.$slugController.'/create'.(isset($companyParam) ? '/'.$companyParam : '')) }}" class="ui icon blue button">
                    <i class="plus icon"></i> Nieuw
                </a>

                @if (Sentinel::inRole('admin'))
                <button id="removeButton" type="submit" name="action" value="remove" class="ui disabled icon grey button">
                    <i class="trash icon"></i> Verwijderen
                </button>
                @endif

            </div>

            <div class="right floated sixteen wide mobile six wide computer column">
                 <div class="ui grid">
                    <div class="two column row">
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

    <?php echo Form::open(array('id' => 'formList', 'url' => 'admin/news/delete', 'method' => 'post')) ?>
    <table class="ui very basic collapsing sortable celled table list" style="width: 100%;">
        <thead>
            <tr>
            	<th data-slug="disabled" class="disabled one wide">
            		<div class="ui master checkbox">
    					<input type="checkbox">
    					<label></label>
    				</div>
    			</th>
                <th data-slug="title" clas="six wide">Naam</th>
                <th data-slug="created_at" class="three wide">Aangemaakt op</th>
                <th data-slug="updated_at" class="three wide">Gewijzigd op</th>
                <th data-slug="company" class="three wide">Bedrijf</th>
                <th data-slug="is_published" class="one wide">Goedgekeurd</th>
                <th data-slug="disabled" class="disabled"></th>
            </tr>
        </thead>
        <tbody class="list search">
            @if(count($data) >= 1)
                @include('admin/news/.list')
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