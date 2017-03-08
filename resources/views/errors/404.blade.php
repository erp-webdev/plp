@extends('errors.layout')
@section('content')
	<style type="text/css">
		body, pre, .wrapper{
			background-color: #EAAB0C;
			color: #fff;
		}
	</style>
	<div class="content">
		<!-- Icon -->
		<div class="col-xs-12 col-sm-3 col-md-3">
			<img src="{{ url('/404-5.png') }}" class="img-responsive">
		</div>
		<!-- Message -->
		<div class="col-xs-12 col-sm-9 col-md-9">
			<div style="padding-left:5px">
				<p class="title">404</p>
				<h4>PAGE NOT FOUND</h4>
				<p>This page may have been moved or deleted. <br>
				Be sure to check your spelling. <br>
				The record or page you are accessing is not found. <br>
				Go back to your dashboard and follow the links.</p>
			</div>
		</div>
	</div>
@endsection