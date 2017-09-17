@extends('layouts.app')

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
                        <form method ="post" action="push-sms" novalidate="false" autocomplete="true">
                        {{ csrf_field() }}
                            <h4>Push message</h4> 
                            <label>Mob Number</label>
                            <input type="number" name="phone" value="7278969286"></br>  
                            <input type="submit" name="submit" value="Send">
                        </form>
                    </div>

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
                <div class="row">

                    <div class="col-md-6">
                        <h2>Send Multiple SMS</h2>
                        <form method ="post" action="multi-sms" novalidate="false" autocomplete="true">
                        {{ csrf_field() }}
                        <h4>Seletct Numbers</h4>   
                        <input type="checkbox" name="multinumber[]" value="+918927054384"> +918927054384<br>
                        <input type="checkbox" name="multinumber[]" value="+917278969286"> +917278969286<br>
                        <input type="checkbox" name="multinumber[]" value="+919871701254"> +919871701254<br>
                        <input type="checkbox" name="multinumber[]" value="+919970694426"> +919970694426<br>
                        <br>
                            <label>Message</label>
                            <input type="text" name="msg">
                            <input type="submit" name="submit" value="Send">
                        </form>
                    </div>

                    <div class="col-md-6">
                        <h2>Call a Number</h2>
                        <form method="post" action="call-no" novalidate="false" autocomplete="true">
                        {{ csrf_field()}} 
                            <label>Country code</label>
                            <input type="text" name="c_code" value=+91>
                            <label>Mob Number</label>
                            <input type="number" name="phone" value="8927054384">
                            <input type="submit" name="submit" value="Call">
                        </form>                        
                    </div>
       
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
