@extends('template.theme')

@section('scripts')
@include('admin.template.remove_alert')
@stop

@section('content')
<div class="content">
    @include('admin.template.breadcrumb')
    <div class="buttonToolbar">  
        <div class="ui grid">
            <div class="left floated sixteen wide mobile nine wide computer column">
                <a href="{{ url('admin/'.$slugController.'/create') }}" class="ui icon blue button"><i class="plus icon"></i> Nieuw</a>

                <button id="removeButton" type="submit" name="action" value="remove" class="ui disabled icon grey button">
                    <i class="trash icon"></i> Verwijderen
                </button>
            </div>

            <div class="right floated sixteen wide mobile six wide computer column">
             <div class="ui grid">
                 <div class="three column row">
                    <div class="column">
                        @include('admin.template.limit')
                    </div>
                    <div class="column">
                        @include('admin.template.type')
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
<table class="ui sortable very basic collapsing celled table list" style="width: 100%;">
    <thead>
     <tr>
      <th class="disabled one wide">
       <div class="ui master checkbox">
        <input type="checkbox">
        <label></label>
    </div>
</th>
<th data-slug="slug">Slug</th>
<th data-slug="name">Naam</th>
<th data-slug="name">Sort</th>
<th></th>
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