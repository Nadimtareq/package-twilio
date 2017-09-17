<html>

<body>
 {{ Form::open(array('url' => url('/dev/rundata'), 'method' => 'post')) }}
	 {{ Form::text('sql') }}
	 {{ Form::submit() }}
 {{ Form::close() }} 
 
 @if ( isset($sql))
	{!! $sql !!}
 @endif
 </body>
 </html>