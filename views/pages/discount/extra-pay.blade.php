<!doctype html>

<html lang="en">
    <head>
        <meta charset="utf-8">
        <script>
	  var baseUrl = {!! json_encode(url('/')."/") !!};             
	</script>
        <script type="text/javascript" src="{{ asset('js/jquery-1.11.3.min.js?rand='.str_random(40)) }}"></script>

        <!--[if lt IE 9]>
          <script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.js"></script>
        <![endif]-->
    </head>

    <body>
        <?php if (isset($temp_reservation_id) && !empty($temp_reservation_id)): ?>
            <?php echo Form::open(array('id' => 'paymentForm', 'url' => 'payment/pay?buy=pay_extra_for_deal', 'method' => 'post', 'class' => 'ui form')) ?><?php echo Form::open(array('id' => 'formList', 'url' => 'payment/pay?buy=pay_extra_for_deal', 'method' => 'post', 'class' => 'ui form')) ?>
            <input type="hidden" name="temp_reservation_id" value="<?php echo $temp_reservation_id; ?>">
        <?php elseif (isset($future_deal_id) && !empty($future_deal_id)): ?>
            <?php echo Form::open(array('id' => 'paymentForm', 'url' => 'payment/pay?buy=future_deal', 'method' => 'post', 'class' => 'ui form')) ?>        
            <input type="hidden" name="future_deal_id" value="<?php echo $future_deal_id; ?>">
        <?php endif; ?>           
        <input id="actionMan" type="hidden" name="action">
        <input type="hidden" name="amount" class="amount" id="charge_amount" value="<?php echo $amount; ?>">        
        <?php echo Form::close(); ?>
        <script type="text/javascript">
$(function () {    
    $('#paymentForm').submit();
});
        </script>
    </body>
</html>






