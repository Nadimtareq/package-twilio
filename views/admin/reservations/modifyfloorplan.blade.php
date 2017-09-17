@extends('template.theme')
<meta name="csrf-token" content="{{ csrf_token() }}" />
<link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}" />
<link rel="stylesheet" href="{{ asset('css/font-awesome.css') }}" />
<link rel="stylesheet" href="{{ asset('css/dragndrop/owl-coursel.css') }}" />
<link rel="stylesheet" href="{{ asset('css/dragndrop/base.css?rand='.str_random(40)) }}">
<script src="{{ asset('js/dragndrop/jquery-git2.min.js') }}"></script>
<script src="{{ asset('js/dragndrop/jquery.event.drag-2.2.js') }}"></script>
<script src="{{ asset('js/dragndrop/jquery.event.drag.live-2.2.js') }}"></script>
<script src="{{ asset('js/dragndrop/jquery.event.drop-2.2.js') }}"></script>
<script src="{{ asset('js/dragndrop/jquery.event.drop.live-2.2.js') }}"></script>
<script src="{{ asset('js/dragndrop/excanvas.min.js') }}"></script>
<script src="{{ asset('js/dragndrop/watermark-polyfill.js') }}"></script>
<script src="{{ asset('js/dragndrop/main.js') }}"></script>
<script src="{{ asset('js/bootstrap.min.js') }}"></script>
<script src="{{ asset('js/dragndrop/owl.carousel.js') }}"></script>

@section('fixedMenu') <a href="{{ url('faq/8/reserveren') }}" class="ui black icon big launch right attached fixed button"> <i class="question mark icon"></i> <span class="text">Veelgestelde vragen</span> </a><br />
<script type="text/javascript">
//var base_img_url = <?php echo url('images/dragndrop/'); ?>;
$("#pageLoader").fadeOut('slow');
</script>
@stop
@section('content')
<?php
$_public = public_path();
$directory = $_public.'/images/dragndrop/bg';
?>
<div class="content">
  <div class="ui breadcrumb"> <a href="#" data-activates="slide-out" class="sidebar open">Menu</a> <i class="right chevron icon divider"></i> <a href="{{ url('admin/reservations'.(isset($companyParam) && $userCompany == TRUE ? '/clients/'.$company : '/clients')) }}" class="section">Reserveringen </a> <i class="right chevron icon divider"></i> @if(trim($date) != '')
    <div class="active section">{{ date('d-m-Y', strtotime($date)) }}</div>
    @else
    <div class="active section">Alle reserveringen</div>
    @endif </div>
  <div class="ui divider"></div>
  
  <br>
  <div class="col-md-4">&nbsp;</div>
  <div class="col-md-8" style="padding:0px;">
<div class="customNavigation"><a id="prev" class="btn prev"><span>Previous</span></a> <a id="next" class="btn next"><span>Next</span></a></div>
        <div id="owl-demo" class="owl-carousel">
              <?php
                //$directory = File::directories();
                $files = File::allFiles($directory);
                $count=0;
                foreach ($files as $file)
                {
                $_files[] = $_file =  str_replace(array($directory, '\\'), array("",""),$file);
                ?><div class="item <?php echo ($count==0)?"active":"" ?>">
                	<div>
                      <div class="imgDemo" style="padding:0px;width:100%;"><img src="<?php echo url('images/dragndrop/bg/'.$_file); ?>" class="img-responsive" id="imgDemo[<?php echo $count;?>]" style="max-height:110px;overflow:hidden;"></div>
                    </div>
					</div>
					<?php
                    $count++;
                }
              ?>
        </div>
    </div>
  
  <!--<div style="float:right;">
    <button onclick="nxt()" id="btnOne">next </button>
    <img src="<?php echo url('images/dragndrop/bg/gray-1.jpg'); ?>" id="imgDemo" alt="HTML5 Icon" width="128" height="128">
    <button onclick="prvs()" id="btnTwo"> previous</button>
  </div>-->
  <div class="col-md-12">
    <input type="hidden" id="totalDrop" value="0">
    <input type="hidden" name="redirectUrl" value="{{ url('admin/reservations/clients'.(isset($companyInfo->name) ? '/'.$companyInfo->id : '')) }}">
  <input type="hidden" name="company" id="company_id" value="{{ isset($companyInfo->id) ? $companyInfo->id : '' }}">
  <input type="hidden" name="date" value="{{ $date }}">
    <div id="lock"></div>
    <div id="container-table" class="col-md-3"></div>
    <div id="container-floor" class="col-md-9"></div>
    
  </div>
  <?php
	$arrName =  $arrTableId = $arrtable_number = $arrName_xposition = $arrName_yposition = $arrName_reservation_id=[null];
	$arrName_table = [null];
	$_cnt = 0;
  ?>
  @if(count($data) >= 1)
  @foreach($data as $data)
  <?php
  	if(isset($data->floor_plan_position) && $data->floor_plan_position!= ''){
		$_floor_plan = json_decode($data->floor_plan_position);
		
		$xcords = (isset($_floor_plan->offsetX) && $_floor_plan->offsetX!='')?$_floor_plan->offsetX:0;
		$ycords = isset($_floor_plan->offsetY)?$_floor_plan->offsetY:0;
		
		array_push( $arrName_xposition,  $xcords );
		array_push( $arrName_yposition,  $ycords );
	} else {
		array_push( $arrName_xposition,  0 );
		array_push( $arrName_yposition,  0 );
	}
	if(isset($data->reservation_id) && $data->reservation_id!= ''){
		array_push( $arrName_reservation_id,  $data->reservation_id );
	} else {
		array_push( $arrName_reservation_id,  0 );
	}
	
    array_push( $arrName,  "Table-". $data->table_number );
    array_push( $arrTableId,  $data->table_id );
	array_push( $arrName_table,  $data->seating );
    array_push( $arrtable_number, $data->table_number );
	$_cnt++;
    ?>
  @endforeach
  @else
  <div class="ui error message">Er is geen data gevonden.</div>
  @endif
  <?php

$totalData  = $_cnt ;//count($data) - 1;
$perGroup   = 2;
$division   = ceil($totalData/$perGroup);
$group      = [];
$style_left = [];
?>
  @for($x=0;$x <= $division;$x++)
  <?php

	$style_left[$x] = 0;
	$group[$x] = [];
	?>
  @if($x==0)
  <?php
	  $z             = 1;
	  $perGroupAfter = $perGroup;
	  ?>
  @else
  <?php
	  $z             = ($x*$perGroup)+1;
	  $perGroupAfter = ($x*$perGroup)+$perGroup;
	  ?>
  @endif
  @for($y=$z;$y<=$perGroupAfter;$y++)
  <?php
	  array_push($group[$x],$y);
	?>
  @endfor	
  @endfor
  @for($i=1;$i<=$totalData;$i++)
  @for($x=0;$x<=$division;$x++)
      @if (in_array($i, $group[$x]))
      <?php
      $sl = $margin_top = 0 ;
      //print "<br> $i => ".($arrName_xposition[$i]);continue;
      if( $arrName_xposition[$i] > 0 ){
           $margin_top      = ($arrName_xposition[$i]);
           $sl              = $arrName_yposition[$i];
        }else{
               $margin_top      = 230+($x*90);
               $style_left[$x] += 80;
               $sl              = $style_left[$x];
      }
               ?>
      @endif
  @endfor  
  <div class="drag2" id="drag{{$i}}" table_id="{{  $arrTableId[$i] }}" table_number="{{  $arrtable_number[$i] }}" reservation_id="{{ $arrName_reservation_id[$i] }}" style="left:{{  $sl }}px;top:{{  $margin_top }}px;" status="0"> <a class="boxclose" id="back{{  $i }}" number="{{  $i }}" top="" left=""  table_id="{{  $arrTableId[$i] }}" table_number="{{  $arrtable_number[$i] }}" reservation_id="{{ $arrName_reservation_id[$i] }}"> <img src="{{ url('images/dragndrop/back.png') }}" width="20"> </a>
    <div class="name">{{  $arrName[$i] }}</div>
  </div>
  <script>
$(function($){
    setTimeout(function () { 
      var text = watermark.text;
	  //watermark([ '<?php echo url('images/dragndrop/table.png') ?>'])
	  watermark([ '<?php 
				 if($arrName_reservation_id[$i] > 0){
				 	echo url('images/dragndrop/1/'.$arrName_table[$i].'.png'); 
				 } else {
					 echo url('images/dragndrop/0/'.$arrName_table[$i].'.png'); 
				 }
				 ?>'])
        .image(text.center('{{  $i }}', '18px Josefin Slab', '#000', 1, 48))
        .then(function (img) {
          $('#drag{{  $i }}').css("background","url('"+img.src+"') no-repeat");
        });

    }, 1000);
});
</script>
  @endfor </div>
<div class="clear"></div>
<script>
	/*var img = new Array("<?php echo url('images/dragndrop/bg/gray-1.jpg'); ?>","<?php echo url('images/dragndrop/bg/yellow-2.jpg'); ?>");
	var imgElement = document.getElementById("imgDemo");
	var i = 0;
	var imgLen = img.length;
	function nxt()
	{
		if(i < imgLen-1)
			{
				i++;
			}
		else{
				i=0;                
			}

			imgElement.src = img[i];                    
	}

	function prvs()
	{
		if(i > 0)
			{
				i--;
			}
		else
		{
			i = imgLen-1;
		}
			imgElement.src = img[i];                    
	}*/
	var img = $("#imgDemo");
	<?php
	if(isset($saved_background_src) && $saved_background_src!=''){
		echo '$("#container-floor").attr("style", "background:url('.$saved_background_src.')");';
	}
	?>
	
</script>
<script>
$( document ).ready(function() {
	  var owl = $("#owl-demo");
	  owl.owlCarousel({

      items : 3, //10 items above 1000px browser width
      itemsDesktop : [1000,3], //5 items between 1000px and 901px
      itemsDesktopSmall : [900,3], // 3 items betweem 900px and 601px
      itemsTablet: [600,1], //2 items between 600 and 0;
      itemsMobile : false, // itemsMobile disabled - inherit from itemsTablet option
	  autoPlay: false,
	  touchDrag : false      
      });

      // Custom Navigation Events
      $("#next").click(function(){
        owl.trigger('owl.next');
      });
      $("#prev").click(function(){
        owl.trigger('owl.prev');
      });

/*$('#myCarousel').carousel({
  interval: false,
  
});

$('.carousel .item').each(function(){
  var next = $(this).next();
  if (!next.length) {
    next = $(this).siblings(':first');
  }
  next.children(':first-child').clone().appendTo($(this));
  
  if (next.next().length>0) {
 
      next.next().children(':first-child').clone().appendTo($(this)).addClass('rightest');
      
  }
  else {
      $(this).siblings(':first').children(':first-child').clone().appendTo($(this));
     
  }
});*/
});
</script>
@stop 