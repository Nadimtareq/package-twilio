@extends('template.theme')

@section('scripts')
	

	<script type="text/javascript">
		$(document).ready(function() {
		    closeBrowser();  
		});
	</script>
@stop

@section('content')
	<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    You are logged in!
                </div>
               
                <div class="row">

                    

                    <div class="col-md-6">
                        <h2>Send SMS</h2>
                        <form method ="post" action="send-sms" novalidate="false" autocomplete="true">
                        {{ csrf_field() }}
                            <label>Country code</label>
                            <input type="text" name="c_code" value=+91>
                            <label>Mob Number</label>
                            <input type="number" name="phone" value="8927054384"></br>
                            <label>Message</label>
                            <input type="text" name="msg">
                            <input type="submit" name="submit" value="Send">
                        </form>
                    </div>
       
                </div>

            </div>
        </div>
    </div>
</div>
<div class="clear"></div>
@stop