@extends('admin.layouts.app')

@section('content')
    <div class="col-xs-12 col-sm-12 col-md-12 margin-tb">
        <div class="">
            <h2>Create New User</h2>
        </div>
        <div class="">
            <a class="btn btn-default btn-sm" href="{{ route('users.index') }}"><i class="fa fa-arrow-left"></i> Back</a>
        </div>
        <hr/>
    </div>
    @if ($message = Session::get('success'))
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
                <p>{{ $message }}</p>
            </div>
        </div>
    @elseif ($message = Session::get('error'))
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="alert alert-danger">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
                <p>{{ $message }}</p>
            </div>
        </div>
    @endif
    <form method="POST" action="{{ route('users.store') }}">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
    	<div class="col-xs-12 col-sm-5 col-md-5">
    		<div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Name:</strong>
                    <input type="text" name="name" class="form-control" placeholder="Name" value="{{ Input::old('name') }}">
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Employee ID:</strong>
                    <input type="text" name="employee_id" class="form-control" placeholder="Employee ID" value="{{ Input::old('employee_id') }}">
                </div>
            </div>
			<div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Database:</strong>
					<select name="database" class="form-select form-select-sm">
						@foreach($databases as $database)
							<option value="{{ $database }}">{{ $database }}</option>
						@endforeach
					</select>
                </div>
            </div>
            <!-- <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Password:</strong>
                    <input type="password" name="password" class="form-control" placeholder="Password" value="">
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Confirm Password:</strong>
                    <input type="password" name="confirm-password" class="form-control" placeholder="Confirm Password" value="">
                </div>
            </div> -->
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Role:</strong>
                     <?php
                        foreach ($roles as $role) { ?>
                            <li style=" list-style-type:none"><input type="checkbox" name="roles[]" value="{{ $role->id }}"> {{ $role->display_name  }} </li>
                        <?php } ?>
                </div>
            </div>
    	   <button type="submit" class="btn btn-success btn-sm btn-block">Submit <i class="fa fa-send"></i></button>
    	</div>
    </form>
@endsection
