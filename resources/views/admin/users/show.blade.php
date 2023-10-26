@extends('admin.layouts.app')

@section('content')
    <div class="col-xs-12 col-sm-12 col-md-12 margin-tb">
        <div>
            <h2> Show User</h2>
        </div>
        <div>
            <a class="btn btn-default btn-sm" href="{{ route('users.index') }}"><i class="fa fa-arrow-left"></i> Back</a>
        	<a class="btn btn-primary btn-sm" href="{{ route('users.edit',$user->id) }}"><i class="fa fa-edit"></i> Edit</a>
        </div>
        <hr/>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>ID:</strong>
            {{ $user->employee_id }}
        </div>
    </div>
	<div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Name:</strong>
            {{ $user->name }}
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Email:</strong>
            {{ $user->email }}
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Active:</strong>
			<?php if($user->active=='1') echo 'True'; else echo 'False'; ?>
        </div>
    </div>
	<div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Active:</strong>
			{{ $user->DBNAME }}
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Roles:</strong>
            @if(!empty($user->roles))
				@foreach($user->roles as $v)
					<label class="label label-success">{{ $v->display_name }}</label>
				@endforeach
			@endif
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
    	<div class="form-group">
    		<strong>Created at:</strong>
    		{{ date('d-M-Y H:i A', strtotime($v->created_at)) }}
    	</div>
    </div>
    <div class="col-xs-12 col-sm-5 col-md-5">
    	<div class="form-group">
    		<strong>Last Modified at:</strong>
            {{ date('d-M-Y H:i A', strtotime($v->updated_at)) }}
    	</div>
    </div>
@endsection
