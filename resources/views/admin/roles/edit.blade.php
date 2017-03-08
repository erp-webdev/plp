@extends('admin.layouts.app')
 
@section('content')
    <div class="col-xs-12 col-sm-5 col-md-5 margin-tb">
        <div class="">
            <h2>Edit Role</h2>
        </div>
        <div class="">
            <a class="btn btn-default btn-sm" href="{{ route('roles.index') }}"><i class="fa fa-arrow-left"></i> Back</a>
        </div>
        <hr>
    </div>
	<div class="col-xs-12 col-sm-12 col-md-12">
		<form method="POST" action="{{ route('roles.update', $role->id) }}">
	        <input name="_method" type="hidden" value="PATCH">
	        <input type="hidden" name="_token" value="{{ csrf_token() }}">
	        	<div class="col-xs-12 col-sm-5 col-md-5">
		            <div class="form-group">
		                <strong>Name:</strong>
		                <input type="text" name="name" placeholder="Name" class="form-control input-sm" value="{{ $role->name }}" readonly>
		            </div>
		        </div>
				<div class="col-xs-12 col-sm-5 col-md-5">
		            <div class="form-group">
		                <strong>Display Name:</strong>
		                <input type="text" name="display_name" placeholder="Name" class="form-control input-sm" value="{{ $role->display_name }}">
		            </div>
		        </div>
		        <div class="col-xs-12 col-sm-5 col-md-5">
		            <div class="form-group">
		                <strong>Description:</strong>
		                <input type="textarea" name="description" placeholder="Description" class="form-control input-sm" style="height:100px" value="{{ $role->description }}">
		            </div>
		        </div>
		        <div class="col-xs-12 col-sm-12 col-md-12">
		            <div class="form-group">
		                <strong>Permission:</strong>
		                <br/>
		                @foreach($permission as $value)
	                        <div class="col-md-3 col-xs-12 col-sm-4">
	                            <label title='<?php echo $value->description; ?>' data-toggle="tooltip">
	                                <input type="checkbox" name="permission[]" value="{{ $value->id }}" class="name" <?php if(in_array($value->id, $rolePermissions)) echo 'checked'; ?>>
	                                {{ $value->display_name }}
	                            </label>
	                            <br/>
	                        </div>
                   		 @endforeach
		            </div>
		        </div>
		        <div class="col-xs-12 col-sm-12 col-md-12">
					<button type="submit" class="btn btn-success btn-sm"><i class="fa fa-save"></i> Submit</button>
		        </div>
		</form>
	</div>
@endsection