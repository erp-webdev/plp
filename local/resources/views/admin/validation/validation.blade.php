<form class="form-horizontal table-responsive" style="font-size: 12px" action="{{ route('validation.approve') }}" method="post">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <input type="number" name="id" value="{{ $loan->id }}" style="display: none">
    <div class="modal-header">
      <div class="col-xs-12 col-sm-8 col-md-8">
        <h4>Employees Fund Loan <small>{!! $utils->formatApprovalStatus($loan->endorser_status, $loan->status, $utils->getStatusIndex('endorser')) !!}</small></h4>
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
                <td>{{ $loan->local_dir_line }}</td>
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
            @if($loan->guarantor_id != NULL && $loan->status > $utils->getStatusIndex('guarantor'))
            <tr>
              <td>
                <td class="l">Guarantor</td>
                <td>{{ $loan->guarantor_FullName }}</td>
              </td>
              <td>
                <td class="l">Reference #</td>
                <td>{{ $loan->guarantor_refno }}</td>
              </td>
            </tr>
            <tr>
              <td>
                <td class="l">Guaranteed Amount</td>
                <td >Php {{ number_format($loan->guaranteed_amount, 2, '.', ',') }}</td>
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
      <button type="button" class="btn btn-default btn-sm" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
      @if($loan->status == $utils->getStatusIndex('nurse'))
      {{-- <button type="submit" name="deny" class="btn btn-danger btn-sm" onsubmit="startLoading()"><i class="fa fa-thumbs-down"></i> Invalid</button> --}}
      <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#denial">Invalid</button>
      <button type="submit" name="approve" class="btn btn-success btn-sm" onsubmit="startLoading()"><i class="fa fa-thumbs-up"></i> Valid</button>
      @endif
    </div>
  </form>
  