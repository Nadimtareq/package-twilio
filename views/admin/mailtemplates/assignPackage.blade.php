
@extends('template.theme')

@section('scripts')
    @include('admin.template.remove_alert')
@stop

@section('content')
<div class="content">
    @include('admin.template.breadcrumb')
<div class="container">
    
    <form action="" method="GET">
    <div class="row">
    
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="col-md-12">
            <div class="col-md-6">

                Select Company <select>
  <option value="volvo">Volvo</option>
  <option value="saab">Saab</option>
  <option value="opel">Opel</option>
  <option value="audi">Audi</option>
</select>
<br>
            </div>
            <div class="col-md-6">
                Select Package <input type="text" name="cost" value=""><br>
            </div>
        </div>
        
         
        <input type="submit" value="Submit" name="submit">
    </div>
    </form>
</div>
<!--

        <table class="ui very basic collapsing  sortable celled table list" style="width: 100%;">
            <thead>
            	<tr>
            		<th data-slug="disabled" class="disabled one wide">
                        
    				</th>
                    
                    <th data-slug="type">Type</th>
		    <th data-slug="quantity">Quantity</th>                   
                    <th data-slug="price">price</th>
                    <th data-slug="total">total</th>
                    
     
                    <th data-slug="disabled"></th>
                    <th data-slug="disabled"></th>
            	</tr>
            </thead>


		<tbody class="list search">
               
                    <tr>
                        <td>
                            
                        </td>

			<td>
                            Call
                        </td>

			<td>
                            20
                        </td>

                        <td>
                            2
                        </td>

                          <td>
                            40
                        </td>
                    </tr>



				<tr>
                        <td>
                            
                        </td>

			<td>
                            Push
                        </td>

			<td>
                            30
                        </td>

                        <td>
                            2
                        </td>

                          <td>
                            60
                        </td>

                    </tr>
            	
            	</tbody> -->

            
   		</table>
    <?php echo Form::close(); ?>


    
    <div class="clear"></div>
    <div class="container"><br />
    <input type="submit">
    </div>
    <div class="container"><br />
    @include('admin.template.limit')
    </div>
 
</div>
<div class="clear"></div>
@stop