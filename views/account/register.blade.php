@inject('preferenceCity', 'App\Models\Preference')
@inject('preference', 'App\Models\Preference')
<input type="hidden" name="state" id="state" value="1">
<input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">

<script type="text/javascript">
$(document).ready(function() {
	$('select.registerSelect').select();
	$('.ui.normal.dropdown').dropdown();
	$('#email').autoEmail([

		/* Dutch ISP domains */
		"hotmail.com", "live.nl", "ziggo.nl",  "ziggo.com",  "outlook.nl", "hotmail.nl", "msn.nl", "upcmail.nl", "chello.nl", "a2000.nl", "casema.nl", "home.nl", "quicknet.nl",

		/* Default domains included */
		"aol.com", "outlook.nl", "att.net", "comcast.net", "facebook.com", "gmail.com", "gmx.com", "googlemail.com",
		"google.com", "hotmail.com", "hotmail.co.uk", "mac.com", "me.com", "mail.com", "msn.com",
		"live.com", "sbcglobal.net", "verizon.net", "yahoo.com", "yahoo.co.uk",

		/* Other global domains */
		"email.com", "games.com" /* AOL */, "gmx.net", "hush.com", "hushmail.com", "icloud.com", "inbox.com",
		"lavabit.com", "love.com" /* AOL */, "outlook.com", "pobox.com", "rocketmail.com" /* Yahoo */,
		"safe-mail.net", "wow.com" /* AOL */, "ygm.com" /* AOL */, "ymail.com" /* Yahoo */, "zoho.com", "fastmail.fm",
		"yandex.com",

		/* United States ISP domains */
		"bellsouth.net",  "charter.net", "comcast.net", "cox.net", "earthlink.net", "juno.com",

		/* Dutch ISP domains */
		"live.nl", "ziggo.nl",  "ziggo.com",  "outlook.nl", "hotmail.nl", "msn.nl", "upcmail.nl", "chello.nl", "a2000.nl", "casema.nl", "home.nl", "quicknet.nl",

		/* Belgian ISP domains */
		"hotmail.be", "live.be", "skynet.be", "voo.be", "tvcablenet.be", "telenet.be",
	], true);
	
});
</script>

<div class="default">
	<div class="ui buttons fluid">
		<a href="{{ url('social/login/facebook') }}" 
		   target="_blank" class="ui facebook icon button"> 
		   <i class="facebook icon"></i>
		   Facebook
		</a>

		<a href="{{ url('social/login/google') }}" 
		   target="_blank"
		   class="ui icon google plus button">
		  	<i class="google plus icon"></i>
		  	Google+
		</a>
	</div>

	<div class="ui horizontal divider">of</div>

	<div class="two fields">
		<div class="field">
			<label>Aanhef</label>
			<select name="gender" class="registerSelect">
				<option value="1">Dhr</option>
				<option value="2">Mvr</option>
			</select>
		</div>

		<div class="field">
			<label>Uw naam</label>
			<input type="text" name="name">
		</div>
	</div>

	<div class="two fields">
		<div class="field">
			<label>Telefoonnummer</label>
			<input type="text" name="phone">
		</div>

		<div class="field">
			<label>E-mailadres</label>
			<input type="text" id="email" name="email">
		</div>
	</div>

	<div class="field">
		<label>Nieuwsbrief</label>
                <?php
			$regio = array();
			$regio[''] = 'Maak uw keuze';
			foreach($preference->where('category_id', 9)->get() as $data) {
				$regio[$data->id] = $data->name;
			}
			?>
                <select name="city[]" id="city" class="registerSelect" multiple="multiple" data-placeholder="Maak uw keuze">
                @foreach($regio as $reg_key => $item)
                    <option value="{{$reg_key}}">{{$item}}</option>
				@endforeach
			</select>
		<?php //echo Form::select('city[]', (isset($preference[9]) ? $preference[9] : []), '',  array('class' => 'registerSelect', 'multiple' => 'multiple', 'data-placeholder' => 'Maak uw keuze')); ?>
	</div>

	<div class="two fields">
		<div class="field">
			<label>Wachtwoord</label>
			<input type="password" name="password">
		</div>

		<div class="field">
			<label>Wachtwoord controle</label>
			<input type="password" name="password_confirmation">
		</div>
	</div>

	<div class="field">
		<div class="ui checkbox">
			<?php echo Form::checkbox('av');  ?>
			<label>Ik ga akkoord met de <a href="{{ url('algemene-voorwaarden') }}" target="_blank">algemene voorwaarden</a></label>
		</div>
	</div>

	<button type="submit" class="next ui button right floated">Verder</button>
</div>

<div class="extra" style="display: none;">
	Stel hieronder uw voorkeuren in, zodat onze website er rekening mee houdt.<br /><br />
	
	<div class="two fields">
		<div class="field">
			<label>Voorkeuren</label>
			<?php echo Form::select('preferences[]', (isset($preference[1]) ? $preference[1] : []), '', array('class' => 'registerSelect', 'multiple' => 'multiple', 'data-placeholder' => 'Maak uw keuze')); ?>
		</div>

		<div class="field">
			<label>Aantal personen</label>
			
			<div class="ui normal compact selection dropdown">
                <input type="hidden" name="kids" value="1">
						
				<div class="default text">Personen</div>
                <i class="dropdown icon"></i>
                        
                <div class="menu">
					@for($i = 1; $i <= 10; $i++) 
                 		<div class="item" data-value="<?php echo $i; ?>"><?php echo $i; ?> <?php echo $i == 1 ? 'persoon' : 'personen'; ?></div>
					@endfor
                </div>
             </div>
		</div>
	</div>

	<div class="two fields">
        <div class="field">
        	<label>Favoriete keuken</label>
     	   	<?php echo Form::select('kitchens[]', (isset($preference[2]) ? $preference[2] : []), '', array('class' => 'registerSelect', 'multiple' => 'multiple', 'data-placeholder' => 'Maak uw keuze')); ?>
        </div>
        
        <div class="field">
     	   	<label>Duurzaamheid</label>
     	   	<?php echo Form::select('sustainability[]', (isset($preference[8]) ? $preference[8] : []), '',  array('class' => 'registerSelect', 'multiple' => 'multiple', 'data-placeholder' => 'Maak uw keuze')); ?>
     	</div>
	</div>

	<div class="two fields">
		<div class="field">
			<label>Faciliteiten</label>
			<?php echo Form::select('facilities[]', (isset($preference[7]) ? $preference[7] : []), '', array('class' => 'registerSelect', 'multiple' => 'multiple', 'data-placeholder' => 'Maak uw keuze')); ?>
		</div>

		<div class="field">
			<label>Allergie&euml;en</label>
			<?php echo Form::select('allergies[]', (isset($preference[3]) ? $preference[3] : []), '', array('class' => 'registerSelect', 'multiple' => 'multiple', 'data-placeholder' => 'Maak uw keuze')); ?>
		</div>	
	</div>

	<div class="two fields">
		<div class="field">
			<label>Soort bedrijf</label>
			<?php echo Form::select('price[]', (isset($preference[4]) ? $preference[4] : []), '', array('class' => 'registerSelect', 'multiple' => 'multiple', 'data-placeholder' => 'Maak uw keuze')); ?>      
		</div>

		<div class="field">
			<label>Korting</label>
			<?php echo Form::select('discount[]', (isset($preference[5]) ? $preference[5] : []), '', array('class' => 'registerSelect', 'multiple' => 'multiple', 'data-placeholder' => 'Maak uw keuze')); ?>
		</div>
	</div>

	<button id="back" type="submit" class="ui button left floated">Terug</button>
	<button type="submit" class="next ui blue button right floated">Aanmelden</button>
</div>