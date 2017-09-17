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
                <a href="{{ url('admin/'.$slugController.'/create') }}" class="ui icon blue button"><i class="plus icon"></i> Nieuw</a>
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

    <?php echo Form::open(array('id' => 'formList', 'url' => 'admin/'.$slugController.'/delete', 'method' => 'post')) ?>
    	<table class="ui very basic collapsing sortable celled table list" style="width: 100%;">
            <thead>
            	<tr>
            		<th class="one wide">
            			<div class="ui master checkbox">
    					  	<input type="checkbox">
    					  	<label></label>
    					</div>
    				</th>
            		<th data-slug="name">Naam</th>
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
                        <td>{{ $result->name }}</td>
                		<td><a href="{{ url('admin/'.$slugController.'/update/'.$result->id) }}" class="ui label"><i class="pencil icon"></i> Bewerk</a></td>
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