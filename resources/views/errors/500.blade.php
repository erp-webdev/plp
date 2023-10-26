@extends('errors.layout')
@section('content')
	<div class="content">
		<!-- Icon -->
		<div class="col-xs-12 col-sm-3 col-md-3">
			<!-- <img src="{{ url('/forbidden.png') }}" class="img-responsive"> -->
			<i class="fa fa-frown-o fa-5x"></i>
		</div>
		<!-- Message -->
		<div class="col-xs-12 col-sm-9 col-md-9">
			<div style="padding-left:5px">
				<p class="title">500</p>
				<h4>Ooops!</h4>
				<p>Something went wrong.</p>
			</div>
			<div>
				<!-- {{ var_dump($e) }} -->
			</div>
		</div>
	</div>
@endsection