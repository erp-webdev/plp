@extends('layouts.app')
@section('content')
	<!DOCTYPE html>
	<html>
	<head>
		<title>Error</title>
		<!-- <meta HTTP-EQUIV="Refresh" CONTENT="5; URL=/"> -->
	</head>
	<body >
	<div class="col-xs-12 col-md-12 col-sm-12">
		<h1><strong>Oops!</strong> </h1>
		<h3>Looks like something went wrong.</h3>
		@if($app_debug==true)
				<div class="panel panel-danger">
					<div class="btn-danger" role="button" data-toggle="collapse" data-parent="#accordion" href="#stack" aria-expanded="true" aria-controls="stack">
						<div class="panel-heading">
							<div class="panel-title">
								<p><strong>Error Message:</strong> {{ $e->getMessage() }}</p>
								<p><strong>File: </strong>{{ $e->getFile() }} Line: {{ $e->getLine() }} </p>
							</div>
						</div>
					</div>
					<div id="stack" class="panel-collapse collapse">
						<div class="panel-body" class="collapse">
							<p><strong>Stack Trace: </strong>{{ $e->getTraceAsString() }}</p>
						</div>
					</div>
				</div>
		@endif
	</div>
	
	</body>
	</html>
@endsection