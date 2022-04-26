<link rel="stylesheet" type="text/css" href="{{ url('/assets/css/daterangepicker.css') }}">
<form class="form-horizontal table-responsive" style="font-size: 12px" action="{{ route('treasury.approve') }}" method="post" ng-app="ApprovalApp" ng-controller="ApprovalCtrl">
  <input type="hidden" name="_token" value="{{ csrf_token() }}">
  <input type="number" name="id" value="{{ $loan->id }}" style="display: none">
  <div class="modal-header">
    <div class="col-xs-12 col-sm-8 col-md-8">
      <h4>Employees Fund Loan </h4>
      {!! $utils->formatTreasuryStatus($loan->status) !!}
    </div>
    <div class="col-xs-12 col-sm-4 col-md-4">
      <p class="pull-right"><small>Ctrl No: </small><strong >{{ $loan->ctrl_no }}</strong></p>
    </div>
  </div>
  <div class="modal-body">
    <style type="text/css">
      .l{
        font-weight: bold;
      }
    </style>
        <table class="table-condensed">
          <tr>
            <td>
              <td class="l">Application Type</td>
              <td>{{ $utils->getType($loan->type) }}</td>
            </td>
            <td>
              <td class="l">Date</td>
              <td>{{ $loan->created_at }}</td>
            </td>
          </tr>
          <tr>
            <td>
              <td class="l">Employee Name</td>
              <td>{{ utf8_encode($loan->FullName) }}</td>
            </td>
            <td>
              <td class="l">Employee ID</td>
              <td>{{ $loan->EmpID }}</td>
            </td>
          </tr>
          <tr>
            <td>
              <td class="l">Date Hired</td>
              <td>{{ date('j F Y', strtotime($loan->HireDate)) }}</td>
            </td>
            <td>
              <td class="l">Local/Direct Line</td>
              <td>{{ $loan->local_dir_line }}</td>
            </td>
          </tr> 
          <tr>
            <td>
              <td class="l">Regularization</td>
              <td>{{ date('j F Y', strtotime($loan->PermanencyDate)) }}</td>
            </td>
            <td>
              <td class="l">Department</td>
              <td>{{ $loan->DeptDesc }}</td>
            </td>
          </tr>
          @if($loan->guarantor_status != NULL)
          <tr>
            <td>
              <td class="l">Guaranteed Amount</td>
              <td style="text-align: right">
              Php {{ number_format($loan->guaranteed_amount, 2, '.', ',') }}</td>
            </td>
          </tr>
          @endif
          <tr>
            <td>
              <td class="l">Loan Amount</td>
              <td style="text-align: right">Php {{ number_format($loan->loan_amount, 2, '.', ',') }}</td>
            </td>
           
          </tr> 
          <tr>
            <td>
              <td class="l">Interest ({{ $loan->interest }}%)</td>
              <td style="text-align: right">Php {{ number_format($loan->int_amount, 2, '.', ',') }}</td>
            </td>
            <td>
              <td class="l">Terms</td>
              <td>{{ $loan->terms_month }} (mos)</td>
            </td>
          </tr> 
          <tr>
            <td>
              <td class="l">Total</td>
              <td style="font-weight: bold; border-top-style: double;text-align: right">Php {{ number_format($loan->total, 2, '.', ',') }}</td>
            </td>
          </tr> 
        </table>
        <hr>
        <div>
          @if(empty($loan->cv_no) && $loan->status == $utils->getStatusIndex('treasury'))
            <div class="col-xs-12 col-sm-12 col-md-12" id="cvBtnContainer">
                <label >Check No.</label>
                <input type="text" id="check_no" class="form-control" style="width: 182px" onchange="checkCVn()">
              <button type="button" class="btn btn-default" id="cvBtn" onclick="genCV({{ $loan->id }})" disabled>Generate Check Voucher</button>
              <span class="loading-cv" style="display: none;">
                <i class="fa fa-spin fa-spinner"></i> Generating check voucher... <br>
              </span>
              <span class="checkvoucher"></span>
              <br>
              <br>
            </div>
          @else
              <div class="col-xs-12 col-sm-12 col-md-12">
                Check Voucher No.: <strong>{{ $loan->cv_no }}</strong> <br>
                Check Voucher Date: <strong>{{ $loan->cv_date }}</strong><br>
                Check No.: <strong>{{ $loan->check_no }}</strong> <br>
                @if(empty($loan->check_released))
                <span class="bg-danger help-block">To be filled up only when check is already signed and ready for release.</span>
                 <!--  <div class="col-xs-12 col-sm-6 col-md-6">
                    Check No.: <input type="text" name="check_no" class="form-control input-sm"> 
                  </div> -->
                  <div class="col-xs-12 col-sm-6 col-md-6">
                    Check Date: <input name="check_released" type="date" id="datep" class="datepicker-range form-control input-sm " type="text" placeholder="mm/dd/yyyy" required>
                  </div>
                @else
                  Check Date: <strong>{{ $loan->check_released }}</strong>
                @endif
              </div>
          @endif
  </div>
  <div class="clearfix"></div>
  <div class="modal-footer">

    {{-- <button type="submit" name="cancel" class="btn btn-danger btn-sm" onsubmit="startLoading(); validate('cancel')"><i class="fa fa-ban"></i> Cancel</button> --}}
    @if(!empty($loan->cv_no) && $loan->status == $utils->getStatusIndex('treasury'))
    <button type="submit" name="approve" class="btn btn-success btn-sm" onsubmit="startLoading(); validate('submit')"><i class="fa fa-send"></i> Submit</button>
    @elseif($loan->released == null && $loan->status == $utils->getStatusIndex('release'))

    <div class="input-group">
        <input type="date" class="form-control" name="check_release_date" required>
        <div class="input-group-btn">
                <button type="button" name="release" class="btn btn-success btnSave" data-title="Confirm Release of Check" data-content="You are about to release the check of the loan application of {{ utf8_encode($loan->FullName) }}."><i class="fa fa-send"></i> Release Check</button>
        </div>
    </div>
    <br>
    @endif
    @if(!empty($loan->cv_no))
      <a href="{{ route('treasury.voucher.print', $loan->id) }}" class="btn btn-default btn-sm" target="_blank"><i class="fa fa-print"></i> Print Check Voucher</a>
    @endif
    <button type="button" class="btn btn-default btn-sm" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>


  </div>
</form>
<script type="text/javascript">
   $(function() {
        $( "input.datepicker" ).datepicker({ dateFormat: 'yy-mm-dd' });
    });

   function validate($action){
      if($action == 'submit')
        if($('input[name="check_no"]').val() == '' || $('input[name="check_released"]').val() == '')
          alert('Check No and Check Date are required');

      return confirm('Do you really want to ' + $action + ' the form?');
   }
</script>
<script type="text/javascript" src="{{ url('/assets/js/daterangepicker.js') }}"></script>
