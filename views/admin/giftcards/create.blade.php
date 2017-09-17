@extends('template.theme')

@section('scripts')
<script type="text/javascript">
    $(document).ready(function () {
        closeBrowser();
    });
</script>
@stop

@section('content')
<div class="content">
    @include('admin.template.breadcrumb')

    <?php echo Form::open(array('url' => 'admin/' . $slugController . '/create', 'method' => 'post', 'class' => 'ui edit-changes form', 'files' => TRUE)) ?>
    <div class="left section">
        <div class="two fields">
            <div class="field">
                <label>Bedrijf</label>
                <?php echo Form::select('company', array_add($companies, '0', 'UwVoordeelpas'), 0, array('class' => 'ui normal search dropdown')); ?>
            </div>
            <div class="field">
                <label>Saldo</label>
                <?php echo Form::text('amount'); ?>
            </div>
        </div>

        <div class="two fields">
            <div class="field">
                <label>Code</label>
                <?php echo Form::text('code'); ?>
            </div>	

            	
            <div class="field">
                <label>Maximale gebruik</label>
                <?php echo Form::text('max_usage'); ?>
            </div>	
        </div>
        <button class="ui tiny button" type="submit"><i class="plus icon"></i> Aanmaken</button>
    </div>

    <div class="right section" style="padding-left: 20px;">
        <div class="field">
            <label>Giftcard inschakelen</label>
            <div class="ui toggle checkbox">
                <?php echo Form::checkbox('is_active', 1, 1); ?>
                <label>Actief</label>
            </div>
        </div>
    </div>
    <?php echo Form::close(); ?>
</div>
<div class="clear"></div>
@stop