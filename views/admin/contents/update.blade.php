@extends('template.theme')
@section('scripts')
@include('admin.template.editor')
	<script type="text/javascript" src="{{ URL::asset('public/js/tinymce/tinymce.min.js') }}"></script>
	<script>
	tinymce.init({
	    selector: "textarea",
	    theme: "modern",
	    height: 300,
	    plugins: [
	         "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
	         "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
	         "save table contextmenu directionality emoticons template paste textcolor"
	   ],
	   toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | l      ink image | print preview media fullpage | forecolor backcolor emoticons", 
	   style_formats: [
	        {title: 'Bold text', inline: 'b'},
	        {title: 'Red text', inline: 'span', styles: {color: '#ff0000'}},
	        {title: 'Red header', block: 'h1', styles: {color: '#ff0000'}},
	        {title: 'Example 1', inline: 'span', classes: 'example1'},
	        {title: 'Example 2', inline: 'span', classes: 'example2'},
	        {title: 'Table styles'},
	        {title: 'Table row 1', selector: 'tr', classes: 'tablerow1'}
	    ]
	}); 
	</script>

	<script type="text/javascript">
		$(document).ready(function() {
		    closeBrowser();  
		});
	</script>
@stop

@section('content')
<div class="content">
    @if($data != '')
   		@include('admin.template.breadcrumb')

		<?php echo Form::open(array('url' => 'admin/'.$slugController.'/update/'.$data->id, 'method' => 'post', 'class' => 'ui edit-changes form')) ?>
		<div class="ui grid">
			<div class="eleven wide column">
				<div class="field">
					<label>Naam</label>
					<?php echo Form::text('name', $data->name); ?>
				</div>
				
				<div class="field">
                	<label>Type</label>
                	<?php echo Form::select('type', array('mail' => 'Mail', 'website' => 'Website'), $data->type) ?>
            	</div>

				<div class="field">
		            <label>Categorie</label>
		            <?php echo Form::select('category', Config::get('preferences.content_blocks'), $data->category); ?>
		        </div>

				<div class="field">
					<label>Inhoud</label>
					<?php echo Form::textarea('content', $data->content, ['class' => 'editor']); ?>
				</div>	
			</div>	

			<div class="five wide column">
				<h4 class="ui header">Commando's</h4>
				<div class="ui styled accordion">
					<div class="active title">
                    	<i class="dropdown icon"></i>
                    	%discount%
	                </div>

	                <div class="active content">
	                    Geef de kortingspercentage weer
	                </div>

	                <div class="title">
	                    <i class="dropdown icon"></i>
	                    %days%
	                </div>

	                <div class="content">
	                    Geef de kortingsdagen weer
	                </div>

	                <div class="title">
	                    <i class="dropdown icon"></i>
	                    %discout_comment%
	                </div>

	                <div class="content">
	                    Geef de kortings opmerkingen weer
	                </div>


	                <div class="title">
	                    <i class="dropdown icon"></i>
	                    %randomPassword%
	                </div>

	                <div class="content">
	                    Geeft een uniek wachtwoord (werkt niet overal)
	                </div>

	                <div class="title">
	                    <i class="dropdown icon"></i>
	                    %invoicenumber%
	                </div>

	                <div class="content">
	                    Geeft factuurnummer van klant weer (werkt niet overal)
	                </div>

	                <div class="title">
	                    <i class="dropdown icon"></i>
	                    %name%
	                </div>

	                <div class="content">
	                    Geeft naam van klant weer
	                </div>

	                <div class="title">
	                    <i class="dropdown icon"></i>
	                    %cname%
	                </div>

	                <div class="content">
	                    Geef de kortings opmerkingen weer
	                </div>

	                <div class="title">
	                    <i class="dropdown icon"></i>
	                    %email%
	                </div>

	                <div class="content">
	                    Geeft e-mail adres van klant weer
	                </div>

	                <div class="title">
	                    <i class="dropdown icon"></i>
	                    %phone%
	                </div>

	                <div class="content">
	                    Geeft telefoonnummer van klant weer 
	                </div>

	                <div class="title">
	                    <i class="dropdown icon"></i>
	                    %date%
	                </div>

	                <div class="content">
	                    Geeft datum van reservering weer (Werkt alleen bij reserving templates)
	                </div>

	                <div class="title">
	                    <i class="dropdown icon"></i>
	                    %time%
	                </div>

	                <div class="content">
	                    Geeft tijd van reservering weer (Werkt alleen bij reserving templates)
	                </div>

	                <div class="title">
	                    <i class="dropdown icon"></i>
	                    %persons%
	                </div>

	                <div class="content">
	                    Geeft aantal personen van reservering weer (Werkt alleen bij reserving templates)
	                </div>

	                <div class="title">
	                    <i class="dropdown icon"></i>
	                    %comment%
	                </div>

	                <div class="content">
	                    Geeft opmerking van reservering van een klant weer (Werkt alleen bij reserving templates)
	                </div>

	                <div class="title">
	                    <i class="dropdown icon"></i>
	                    %saldo%
	                </div>

	                <div class="content">
	                    Geeft saldo van klant weer
	                </div>

	                <div class="title">
	                    <i class="dropdown icon"></i>
	                    %preferences%
	                </div>

	                <div class="content">
	                    Geeft voorkeuren van klant weer
	                </div>

	                <div class="title">
	                    <i class="dropdown icon"></i>
	                    %allergies%
	                </div>

	                <div class="content">
	                    Geeft allergieën van klant weer
	                </div>

	                <div class="title">
	                    <i class="dropdown icon"></i>
	                    %url%
	                </div>

	                <div class="content">
	                    Geeft link naar pagina weer
	                </div>

	                <div class="title">
	                    <i class="dropdown icon"></i>
	                    %euro%
	                </div>

	                <div class="content">
	                    Geeft een bedrag weer
	                </div>

	                <div class="title">
	                    <i class="dropdown icon"></i>
	                    %webshop%
	                </div>

	                <div class="content">
	                    Geeft webshop naam weer
	                </div>

	                <div class="title">
	                    <i class="dropdown icon"></i>
	                    %days%
	                </div>

	                <div class="content">
	                    Geeft kortings dagen weer
	                </div>
				</div>
			</div>
		</div>	
	    <div class="clear"></div><br />

		<button class="ui tiny button" type="submit"><i class="pencil icon"></i> Wijzigen</button>
		<?php echo Form::close(); ?>

		<div class="clear"></div>
	@else
		<div class="ui error message">Het formulier met record ID <strong>{{ Request::segment(4) }}</strong> is niet gevonden.</div>
	@endif
</div>
<div class="clear"></div>
@stop