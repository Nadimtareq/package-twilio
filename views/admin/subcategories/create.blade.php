@extends('template.theme')

@section('content')
<div class="content">
 	@if (isset($success))
		<div class="ui success message">{{ $success }}</div> 
	@elseif (count($errors) > 0)
		<div class="ui error message">
			<div class="header">Er zijn fouten opgetreden</div>
			<ul class="list">
				@foreach ($errors->all() as $error)
				    <li>{{ $error }}</li>
				@endforeach
			</ul>
		 </div>
	@endif

 	<div class="ui breadcrumb">
        <a href="#" class="sidebar open">Admin</a>
        <i class="right chevron icon divider"></i>
            
        <a href="{{ URL::to('admin/subcategories') }}" class="section">
       	 	Subrubrieken
        </a>
        <i class="right arrow icon divider"></i>

        <div class="active section">Nieuwe subrubriek</div>
    </div>
    <div class="ui divider"></div>

	<?php echo Form::open(array('url' => 'admin/'.$slugController.'/create', 'method' => 'post', 'class' => 'ui edit-changes form', 'files' => TRUE)) ?>
	    <div class="left section">
			<div class="field">
			    <label>Naam</label>
			    <?php echo Form::text('name') ?>
			</div>

			<div class="field">
		        <label>Categorie</label>
		        <?php echo Form::select('category', $categories) ?>
		    </div>

			<button class="ui tiny button" type="submit"><i class="plus icon"></i> Aanmaken</button>
		</div>

		<div class="right section" style="padding-left: 20px;">
		</div>
	<?php echo Form::close(); ?>
</div>
<div class="clear"></div>
@stop