@extends('template.theme')

@section('content')
<div class="content">
    @if($data != '')
		<div class="ui breadcrumb">
        	<a href="#" class="sidebar open">Admin</a>
	        <i class="right chevron icon divider"></i>
	        
	        <a href="{{ url('admin/subcategories') }}" class="section">Subrubrieken</a>
			<i class="right arrow icon divider"></i>

			<div class="active section">Wijzig subrubriek: {{ $data->name }}</div>
	    </div>

    	<div class="ui divider"></div>

		<?php echo Form::open(array('method' => 'post', 'class' => 'ui edit-changes form')) ?>
			<div class="left section">
				<div class="field">
					<div class="ui switch checkbox">
						<?php echo Form::checkbox('no_show', 1, $data->no_show) ?>
						<label>Niet tonen</label>
					</div>	
				</div>
				
				<div class="field">
				    <label>Naam</label>
				    <?php echo Form::text('name', $data->name) ?>
				</div>	

				<div class="field">
			        <label>Categorie</label>
			        <?php 
			        echo Form::select(
			        	'category[]', 
			        	$categories, 
			        	$subcatCategories,
			        	array(
			        		'multiple' => 'multiple',
			        		'class' => 'multipleSelect'
			        	)
			        ) ?>
			    </div>

				<button class="ui tiny button" type="submit"><i class="pencil icon"></i> Wijzigen</button>
			</div>

			<div class="right section" style="padding-left: 20px;">

			</div>
		<?php echo Form::close(); ?>
	@else
		<div class="ui error message">Het formulier met record ID <strong>{{ Request::segment(4) }}</strong> is niet gevonden.</div>
	@endif
</div>
<div class="clear"></div>
@stop