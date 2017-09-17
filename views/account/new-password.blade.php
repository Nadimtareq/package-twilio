@extends('template.theme')


@section('scripts')
<script type="text/javascript">
$(document.body).animate({
    'scrollTop': $('#newPassword').offset().top
}, 4000);
</script>
@stop

@section('content')
<div class="content">
    <div id="newPassword" class="left section">
        <h5>Nieuw wachtwoord instellen</h5>
       <?php echo Form::open(array('method' => 'post', 'class' => 'ui form')) ?>
		<div class="field">
			<label>Wachtwoord</label>
			<?php echo Form::password('password') ?>
		</div>

		<div class="field">
			<label>Wachtwoord controle</label>
			<?php echo Form::password('password_confirmation') ?>
		</div>
		
		<button class="ui button" name="action" value="update" type="submit"><i class="wrench icon"></i> Herstellen</button>
		<?php echo Form::close(); ?>
    </div>
</div>
<div class="clear"></div>
@stop