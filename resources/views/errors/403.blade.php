@extends('errors.layout')
@section('content')
	<style type="text/css">
		body, pre, .wrapper{
			background-color: #B70606;
			color: #fff;
		}
	</style>
	<div class="content">
		<!-- Icon -->
		<div class="col-xs-12 col-sm-3 col-md-3">
			<img src="{{ url('/forbidden.png') }}" class="img-responsive">
		</div>
		<!-- Message -->
		<div class="col-xs-12 col-sm-9 col-md-9">
			<div style="padding-left:5px">
				<p class="title">403</p>
				<h4>FORBIDDEN</h4>
				<p>You do not have permission to view this resource. <br> 
				Contact your HR Business Partner if you believe you should have access to this resouce.
					</p>
			</div>
		</div>
	</div>
@endsection