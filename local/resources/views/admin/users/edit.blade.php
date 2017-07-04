@extends('admin.layouts.app')
 
@section('content')
    <div class="col-xs-12 col-sm-12 col-md-12 margin-tb">
        <div>
            <h2>Edit User</h2>
        </div>
        <div>
            <a class="btn btn-default btn-sm" href="{{ route('users.index') }}"><i class="fa fa-arrow-left"></i> Back</a>
        </div>
        <hr/>
    </div>
    
    <div class="col-xs-12 col-sm-5 col-md-5">
        <form method="POST" action="{{ route('users.update', $user->id) }}">
            <input name="_method" type="hidden" value="PATCH">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Name:</strong>
                        <input type="text" name="ID" placeholder="ID" class="form-control" value="{{ $user->employee_id }}" readonly>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Name:</strong>
                        <input type="text" name="name" placeholder="Name" class="form-control" value="{{ $user->name }}">
                    </div>
                </div>
                <div >
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group" >
                            <strong>Email:</strong>
                            <input type="text" name="email" placeholder="Email" class="form-control" value="{{ $user->email }}" <?php if(Auth::user()->id != $user->id){ echo 'readonly';} ?>>
                        </div>
                    </div>

                    <!-- <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Password:</strong>
                            <input type="password" name="password" placeholder="Password" class="form-control" <?php if(Auth::user()->id != $user->id){ echo 'readonly';} ?>>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Confirm Password:</strong>
                            <input type="password" name="confirm-password", placeholder="Confirm Password" class="form-control" <?php if(Auth::user()->id != $user->id){ echo 'readonly';} ?>>
                        </div>
                    </div> -->
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Active:</strong>
                        <input type="checkbox" name="active" <?php if($user->active=='1') echo 'checked'; ?>>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group" 
                        <strong>Roles:</strong>
                        <?php 
                            foreach ($roles as $role) {
                                $checked = false;
                                foreach ($userRole as $userrole) { 
                                    if($userrole->role_id==$role->id)
                                        $checked=true;
                                } ?>
                                    <li style=" list-style-type:none"><input type="checkbox" name="roles[]" value="{{ $role->id }}" <?php if($checked) { echo 'checked'; } ?>> {{ $role->display_name  }} </li>
                                <?php
                            }
                         ?>
                </div>
                <button type="submit" class="btn btn-success btn-sm btn-block"><i class="fa fa-save"></i> Save</button>
            </div>
        </form>
    </div>
@endsection