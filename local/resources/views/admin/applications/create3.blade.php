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
                List of Valid Endorsers or Coborrower/surety
                {{-- Search Employee
                <span class="">
                    <div class="input-group">
                        <input type="text" class="form-control" id="search" placeholder="Search..." autocomplete="off" class="form-control">
                        <span class="input-group-btn">
                            <button id="butotn" onclick="search()" class="btn btn-default"><i class="fa fa-search"></i> Search</button>
                        </span>
                    </div>
                </span> --}}
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
                        @if(!empty(trim($approvers->SIGNATORYID1)))
                        <tr onclick="setApprover(this)" data-empid="{{ $approvers->SIGNATORYID1 }}" data-db="{{ $approvers->SIGNATORYDB1 }}" data-name="{{ $approvers->SIGNATORY1 }}">
                            <td> {{ $approvers->SIGNATORYID1 }} </td> 
                            <td> {{ $approvers->SIGNATORY1 }} </td>
                        </tr>
                        @endif

                        @if(!empty(trim($approvers->SIGNATORYID2)))
                        <tr onclick="setApprover(this)" data-empid="{{ $approvers->SIGNATORYID2 }}" data-db="{{ $approvers->SIGNATORYDB2 }}" data-name="{{ $approvers->SIGNATORY2 }}">
                            <td> {{ $approvers->SIGNATORYID2 }} </td> 
                            <td> {{ $approvers->SIGNATORY2 }} </td>
                        </tr>
                        @endif

                        @if(!empty(trim($approvers->SIGNATORYID3)))
                        <tr onclick="setApprover(this)" data-empid="{{ $approvers->SIGNATORYID3 }}" data-db="{{ $approvers->SIGNATORYDB3 }}" data-name="{{ $approvers->SIGNATORY3 }}">
                            <td> {{ $approvers->SIGNATORYID3 }} </td> 
                            <td> {{ $approvers->SIGNATORY3 }} </td>
                        </tr>
                        @endif

                        @if(!empty(trim($approvers->SIGNATORYID4)))
                        <tr onclick="setApprover(this)" data-empid="{{ $approvers->SIGNATORYID4 }}" data-db="{{ $approvers->SIGNATORYDB4 }}" data-name="{{ $approvers->SIGNATORY4 }}">
                            <td> {{ $approvers->SIGNATORYID4 }} </td> 
                            <td> {{ $approvers->SIGNATORY4 }} </td>
                        </tr>
                        @endif

                        @if(!empty(trim($approvers->SIGNATORYID5)))
                        <tr onclick="setApprover(this)" data-empid="{{ $approvers->SIGNATORYID5 }}" data-db="{{ $approvers->SIGNATORYDB5 }}" data-name="{{ $approvers->SIGNATORY5 }}">
                            <td> {{ $approvers->SIGNATORYID5 }} </td> 
                            <td> {{ $approvers->SIGNATORY5 }} </td>
                        </tr>
                        @endif

                        @if(!empty(trim($approvers->SIGNATORYID6)))
                        <tr onclick="setApprover(this)" data-empid="{{ $approvers->SIGNATORYID6 }}" data-db="{{ $approvers->SIGNATORYDB6 }}" data-name="{{ $approvers->SIGNATORY6 }}">
                            <td> {{ $approvers->SIGNATORYID6 }} </td> 
                            <td> {{ $approvers->SIGNATORY6 }} </td>
                        </tr>
                        @endif

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<form  id="loanform" class="form form-horizontal" action="{{ route('applications.store2') }}" method="post" >
<input type="hidden" name="id" value="{{ $loan->id or '' }} ">
<input type="hidden" name="_token" value="{{ csrf_token() }}">
    <div class="container-fluid">
        <div class="col-md-6 col-sm-6"> 
            <table class="table">
                <tbody>
                    <tr>
                        <td colspan="2"><h3>APPLICATION FORM
                            <span style="font-size: 14px; font-weight: normal">
                                @if(isset($loan))
                                    {!! $utils->formatStatus($loan->status) !!}
                                @endif
                            </span>   
                            @if(isset($loan))
                                <h4><?php if(isset($loan)) if($loan->ctrl_no != '0000') echo 'Ctrl No: '. $loan->ctrl_no; ?></h4>
                            @endif 
                        </h3></td>
                    </tr>
                    <tr>
                        <td><label for="">
                                <input type="hidden" name="type" value="<?php 
                                        if(isset($loan->type)){
                                            if($loan->type == 0) echo 0;
                                            else echo 1;
                                        }else{
                                            if(!empty(old('type'))){
                                                if(old('type') == 0) echo 0;
                                                else echo 1;
                                            } else {
                                                if($records == 0 ) echo 0;
                                                else echo 1;
                                            }
                                        }
                                            
                                    ?>">
                                <input id="type" name="type"  type="radio" 
                                    value="0" 
                                    <?php 
                                        if(isset($loan->type)){
                                            if($loan->type == 0)
                                                echo 'checked';
                                        }else{
                                            if(!empty(old('type'))){
                                                if(old('type') == 0)
                                                    echo 'checked';
                                            } elseif($records == 0 )
                                                echo 'checked';
                                        }
                                            
                                    ?>
                                    disabled required>  NEW</label> </td>
                        <td>
                        <label for="">
                            <input id="type" name="type" type="radio" 
                            value="1" 
                            <?php 
                                if(isset($loan->type)){
                                    if($loan->type == 1)
                                        echo 'checked';
                                }else{
                                    if(!empty(old('type'))){
                                        if(old('type') == 1)
                                            echo 'checked';
                                    } elseif($records != 0 )
                                        echo 'checked';
                                }
                                    
                            ?> 
                            disabled required> RE-AVAILMENT</label>    
                            <br> <span>Previous loan amount: {{ number_format($previous_loan, 2, '.', ',') }}</span>
                            <br> <span>Balance: {{ number_format($balance, 2, '.', ',') }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td><label for=""><input type="radio" id="special" name="special" 
                            value="0" 
                            <?php
                                
                                if(isset($loan->special)){
                                    if($loan->special == 0)
                                        echo 'checked';
                                }elseif(!empty(old('special'))){
                                    if(old('special') == 0)
                                        echo 'checked';
                                }else{
                                    echo 'checked';
                                }
                            ?>
                            required> REGULAR</label></td>
                        <td><label for=""><input type="radio" id="special" name="special" 
                            value="1" <?php
                                if(isset($loan->special)){
                                    if($loan->special == 1)
                                        echo 'checked';
                                }if(!empty(old('special'))){
                                    if(old('special') == 1)
                                        echo 'checked';
                                } ?> required> SPECIAL</label></td>
                    </tr>
                    <tr>
                        <th>LOCAL / DIRECT LINE</th>
                        <td><input type="text" name="local" class="form-control" value="{{ $loan->local_dir_line or old('local') }}" required></td>
                    </tr>
                    <tr>
                        <th>LOAN AMOUNT</th>
                        <td>
                            <input type="number" name="loan_amount" class="form-control" 
                                    min="<?php echo $terms->min_loan_amount; ?>" 
                                    max="{{ empty($allowed_above_max) ? isset($loan->special) ? $special->max_loan_amount :  $terms->max_loan_amount : '' }}"

                                    value="<?php 
                                        if(!empty($loan->loan_amount)){
                                            echo $loan->loan_amount;
                                        }elseif(!empty(old('loan_amount')))
                                            echo old('loan_amount');
                                        else
                                            echo round($terms->max_loan_amount, 0); ?>" 
                                    step="500"
                                    required>
                            @if(!$allowed_above_max)
                            <span class="help-block">You are qualified up to 
                                @if(isset($loan->special))
                                    @if($loan->special) 
                                    {{ number_format($special->max_loan_amount, 2, '.', ',') }}
                                    @else
                                    {{ number_format($terms->max_loan_amount, 2, '.', ',') }}
                                    @endif
                                @else
                                    {{ number_format($terms->max_loan_amount, 2, '.', ',') }}
                                @endif                                
                            </span>
                            @else
                            <span class="help-block">
                                You may apply for loan above your maximum limit until {{ date('j F Y', strtotime($allowed_above_max->ExpiredAt))}}
                            </span>
                            @endif

                        </td>
                    </tr>
                    <tr>
                        <th>Terms (Months)</th>
                        <td>
                            <input type="number" name="terms" class="form-control" min="1" max="{{ isset($loan->id) ? $loan->special == 1 ? $months_special : $months : $months}}" value="<?php
                                    if(isset($loan->id))
                                        echo $loan->terms_month;
                                    else{
                                        if(old('terms')==! null)
                                            echo old('terms');
                                        else
                                            echo $months;
                                    }
                                ?>">
                        </td>
                    </tr>

                    @if(isset($loan->loan_amount))
                    <tr>
                        <th>TOTAL LOAN AMOUNT</th>
                        <td><strong style="font-size: 16px">{{ number_format($loan->total, 2, '.', ',')}}</strong>
                            at <strong>{{ $loan->interest }}% </strong> interest in <strong>{{ $loan->terms_month }}</strong> months <br>
                            
                        </td>
                    </tr>
                    <tr>
                        <th>PAYROLL DEDUCTION*</th>
                        <td>
                            <strong>{{ $loan->terms_month * 2 }}</strong> terms x <strong>{{ number_format($loan->deductions, 2, '.', ',') }} </strong> per pay day <br>
                            <i>*Terms applied are subject to changes by the eFund Custodian, thus the *no. of payments and *payroll deductions may change as well.</i>
                        </td>
                    </tr>
                    @endif
                    <tr>
                        <th>PURPOSE</th>
                        <td><input type="text" class="form-control" name="purpose" value="{{ $loan->purpose or old('purpose') }}" required></td>
                    </tr>
                    <tr>
                        <th>ENDORSED BY</th>
                        <td>
                            <div class="input-group">
                                <input type="text" class="form-control" id="endorsed_by" name="endorsed_by" 
                                value="{{ $loan->endorser_EmpID or old('endorsed_by') }}" readonly>
                                <span class="input-group-btn">
                                    <a class="btn btn-default" data-toggle="modal" data-target="#search_employee" onclick="search_input = 'endorsed'"><i class="fa fa-search"></i> Search</a>
                                </span>    
                            </div>
                            <input type="hidden" class="form-control" id="endorsed_dbname" name="endorsed_dbname" 
                                value="{{ $loan->endorser_dbname or old('endorsed_dbname') }}">
                            <input type="hidden" id="endorsed_FullName" name="endorsed_FullName" value="{{ $loan->endorser_FullName or old('endorsed_FullName') }}">    
                            <span id="endorsed_name">{{ $loan->endorser_FullName or old('endorsed_FullName') }}</span>    
                        </td>
                    </tr>
                    <tr>
                        <th>SURETY/CO-BORROWER</th>
                        <td>
                            <div class="input-group">
                                <input type="text" class="form-control" id="guarantor_by" name="guarantor_by" 
                                value="{{ $loan->guarantor_EmpID or old('guarantor_by') }}" readonly>
                                <span class="input-group-btn">
                                    <a class="btn btn-default" data-toggle="modal" data-target="#search_employee" onclick="search_input = 'guarantor'"><i class="fa fa-search"></i> Search</a>
                                </span>    
                            </div>
                            <input type="hidden" class="form-control" id="guarantor_dbname" name="guarantor_dbname" value="{{ $loan->guarantor_dbname or old('guarantor_dbname') }}">
                            <input type="hidden" id="guarantor_FullName" name="guarantor_FullName" value="{{ $loan->guarantor_FullName or old('guarantor_FullName') }}">
                            <span id="guarantor_name">{{ $loan->guarantor_FullName or old('guarantor_FullName') }}</span>    
                        </td>
                    </tr>
                    <tr>
                        @if(isset($loan->id))
                        <td colspan="2" class="">
                            
                            <button type="button" class="btn btn-default" onclick="editForm()"><i class="fa fa-pencil"></i> Edit</button>

                            <button type="submit" class="btn btn-primary hidden" name="verify" data-title="Verify and Continue Application!" data-content="Are you sure you want to verify and continue application?" data-form="#loanform">Continue</button>

                            <button type="submit" name="submit" class="btn btn-primary pull-right " data-validate="validate_standard" data-title="Submit Application!" data-content="Are you sure you want to submit application?"  data-form="#loanform">Submit</button>
                        
                        </td>
                        @else
                        <td colspan="2" class=""><button type="submit" name="verify" class="btn btn-primary pull-right " data-title="Verify and Continue Application!" data-content="Are you sure you want to verify and continue application?" data-form="#loanform">Continue</button></td>
                        @endif
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
        $('#' + search_input + "_FullName").val($(event).data('name'));

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
@section('scripts')
@if(empty($allowed_above_max))
<script>
    $(document).on('click', '#special', function(event){
        if($(this).val() == 0){
            // regular
            $('input[name="loan_amount"]')
                .attr({
                    'max' : {{ $terms->max_loan_amount }},
                    'value' : {{ $terms->max_loan_amount }}
                });

            $('input[name="loan_amount"]').parent('td').find('.help-block')
                .html('You are qualified up to ' +  '{{ number_format($terms->max_loan_amount, 2, '.', ',') }}')

            $('input[name="terms"]')
            .attr({
                'max' : {{ $months }},
                'value' : {{ $months }}
            });

        }else{
            // special
            $('input[name="loan_amount"]')
                .attr({
                    'max' : {{ $special->max_loan_amount }},
                    'value' : {{ $special->max_loan_amount }}
                });

            $('input[name="loan_amount"]').parent('td').find('.help-block')
                .html('You are qualified up to ' +  '{{ number_format($special->max_loan_amount, 2, '.', ',') }}')
            
            $('input[name="terms"]')
            .attr({
                'max' : {{ $months_special }},
                'value' : {{ $months_special }}
            });
        }
    });
</script>
@endif
@endsection