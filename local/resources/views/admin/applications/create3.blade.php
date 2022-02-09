@extends('admin.layouts.app')
@section('content')
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
                        <td><label for=""><input id="type" name="type" type="radio" value="0" {{ $records == 0 ? 'checked' : '' }} required> NEW</label> </td>
                        <td>
                        <label for=""><input id="type" name="type" type="radio" value="1" {{ $records > 0 ? 'checked' : '' }} required> RE-AVAILMENT</label>    
                            <br> <span>Previous loan amount: {{ number_format($previous_loan, 2, '.', ',') }}</span>
                            <br> <span>Balance: {{ number_format($balance, 2, '.', ',') }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td><label for=""><input type="radio" name="special" value="0" checked required> REGULAR</label></td>
                        <td><label for=""><input type="radio" name="special" value="1" required> SPECIAL</label></td>
                    </tr>
                    <tr>
                        <th>LOCAL / DIRECT LINE</th>
                        <td><input type="text" name="local" class="form-control" required></td>
                    </tr>
                    <tr>
                        <th>LOAN AMOUNT</th>
                        <td>
                            <input type="number" name="loan_amount" class="form-control" 
                                    min="<?php echo $terms->min_loan_amount; ?>" 
                                    max="<?php echo $terms->max_loan_amount; ?>" 
                                    value="{{ $terms->max_loan_amount }}" required>
                            <span class="help-block">You are qualified up to {{ number_format($terms->max_loan_amount, 2, '.', ',') }}</span>
                        </td>
                    </tr>
                    <tr>
                        <th>PURPOSE</th>
                        <td><input type="text" class="form-control" name="purpose" value="" required></td>
                    </tr>
                    <tr>
                        <th>ENDORSED BY</th>
                        <td>
                            <div class="input-group">
                                <input type="text" class="form-control" name="endorsed_by" 
                                value="{{ !empty($endorser) ? $endorser->SIGNATORYID1 : '' }}">
                                <span class="input-group-btn">
                                    <a class="btn btn-default"><i class="fa fa-search"></i> Search</a>
                                </span>    
                            </div>
                            <input type="hidden" class="form-control" name="endorsed_dbname" 
                                value="{{ !empty($endorser) ? $endorser->SIGNATORYDB1 : '' }}">
                            <span id="endorser_name">{{ !empty($endorser) ? $endorser->SIGNATORY1 : '' }}</span>    
                        </td>
                    </tr>
                    <tr>
                        <th>SURETY/CO-BORROWER</th>
                        <td>
                            <div class="input-group">
                                <input type="text" class="form-control" name="guarantor_by" 
                                value="{{ !empty($guarantor) ? $guarantor->SIGNATORYID1 : '' }}">
                                <span class="input-group-btn">
                                    <a class="btn btn-default"><i class="fa fa-search"></i> Search</a>
                                </span>    
                            </div>
                            <input type="hidden" class="form-control" name="guarantor_dbname" 
                                value="{{ !empty($guarantor) ? $guarantor->SIGNATORYDB1 : '' }}">
                            <span id="guarantor_name">{{ !empty($guarantor) ? $guarantor->SIGNATORY1 : '' }}</span>    
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" class=""><button type="button" class="btn btn-primary pull-right">Submit Loan Application</button></td>
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
                        <th>No. of Years in the Company</th>
                        <td>{{ $employee->tenure  }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</form>
@endsection