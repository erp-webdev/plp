@extends('admin.layouts.app')
@section('content')
@if(count($errors)>0)
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="alert alert-danger col-xs-12 col-sm-5 col-md-5">
            <strong>Attention!</strong><br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>    
@endif
@if ($message = Session::get('success'))
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    </div>
@elseif ($message = Session::get('error'))
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="alert alert-danger">
            <p>{{ $message }}</p>
        </div>
    </div>
@endif
<div id="search_employee" class="modal fade-in">
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
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<form class="form form-horizontal" action="{{ route('applications.store2') }}" method="post" >
<input type="hidden" name="_token" value="{{ csrf_token() }}">
    <div class="container-fluid">
        <div class="col-md-6 col-sm-6"> 
            <table class="table">
                <tbody>
                    <tr>
                        <td colspan="2"><h3>APPLICATION FORM</h3></td>
                    </tr>
                    <tr>
                        <td><label for="">
                                <input id="type" name="type" type="radio" 
                                    value="0" 
                                    <?php 
                                        if(!empty(old('type'))){
                                            if(old('type') == 0)
                                                echo 'checked';
                                        } elseif($records == 0 )
                                            echo 'checked';
                                            
                                    ?>
                                    required> NEW</label> </td>
                        <td>
                        <label for="">
                            <input id="type" name="type" type="radio" 
                            value="1" 
                            <?php 
                                if(!empty(old('type'))){
                                    if(old('type') == 1)
                                        echo 'checked';
                                } elseif($records != 0 )
                                    echo 'checked';
                                    
                            ?> 
                            required> RE-AVAILMENT</label>    
                            <br> <span>Previous loan amount: {{ number_format($previous_loan, 2, '.', ',') }}</span>
                            <br> <span>Balance: {{ number_format($balance, 2, '.', ',') }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td><label for=""><input type="radio" name="special" 
                            value="0" 
                            <?php
                                if(!empty(old('special'))){
                                    if(old('special') == 0)
                                        echo 'checked';
                                }else{
                                    echo 'checked';
                                }
                            ?>
                            required> REGULAR</label></td>
                        <td><label for=""><input type="radio" name="special" 
                            value="1" 
                            <?php
                                if(!empty(old('special'))){
                                    if(old('special') == 1)
                                        echo 'checked';
                                }
                            ?>
                            required> SPECIAL</label></td>
                    </tr>
                    <tr>
                        <th>LOCAL / DIRECT LINE</th>
                        <td><input type="text" name="local" class="form-control" value="{{ old('local') }}" required></td>
                    </tr>
                    <tr>
                        <th>LOAN AMOUNT</th>
                        <td>
                            <input type="number" name="loan_amount" class="form-control" 
                                    min="<?php echo $terms->min_loan_amount; ?>" 
                                    max="<?php echo $terms->max_loan_amount; ?>" 
                                    value="
                                    <?php
                                        echo round($terms->max_loan_amount, 0);
                                    ?>
                                    " 
                                    step="500"
                                    required>
                            <span class="help-block">You are qualified up to {{ number_format($terms->max_loan_amount, 2, '.', ',') }}</span>
                        </td>
                    </tr>
                    <tr>
                        <th>PURPOSE</th>
                        <td><input type="text" class="form-control" name="purpose" value="{{ old('purpose') }}" required></td>
                    </tr>
                    <tr>
                        <th>ENDORSED BY</th>
                        <td>
                            <div class="input-group">
                                <input type="text" class="form-control" id="endorsed_by" name="endorsed_by" 
                                value="{{ !empty($endorser) ? $endorser->SIGNATORYID1 : '' }}" readonly>
                                <span class="input-group-btn">
                                    <a class="btn btn-default" data-toggle="modal" data-target="#search_employee" onclick="search_input = 'endorsed'"><i class="fa fa-search"></i> Search</a>
                                </span>    
                            </div>
                            <input type="hidden" class="form-control" id="endorsed_dbname" name="endorsed_dbname" 
                                value="{{ !empty($endorser) ? $endorser->SIGNATORYDB1 : '' }}">
                            <span id="endorsed_name">{{ !empty($endorser) ? $endorser->SIGNATORY1 : '' }}</span>    
                        </td>
                    </tr>
                    <tr>
                        <th>SURETY/CO-BORROWER</th>
                        <td>
                            <div class="input-group">
                                <input type="text" class="form-control" id="guarantor_by" name="guarantor_by" 
                                value="{{ !empty($guarantor) ? $guarantor->SIGNATORYID1 : '' }}" readonly>
                                <span class="input-group-btn">
                                    <a class="btn btn-default" data-toggle="modal" data-target="#search_employee" onclick="search_input = 'guarantor'"><i class="fa fa-search"></i> Search</a>
                                </span>    
                            </div>
                            <input type="hidden" class="form-control" id="guarantor_dbname" name="guarantor_dbname" 
                                value="{{ !empty($guarantor) ? $guarantor->SIGNATORYDB1 : '' }}">
                            <span id="guarantor_name">{{ !empty($guarantor) ? $guarantor->SIGNATORY1 : '' }}</span>    
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" class=""><button type="submit" name="verify" class="btn btn-primary pull-right">Continue</button></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="col-md-6 col-sm-6">
            <table class="table">
                <tbody>
                    <tr>
                        <th colspan="2"><h3>EMPLOYEE INFORMATION</h3></th>
                    </tr>
                    <tr>
                        <th>NAME</th>
                        <td>{{ $employee->FullName }}</td>
                    </tr>
                    <tr>
                        <th>EMPLOYEE NO.</th>
                        <td>{{ $employee->EmpID }}</td>
                    </tr>
                    <tr>
                        <th>POSITION</th>
                        <td>{{ $employee->PositionDesc }}</td>
                    </tr>
                    <tr>
                        <th>DEPARTMENT</th>
                        <td>{{ $employee->DeptDesc }}</td>
                    </tr>
                    <tr>
                        <th>DATE HIRED</th>
                        <td>{{ date('j F Y', strtotime($employee->HireDate)) }}</td>
                    </tr>
                    <tr>
                        <th>REGULARIZATION DATE</th>
                        <td>{{ date('j F Y', strtotime($employee->PermanencyDate)) }}</td>
                    </tr>
                    <tr>
                        <th>YEARS IN THE COMPANY</th>
                        <td>{{ (int)($employee->tenure / 12) }} years, {{ $employee->tenure%12 }} months</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</form>
<script>
    var search_input = '';

    function setApprover(event){
        $('#' + search_input + "_by").val($(event).data('empid'));
        $('#' + search_input + "_name").html($(event).data('name'));
        $('#' + search_input + "_dbname").val($(event).data('db'));

        $('#search_employee').modal('hide');
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
                    "</tr>"
                    );
                });                 
            }
        });
    }
</script>
@endsection
