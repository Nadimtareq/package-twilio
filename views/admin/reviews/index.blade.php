@extends('template.theme')

@section('scripts')
    @include('admin.template.remove_alert')
@stop

@section('content')
<div class="content">
    @include('admin.template.breadcrumb')
    <div class="buttonToolbar">  
        <div class="ui grid">
            <div class="sixteen wide mobile six wide computer column">
                <button id="openButton" type="submit" value="accept" data-text="Weet u zeker dat u deze recensie wil plaatsen?" class="ui icon green button">
                    <i class="check  icon"></i>
                </button>

                <button id="closeButton" type="submit" value="decline" class="ui icon red button">
                    <i class="remove icon"></i>
                </button>
            </div>

            <div class="sixteen wide mobile ten wide computer column">
                 <div class="ui grid">
                    <div class="three column row">
                        <div class="column">
                            @if ($userAdmin)
                            <div class="ui normal icon search selection fluid dropdown">
                                <i class="filter icon"></i>

                                <div class="text">Bedrijf</div>

                                <i class="dropdown icon"></i>
                                <div class="menu">
                                    @if (count($companies) >= 1)
                                        @foreach ($companies as $company)
                                        <a class="item" href="{{ url('admin/reviews/'.$company->slug)  }}">{{ $company->name }}</a>
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
    <?php echo Form::open(array('id' => 'formList', 'url' => 'admin/reviews/update'.(trim(Request::segment(3)) != '' ? '/'.Request::segment(3) : ''), 'method' => 'post')) ?>
        <input type="hidden" id="actionMan" name="action" />
        
        <table class="ui sortable very basic collapsing sortable celled table list" style="width: 100%;">
            <thead>
            	<tr>
            		<th class="disabled one wide">
            			<div class="ui master checkbox">
    					  	<input type="checkbox">
    					  	<label></label>
    					</div>
    				</th>
                    <th data-slug="name">Door</th>
                    <th data-slug="food" clas="one wide">Eten</th>
                    <th data-slug="service" clas="one wide">Service</th>
                    <th data-slug="decor" clas="one wide">Decor</th>
                    <th data-slug="content" clas="four wide">Recensie</th>
                    <th data-slug="company" clas="one wide">Bedrijf</th>
                    <th data-slug="is_approved" clas="one wide">Zichtbaar</th>
                    <th data-slug="created_at" clas="one wide">Toegevoegd op</th>
                    <th data-slug="disabled" clas="one wide"></th>
            	</tr>
            </thead>
            <tbody class="list search">
                @if(count($data) >= 1)
                	@include('admin/reviews/list')
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