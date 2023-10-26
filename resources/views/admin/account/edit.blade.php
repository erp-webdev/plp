@extends('admin.layouts.app')
 
@section('content')
    <div class="col-xs-12 col-sm-12 col-md-12 margin-tb">
        <div>
            <h2>My Account</h2>
        </div>
        <hr/>
    </div>
    <div class="col-xs-12 col-sm-5 col-md-5">
        <form method="POST" action="{{ route('account.update', $user->id) }}">
            <input name="_method" type="hidden" value="PATCH">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="row">
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

                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Password:</strong>
                            <input type="password" name="password" placeholder="Password" class="form-control" <?php if(Auth::user()->id != $user->id){ echo 'readonly';} ?>>
                            <span class="help-block">Leave blank if password is not changed</span>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Confirm Password:</strong>
                            <input type="password" name="confirm-password", placeholder="Confirm Password" class="form-control" <?php if(Auth::user()->id != $user->id){ echo 'readonly';} ?>>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Save</button>
            </div>
        </form>
    </div>
@endsection