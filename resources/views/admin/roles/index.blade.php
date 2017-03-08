@extends('admin.layouts.app')
@section('content')
    <div class="col-xs-12 col-sm-5 col-md-5 margin-tb">
        <div class="">
            <h2>Role Management</h2>
        </div>
        <div class="">
            <a href="{{ route('users.index') }}" class="btn btn-default btn-sm"><i class="fa fa-arrow-left"></i> Back</a>
            <a class="btn btn-success btn-sm" href="{{ route('roles.create') }}"><i class="fa fa-plus"></i> Create New Role</a>
        </div>
        <hr/>
    </div>
	<div class="col-xs-12 col-sm-12 col-md-12">
		<div class="table-responsive">
			<table id="dataTable" class="table table-striped table-condensed table-hover" cellspacing="0">
				<thead>
					<th>No</th>
					<th>Name</th>
					<th>Description</th>
					<th>Action</th>
				</thead>
				<tbody>
					@foreach ($roles as $key => $role)
						<tr>
							<td>{{ ++$i }}</td>
							<td>{{ $role->display_name }}</td>
							<td>{{ $role->description }}</td>
							<td>
								<form method="POST" action="{{ route('roles.destroy', $role->id) }}">
									<input name="_method" type="hidden" value="DELETE">
							        <input type="hidden" name="_token" value="{{ csrf_token() }}">
									<div class="btn-group">
										<a class="btn btn-info btn-xs" href="{{ route('roles.show',$role->id) }}"><i class="fa fa-eye"></i></a>
											<a class="btn btn-primary btn-xs" href="{{ route('roles.edit',$role->id) }}"><i class="fa fa-pencil"></i></a>
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