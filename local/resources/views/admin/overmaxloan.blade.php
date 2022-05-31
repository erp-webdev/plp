@extends('admin.layouts.app')
@section('content')
<form action="{{ route('preferences.loan') }}" method="post">
    {{ csrf_field() }}

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <h1>Above Maximum Loanable Amount</h1>
            <p>The list allows employee to submit PLP applications with loanable amount above their maximum limit. Each entry is applicable for one time use only and must be applied on or before the expiration date.</p>
            <div class="table-responsive">
                <table class="table table-sm table-hover table-striped">
                    <thead>
                        <th>ID</th>
                        <th>EmpID</th>
                        <th>DBName</th>
                        <th>FullName</th>
                        <th>Expired AT</th>
                        <th>Created At</th>
                        <th>Created By</th>
                        <th>Delete</th>
                    </thead>
                    <tbody>
                        <tr>
                            <td><a class="btn btn-default" data-target="#SearchModal" data-toggle="modal"><i class="fa fa-search"></i></a></td>
                            <td><input type="text" class="form-control" name="EmpID" readonly></td>
                            <td><input type="text" class="form-control" name="DBName" readonly></td>
                            <td><input type="text" class="form-control" name="FullName" readonly></td>
                            <td><input type="date" class="form-control" name="ExpiredAt"></td>
                            <td>&nbsp;</td>
                            <td>{{ Auth::user()->name }}</td>
                            <td><button type="submit" name="save" class="btn btn-primary"><i class="fa fa-save"></i></button></td>
                        </tr>
                        @foreach($employees as $employee)
                        <tr>
                            <td>{{ $employee->id }}</td>
                            <td>{{ $employee->EmpID }}</td>
                            <td>{{ $employee->DBName }}</td>
                            <td>{{ $employee->FullName }}</td>
                            <td>{{ $employee->ExpiredAt }}</td>
                            <td>{{ $employee->CreatedAt }}</td>
                            <td>{{ $employee->CreatedBy }}</td>
                            <td><button class="btn btn-danger" type="submit" name="delete" value="{{ $employee->id }}"></button></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</form>

@endsection