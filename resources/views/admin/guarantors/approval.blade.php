<form class="form-horizontal table-responsive" style="font-size: 12px" action="{{ route('guarantors.approve') }}" method="post">
  <input type="hidden" name="_token" value="{{ csrf_token() }}">
  <input type="number" name="id" value="{{ $loan->id }}" style="display: none">
  <div class="modal-header">
    <div class="col-xs-12 col-sm-8 col-md-8">
      <h4>Employees Fund Loan <small>{!! $utils->formatApprovalStatus($loan->guarantor_status, $loan->status, $utils->getStatusIndex('guarantor')) !!}</small></h4>
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
              <td>{{ $loan->FullName }}</td>
            </td>
            <td>
              <td class="l">Employee ID</td>
              <td>{{ $loan->EmpID }}</td>
            </td>
          </tr>
          <tr>
            <td>
              <td class="l">Date Hired</td>
              <td>{{ $loan->HireDate }}</td>
            </td>
            <td>
              <td class="l">Local/Direct Line</td>
              <td>{{ $loan->loc_dir_line }}</td>
            </td>
          </tr> 
          <tr>
            <td>
              <td class="l">Regularization</td>
              <td>{{ $loan->PermanencyDate }}</td>
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
  </div>
  <div class="modal-footer">
    <p style="text-align: justify;">I hereby consent to act as surety of the applicant and agree to pay the abovenamed applicant's loan up to the amount of 
    @if($loan->guarantor_status == null && $loan->status == $utils->getStatusIndex('guarantor'))
      Php <input type="number" name="guaranteed_amount" style="width: 100px" min="0" value="{{ $loan->loan_amount }}" required>
    @else
      <span>Php {{ number_format($loan->guaranteed_amount, 2, '.', ',') }}</span>
    @endif
    . As surety, I hereby authorize Megaworld Corporation to deduct from the salary, allowances, bonuses and other benefits without any need of prior notice, any outstanding balance of the Applicant's loan including interest and penalty until full payment thereof in case of applican's default, resignation, termination, dismissal or failure to pay amortization relating to the loan.</p>
    <button type="button" class="btn btn-default btn-sm" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
    @if($loan->guarantor_status == null && $loan->status == $utils->getStatusIndex('guarantor'))
    <button type="submit" name="deny" class="btn btn-danger btn-sm"><i class="fa fa-thumbs-down"></i> I Disagree</button>
    <button type="submit" name="approve" class="btn btn-success"><i class="fa fa-thumbs-up"></i> I Agree</button>
    @endif
  </div>
</form>
