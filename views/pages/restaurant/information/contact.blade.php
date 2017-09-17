<ul class="ui icon list" style="list-style-type: none;">
	<li class="item">
		<i class="home icon"></i>
    	<div class="content">
    		 {!! $company->address !!}<br />
    		 {!! $company->zipcode !!}  {!! $company->city !!}
    	</div>
	</li>

	<li class="item">
		<i class="phone icon"></i>
    	<div class="content">
			<a href="tel:{!! $company->phone !!}" target="_blank">
				{!! $company->phone !!}
			</a>
    	</div>
	</li>

	<li class="item">
		<i class="globe icon"></i>
    	<div class="content">
			<a href="http://{!! $company->website !!}" target="_blank">
				{!! $company->website !!}
			</a>
    	</div>
	</li>
</ul>

@if(trim($company->contact_email) != '' || trim($company->email) != '')
	<?php echo Form::open(array('url' => 'contact/'.$company->slug, 'method' => 'post', 'class' => 'ui form')) ?>
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
			<?php echo Form::textarea('content') ?>
		</div>

		<div class="two fields">
			<div class="six wide field">
		  		{!! captcha_image_html('ContactCaptcha') !!}
		  	</div>

			<div class="field">	
				<label>Typ de beveiligingscode over:</label>
		  		<?php echo Form::text('CaptchaCode', '', array('id' => 'CaptchaCode')); ?>
		  	</div>
	  	</div>

		<button type="submit" class="ui small blue button">VERZENDEN</button>
	<?php echo Form::close(); ?>
@endif