@extends('admin.layouts.app')
 
@section('content')
	<div class="col-xs-12 col-sm-12 col-md-12 margin-tb">
	    <div>
	        <h2>Users Management</h2>
	    </div>
	    <div>
	    		@permission('roles')
	        	<a href="{{ route('roles.index') }}" class="btn btn-primary btn-sm"><i class="fa fa-list"></i> Roles</a>
	        	@endpermission
	        	<a class="btn btn-success btn-sm" href="{{ route('users.create') }}"> <i class="fa fa-user-plus"></i> New User</a>
	    </div>
		<hr/>
	</div>
	<div class="col-xs-12 col-sm-12 col-md-12">
		<div class="table-responsive">
			<table id="dataTable" class="table table-striped table-condensed table-hover" cellspacing="0">
				<thead>
					<th>No</th>
					<th>ID/Username</th>
					<th>Name</th>
					<th>Email</th>
					<th>Active</th>
					<th>Roles</th>
					<th>Action</th>
				</thead>
				<tbody>
					@foreach ($data as $key => $user)
					<tr>
						<td>{{ ++$i }}</td>
						<td>{{ $user->employee_id }}</td>
						<td>{{ $user->name }}</td>
						<td>{{ $user->email }}</td>
						<td><?php if($user->active=='1') echo 'True'; else echo 'False'; ?></td>
						<td>
							@if(!empty($user->roles))
								@foreach($user->roles as $v)
									<label class="label label-success">{{ $v->display_name }}</label>
								@endforeach
							@endif
						</td>
						<td>
							<form method="POST" action="{{ route('users.destroy', $user->id) }}">
								<input name="_method" type="hidden" value="DELETE">
								<input type="hidden" name="_token" value="{{ csrf_token() }}">
								<div class="btn-group">
									<a class="btn btn-info btn-xs" href="{{ route('users.show', $user->id) }}"><i class="fa fa-eye"></i></a>
										<a class="btn btn-primary btn-xs" href="{{ route('users.edit',$user->id) }}"><i class="fa fa-pencil"></i></a>
										<button type="submit" value="Delete" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i></button>
								</div>
							</form>
						</td>
					</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>

	
@endsection