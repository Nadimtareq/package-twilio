@extends('template.theme')

@section('scripts')
    @include('admin.template.remove_alert')
@stop

@section('content')
<div class="content">
    @include('admin.template.breadcrumb')

<form action="save" method="POST">

<table class="ui very basic collapsing  sortable celled table list" style="width: 100%;">
            <thead>
                <tr>
                    
                    <th data-slug="subject">Package Name</th>
                    <th data-slug="type">Package Cost</th>
                    <th data-slug="quantity">SMS</th>
                    
                    <th data-slug="price">Call</th>
                    <th data-slug="tot_price">Mail</th>
                    <th data-slug="tot_price">Push</th>
                    
                    <th data-slug="is_active">Actief</th>
     
                   
                </tr>
            </thead>

            <tbody class="list search">
                <?php
                $link = mysqli_connect("localhost", "marvelso_martin", "capitive@305@pune", "marvelso_livepass");
        $sql = "select * from packages ";
        $result = mysqli_query($link, $sql) or die(mysqli_error());
        if (mysqli_num_rows($result) > 0) {
         while ($row = mysqli_fetch_array($result)) {
        ?>
                <tr>
   
    <td><?php echo $row['package']; ?></td>
    <td><?php echo $row['cost']; ?></td>
    <td><?php echo $row['sms_qty']; ?></td>
    
    <td>
       <?php echo $row['call_qty']; ?>
    </td>
    <td><?php echo $row['mail_qty']; ?></td>
    <td><?php echo $row['push_qty']; ?></td>
   
   
</tr>
<?php
}
}
?>
            </tbody>

        </table>
</form>

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
    @include('admin.template.limit')
    </div>
 
</div>
<div class="clear"></div>
@stop