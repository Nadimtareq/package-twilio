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
                    <a href="{{ url('admin/'.$slugController.'/list/create') }}" class="ui icon blue button"><i class="plus icon"></i> Nieuw</a>

                    <button id="removeButton" type="submit" name="action" value="remove" class="ui disabled icon grey button">
                        <i class="trash icon"></i> Verwijderen
                    </button>
                </div>

                <div class="sixteen wide mobile twelve wide computer column">
                    <div class="ui grid">
					
                    </div>
                </div>
            </div>            
			<div class="row">  
				<div class="sixteen wide mobile six wide computer column">
                    <div class="ui grid">
					<input type="text" id="ext_search_value" value="">
                      
                    </div>
                </div>
				<div class="sixteen wide mobile six wide computer column">
				 <a href="#" class="ui blue button extension_search">Search</a>
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
        <th data-slug="email_extension">Uitbreiding</th>
        <th data-slug="total_email">Total Emails</th>
      
        <th data-slug="id1">Actie</th>
      
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
               
               
               
                
                <td>{{ $result->email_extension }}</td>               
                <td>{{ $result->total_email }}</td>               
                
               
                <td>
				@if($result->id1==1)
				
				<a href="#" class="ui red button extension_update" data-extension="{{ $result->id }}" data-status="0"> in staat stellen</a>
				@else
					<a href="#" class="ui green button extension_update" data-extension="{{ $result->id }}" data-status="1">onbruikbaar maken</a>
				@endif
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
    
</div>
<div class="clear"></div>
@stop