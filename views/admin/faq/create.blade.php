@extends('template.theme')

@section('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            closeBrowser();  
        });
    </script>
@stop

@section('content')
<div class="content">
    @include('admin.template.breadcrumb')
    
	<?php echo Form::open(array('url' => 'admin/'.$slugController.'/create', 'method' => 'post', 'class' => 'ui edit-changes form')) ?>
    <div class="field">
        <label>Vraag</label>
        <?php echo Form::text('title') ?>
    </div>      

    <div class="field">
        <label>Categorie</label>
        <?php echo Form::select('category', $categories, '', array('id' => 'getCategories', 'class' => 'ui normal dropdown')) ?>
    </div>   

    <div class="field">
        <label>Subcategorie</label>
        <div id="getSubCategories" class="ui  normal selection dropdown disabled">
            <input type="hidden" name="subcategory">
            <i class="dropdown icon"></i>

            <div class="default text">Kies een subcategorie</div>

            <div class="menu"></div>
        </div>
    </div>   

    <div class="field">
        <label>Antwoord</label>
        <?php echo Form::textarea('content') ?>
    </div>  

	<button class="ui button" type="submit"><i class="plus icon"></i> Aanmaken</button>
	<?php echo Form::close(); ?>
</div>
<div class="clear"></div>
@stop