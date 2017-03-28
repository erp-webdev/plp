@extends('admin.layouts.app')
@section('content')

<div class="row">
	<div class="col-xs-12 col-sm-12 col-md-12">
		<h1>Employee's Ledger</h1>
		<hr>
		@include('admin.loans.ledger')
	</div>	
</div>	


@endsection