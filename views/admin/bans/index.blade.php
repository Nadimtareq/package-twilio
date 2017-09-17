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
                <a href="{{ url('admin/'.$slugController.'/create') }}" class="ui icon blue button"><i class="plus icon"></i> Nieuw</a>
                        
                <button id="removeButton" type="submit" name="action" value="remove" class="ui disabled icon grey button">
                    <i class="trash icon"></i> Verwijderen
                </button>
            </div>

            <div class="sixteen wide mobile twelve wide computer column">
                 <div class="ui grid">
                    <div class="two column row">

                        <div class="sixteen wide mobile three wide computer column">
                            @include('admin.template.limit')
                        </div>
<!-- 
                        <div class="sixteen wide mobile four wide computer column">
                            @include('admin.template.search.form')
                        </div> -->
                    </div>
                </div>
            </div>
        </div>
    </div><br />

    <?php echo Form::open(array('id' => 'formList', 'url' => 'admin/'.$slugController.'/delete', 'method' => 'post')) ?>
    	<table class="ui very basic sortable collapsing celled table list" style="width: 100%;">
            <thead>
            	<tr>
            		<th data-slug="disabled">
            			<div class="ui master checkbox">
    					  	<input type="checkbox">
    					  	<label></label>
    					</div>
    				</th>
                    <th data-slug="user_id" class="three wide">Gebruiker</th>
                    <th data-slug="reason" class="three wide">Einddatum</th>
                    <th data-slug="expired_date" class="four wide">Reden</th>
                    <th data-slug="disabled"></th>
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
                            {{ $result->name }}
                        </td>
                        <td>{{ date('d-m-Y', strtotime($result->expired_date)) }}</td>
                        <td>{{ $result->reason }}</td>
                		<td>
                            <a href="{{ url('admin/'.$slugController.'/update/'.$result->id) }}" class="ui icon tiny button">
                                <i class="pencil icon"></i>
                            </a>
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