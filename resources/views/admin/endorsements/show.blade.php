@extends('admin.layouts.app')
@section('content')
<div class="row">
	<div class="col-xs-12 col-sm-12 col-md-12">
		<h1>Endorsements</h1>
		<hr>
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12">
				{{ json_encode($endorsement) }}
			</div>
		</div>
	</div>
</div>
@endsection