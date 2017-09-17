@extends('template.theme')

@section('scripts')
    @include('admin.template.remove_alert')
@stop

@section('content')
<div class="content">
    @include('admin.template.breadcrumb')

    <div class="buttonToolbar">  
        <div class="ui grid container">
            <div class="sixteen wide mobile twelve wide computer column">
                <a href="{{ url('admin/mailtemplates/create'.(isset($companyParam) ? '/'.$companyParam : '')).(Request::has('type') ? '?type='.Request::input('type') : '') }}" class="ui icon blue button"><i class="plus icon"></i> Nieuw</a>

                <a href="{{ url('admin/mailtemplates'.(isset($companyParam) ? '/'.$companyParam : '').'?type=call') }}" class="ui icon blue button"><i class="phone icon"></i> Bellen</a>
                <a href="{{ url('admin/mailtemplates'.(isset($companyParam) ? '/'.$companyParam : '').'?type=mail') }}" class="ui icon blue button"><i class="envelope icon"></i> Mail</a>
                <a href="{{ url('admin/mailtemplates'.(isset($companyParam) ? '/'.$companyParam : '').'?type=push') }}" class="ui icon blue button"><i class="announcement icon"></i> Push</a>
                <a href="{{ url('admin/mailtemplates'.(isset($companyParam) ? '/'.$companyParam : '').'?type=message') }}" class="ui icon blue button"><i class="comment icon"></i> SMS</a>
                <a href="{{ url('admin/mailtemplates'.(isset($companyParam) ? '/'.$companyParam : '').'?type=notifications') }}" class="ui icon blue button"><i class="announcement icon"></i> Notificaties</a>
    		  <a href="{{ url('admin/mailtemplates/createPackage'.(isset($companyParam) ? '/'.$companyParam : '')).(Request::has('type') ? '?type='.Request::input('type') : '') }}" class="ui icon blue button">
				<i class="plus icon"></i> Create Package</a>

               
               
            </div>

          
        </div>
    </div>

   
    
   
   

    

    

    
  


    <?php echo Form::open(array('id' => 'formList', 'url' => 'admin/mailtemplates/delete/'.$companyParam, 'method' => 'post')) ?>
        <table class="ui very basic collapsing  sortable celled table list" style="width: 100%;">
            <thead>
            	<tr>
            		<th data-slug="disabled" class="disabled one wide">
                        <div class="ui master checkbox">
                            <input type="checkbox">
                        </div>
    				</th>
                    <th data-slug="subject">package Name</th>
                    <th data-slug="type">Type</th>
                    <th data-slug="name">Quantity</th>
                    
                    <th data-slug="price">price</th>
                    <th data-slug="tot_price">total price</th>
                    <th data-slug="is_active">Actief</th>
     
                    <th data-slug="disabled"></th>
                    <th data-slug="disabled"></th>
            	</tr>
            </thead>

            <tbody class="list search">
                @if(count($data) >= 1)
                	@include('admin/mailtemplates/list')
                @else
                    <tr>
                        <td colspan="2">
                            <div class="ui error message">Er is geen data gevonden.</div>
                        </td>
                    </tr>
            	@endif
            </tbody>

   		</table>
    <?php echo Form::close(); ?>


    {!! with(new \App\Presenter\Pagination($data->appends($paginationQueryString)))->render() !!}
    <div class="clear"></div>
    
    <div class="container"><br />
    @include('admin.template.limit')
    </div>
 
</div>
<div class="clear"></div>
@stop