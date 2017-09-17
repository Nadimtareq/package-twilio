@extends('template.theme')

{{--*/ $pageTitle = 'Contact' /*--}}

@section('content')
<div class="container">
	<div class="ui breadcrumb">
		<a href="{{ url('/') }}" class="section">Home</a>
		<i class="right chevron icon divider"></i>
		<span class="active section"><h1>Contact</h1></span>
	</div>

	<div class="ui divider"></div>
	Heb je een vraag, suggestie, opmerking?
	Laat het ons weten.<br /><br />

	<?php echo Form::open(array('method' => 'post', 'class' => 'ui form')) ?>
		<div class="field">
			<label>Naam</label>
			<?php echo Form::text('name', (Sentinel::check() ? Sentinel::getUser()->name : '')) ?>
		</div>

		<div class="field">
			<label>E-mail</label>
			<?php echo Form::text('email',  (Sentinel::check() ? Sentinel::getUser()->email : '')) ?>
		</div>

		<div class="field">
			<label>Onderwerp</label>
			<?php echo Form::text('subject') ?>
		</div>

		<div class="field">
			<label>Bericht</label>
			<?php echo Form::textarea('content', null, ['size' => '30x80']) ?>
		</div>

		<div class="two fields">
			<div class="five wide field">
		  		{!! captcha_image_html('ContactCaptcha') !!}
		  	</div>

			<div class="field">	
				<label>Typ de beveiligingscode over:</label>
		  		<?php echo Form::text('CaptchaCode', '', array('id' => 'CaptchaCode')); ?>
		  	</div>
	  	</div>
			
		<button type="submit" class="ui small blue button">VERZENDEN</button>
	<?php echo Form::close(); ?>
</div>
<style>
	.ui.form textarea{
		padding: 3.78571em 1em;
	}
</style>
@stop