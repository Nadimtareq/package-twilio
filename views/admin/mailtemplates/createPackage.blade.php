
@extends('template.theme')

@section('scripts')
    @include('admin.template.remove_alert')
@stop

@section('content')
<div class="content">
    @include('admin.template.breadcrumb')
<div class="container">
    <?php
    if(isset($_REQUEST['submit']))
    {   
        $name = $_REQUEST['package'];
        $cost = $_REQUEST['cost'];
        $sms_qty = $_REQUEST['sms_qty'];
        $call_qty = $_REQUEST['call_qty'];
        $mail_qty = $_REQUEST['mail_qty'];
        $push_qty = $_REQUEST['push_qty'];
        
        $link = mysqli_connect("localhost", "marvelso_martin", "capitive@305@pune", "marvelso_livepass");
       echo $SQL = "INSERT INTO packages (package,cost,sms_qty,call_qty,mail_qty,push_qty) VALUES ('$name',$cost,$sms_qty,$call_qty,$mail_qty,$push_qty)";

        $result = mysqli_query($link, $SQL);

       header("location:packageshow");  
    }

    else
     {
        
     }
     
    ?>
    <form action="" method="GET">
    <div class="row">
    
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="col-md-12">
            <div class="col-md-6">
                Enter Package Name<input type="text" name="package" value=""><br>
            </div>
            <div class="col-md-6">
                Package Cost <input type="number" name="cost" value=""><br>
            </div>
        </div>
        <div class="col-md-12">
            <div class="col-md-6">
                SMS Quantity<input type="number" name="sms_qty" value=""><br>
            </div>
            <div class="col-md-6">
                Call Quantity <input type="number" name="call_qty" value=""><br>
            </div>
        </div>
        <div class="col-md-12">
            <div class="col-md-6">
                Mail Quantity<input type="number" name="mail_qty" value=""><br>
            </div>
            <div class="col-md-6">
                Push Quantity <input type="number" name="push_qty" value=""><br>
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
   <!-- <input type="submit">-->
    </div>
    <div class="container"><br />
    @include('admin.template.limit')
    </div>
 
</div>
<div class="clear"></div>
@stop