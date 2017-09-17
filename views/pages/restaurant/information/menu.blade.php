<h5 class="ui header thin">
    <i class="icon food"></i>
    <div class="content">Menu</div>
</h5>
  @if(isset($deals) && count($deals))
        <?php foreach ($deals as $deal): ?>
<div class="row">
<!--    <div class="col-md-3">
        <img id="image" src="{{ url($media[0]->getUrl('175Thumb')) }}" class="img-responsive" alt="" />
    </div>-->

    <div class="col-md-6">

            <div class="text">
                <h2>{{ $deal->name }}</h2>
                <p><?php echo html_entity_decode($deal->description); ?></p>
                <p>{{-- str_limit($deal->description, (isset($limitChar) ? $limitChar : 210)) --}}</p>
            </div>
        </div>

        <div class="col-md-3 pull-right">
            <div class="mdg_price">
                <p>
                    <span style="position: relative; font-weight: normal;  ">
                        <s>&euro; {{ $deal->price_from }}</s>
                    </span>
                    <span style="position: relative;  font-weight: bold; margin-top:10px; margin-left: 10px;">
                        &euro; {{ $deal->price }}
                    </span>
                </p>
            </div>
        </div>
    </div>
    <div class="clear"></div>
<?php endforeach; ?>
@endif