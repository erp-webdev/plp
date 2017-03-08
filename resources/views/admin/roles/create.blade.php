@extends('admin.layouts.app')
 
@section('content')
    <div class="col-xs-12 col-md-12 col-sm-12 margin-tb">
        <div class="">
            <h2>Create New Role</h2>
        </div>
        <div class="">
            <a class="btn btn-default btn-sm" href="{{ route('roles.index') }}"><i class="fa fa-arrow-left"></i> Back</a>
        </div>
        <hr>
    </div>
    <form method="POST" action="{{ route('roles.store') }}">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
    	<div class="col-xs-12 col-sm-12 col-md-12">
    		<div class="col-xs-12 col-sm-5 col-md-5">
                <div class="form-group">
                    <strong>Name:</strong>
                    <input type="text" name="name" placeholder="Name" class="form-control input-sm" value="{{ Input::old('name') }}">
                </div>
            </div>
    		<div class="col-xs-12 col-sm-5 col-md-5">
                <div class="form-group">
                    <strong>Display Name:</strong>
                    <input type="text" name="display_name" placeholder="Display Name" class="form-control input-sm" value="{{ Input::old('display_name') }}">
                </div>
            </div>
            <div class="col-xs-12 col-sm-5 col-md-5">
                <div class="form-group">
                    <strong>Description:</strong>
                    <input type="textarea" name="description" placeholder="Description" class="form-control input-sm" value="{{ Input::old('description') }}" style="height:100px">
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Permission:</strong>
                    <br/>
                    @foreach($permission as $value)
                        <div class="col-md-3 col-xs-12 col-sm-4">
                            <label>
                                <input type="checkbox" name="permission[]" value="{{ $value->id }}" class="name">
                                {{ $value->display_name }}
                            </label>
                            <br/>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <button type="submit" class="btn btn-success btn-sm">Submit <i class="fa fa-send"></i></button>
            </div>
    	</div>
@endsection