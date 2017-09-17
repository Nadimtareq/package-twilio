@extends('template.theme')

@section('scripts')
    @include('admin.template.editor')

    <script type="text/javascript">
        $(document).ready(function() {
            closeBrowser();  
        });
    </script>
@stop

@section('content')
<div class="content">
    @include('admin.template.breadcrumb')
    
	<?php echo Form::open(array('url' => 'admin/mailtemplates/create'.(isset($companyParam) ? '/'.$companyParam : ''), 'method' => 'post', 'class' => 'ui edit-changes form')) ?>
    <div class="left section">
        <div class="field">
            <label>Onderwerp</label>
            <?php echo Form::text('subject') ?>
        </div>      

        <div class="field">
            <label>Type</label>
            <?php 
            echo Form::select(
                'type', 
                array(
                    'mail' => 'Mail',
                    'call' => 'Bellen',
                    'message' => 'SMS',
                    'push' => 'Push',
                    'notifications' => 'Notificaties',
                ), 
                Request::has('type') ? Request::input('type') : 'mail', 
                array('class' => 'ui search normal dropdown')
            );
            ?>
        </div>

        <div class="field">
            <label>Categorie</label>
            <?php echo Form::select('category', Config::get('preferences.mail_templates'), '', array('class' => 'ui search normal dropdown')) ?>
        </div>

        @if(isset($companies))
        <div class="field">
            <label>Bedrijf</label>
            <?php echo Form::select('company', $companies, null, array('class' => 'ui normal search dropdown'));  ?>
        </div>        
        @endif

        <div class="field">
            <label>Inhoud</label>
            <?php echo Form::textarea('content', '', array('class' => 'editor')) ?>
        </div>  
    </div>    
    <div class="clear"></div><br />
	
	<button class="ui tiny button" type="submit"><i class="plus icon"></i> Aanmaken</button>
	<?php echo Form::close(); ?>
</div>
<div class="clear"></div>
@stop