@extends('admin.layouts.app')

@section('content')

<style type="text/css">
	.bs-callout {
    padding: 20px;
    margin: 20px 0;
    border: 1px solid #eee;
    border-left-width: 5px;
    border-radius: 3px;
}
.bs-callout h4 {
    margin-top: 0;
    margin-bottom: 5px;
}
.bs-callout p:last-child {
    margin-bottom: 0;
}
.bs-callout code {
    border-radius: 3px;
}
.bs-callout+.bs-callout {
    margin-top: -5px;
}
.bs-callout-default {
    border-left-color: #777;
}
.bs-callout-default h4 {
    color: #777;
}
.bs-callout-primary {
    border-left-color: #428bca;
}
.bs-callout-primary h4 {
    color: #428bca;
}
.bs-callout-success {
    border-left-color: #5cb85c;
}
.bs-callout-success h4 {
    color: #5cb85c;
}
.bs-callout-danger {
    border-left-color: #d9534f;
}
.bs-callout-danger h4 {
    color: #d9534f;
}
.bs-callout-warning {
    border-left-color: #f0ad4e;
}
.bs-callout-warning h4 {
    color: #f0ad4e;
}
.bs-callout-info {
    border-left-color: #5bc0de;
}
.bs-callout-info h4 {
    color: #5bc0de;
}
</style>
<!-- https://1drv.ms/w/s!AttElQx2Dc5ohtw8rbcp2ZMOrpyVpg -->
	<div class="col-xs-12 col-sm-12 col-md-12">
		<h1>Documentation</h1>
		<p></p>
	</div>	
	<div class="col-xs-12 col-sm-3 col-md-3" style="height: 75vh; overflow: auto;" >
		<h3>Contents</h3>
	</div>
	<div class="col-xs-12 col-sm-9 col-md-9" style="height: 75vh; overflow: auto;">

	</div>
@endsection
