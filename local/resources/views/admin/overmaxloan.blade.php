@extends('admin.layouts.app')
@section('content')
<form action="{{ route('preferences.loan') }}" method="post">
    {{ csrf_field() }}

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <h1>Above Maximum Loanable Amount</h1>
            <p>The list allows employee to submit PLP applications with loanable amount above their maximum limit. Each entry is applicable for one time use only and must be applied on or before the expiration date.</p>
            <div class="table-responsive">
                <table class="table table-sm table-hover table-striped dataTable">
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
@section('modals')
<div id="SearchModal" class="modal fade-in">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                Search Employee
                <span class="">
                    <div class="input-group">
                        <input type="text" class="form-control" id="search" placeholder="Search..." autocomplete="off" class="form-control">
                        <span class="input-group-btn">
                            <button id="butotn" onclick="search()" class="btn btn-default"><i class="fa fa-search"></i> Search</button>
                        </span>
                    </div>
                </span>
            </div>
            <div class="modal-body">
                <table id="search_employee_table" class="table table-hover table-striped">
                    <thead>
                        <tr>
                            <th>EmpID</th>
                            <th>Name</th>
                            <th>DBName</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script>

function setApprover(event){
    $('input[name="EmpID"]').val($(event).data('empid'));
    $('input[name="DBName"]').html($(event).data('name'));
    $('input[name="FullName"]').val($(event).data('db'));

    $('#SearchModal').modal('hide');
}

function search(){
    $.ajax({
        type: "get",
        url: "{{ url('/getEmployee') }}",
        data: {search: $('#search').val() },
        success: function (response) {
            $('#search_employee_table tbody').html('');

            $.each(response, function (index, item) { 

                $('#search_employee_table tbody').append(
                '<tr onclick="setApprover(this)" data-empid="'+ item['EmpID'] +'" data-db="'+ item['DBNAME'] +'" data-name="'+ item['FullName'] +'">' +
                    "<td>" + item['EmpID'] + "</td>" +
                    "<td>" + item['FullName'] + "</td>" +
                    "<td>" + item['DBName'] + "</td>" +
                "</tr>"
                );
            });                 
        }
    });
}
</script>
@endsection