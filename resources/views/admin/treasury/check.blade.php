<form class="form-horizontal table-responsive" style="font-size: 12px" action="{{ route('treasury.approve') }}" method="post">
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
          @if($loan->check_created_at == null && $loan->status == $utils->getStatusIndex('treasury'))
            <span class="bg-danger help-block">To be filled up only when check is ready for release.</span>
          @endif
          <div class="col-xs-12 col-sm-6 col-md-6">
              
            <div class="form-group">
              <label>CV No</label>
              @if($loan->check_created_at == null && $loan->status == $utils->getStatusIndex('treasury'))
              <input type="text" name="cv_no" class="form-control input-sm" required>
             @else
              <span>{{ $loan->cv_no }}</span>
              @endif
            </div>
            <div class="form-group">
             <label>CV Date</label>
              @if($loan->check_created_at == null && $loan->status == $utils->getStatusIndex('treasury'))
              <input type="date" name="cv_date" class="form-control input-sm" required>
             @else
              <span>
                  {{ date('j F y', strtotime($loan->cv_date)) }}
              </span>
              @endif
            </div>
          </div>
           <div class="col-xs-12 col-sm-6 col-md-6">
            <div class="form-group">
              <label>Check No</label>
              @if($loan->check_created_at == null && $loan->status == $utils->getStatusIndex('treasury'))
              <input type="text" name="check_no" class="form-control input-sm" required>
               @else
              <span>{{ $loan->check_no }}</span>
              @endif
            </div>
             <div class="form-group">
              <label>Check’s Issue Date</label>
              @if($loan->check_created_at == null && $loan->status == $utils->getStatusIndex('treasury'))
              <input type="date" name="check_released" class="form-control input-sm" required>
               @else
              <span>
                  {{ date('j F y', strtotime($loan->check_released)) }}
              </span>
              @endif
            </div>
            <div class="form-group">
              <label>Released At</label>
              @if($loan->check_created_at != null && $loan->status != $utils->getStatusIndex('treasury'))
              <span>
                @if(!empty($loan->released))
                  {{ date('j F y h:i A', strtotime($loan->released)) }}
                @endif
              </span>
              @endif
            </div>
          </div>
        </div>
  </div>
  <div class="clearfix"></div>
  <div class="modal-footer">
    <button type="button" class="btn btn-default btn-sm" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
    @if($loan->check_created_at == null && $loan->status == $utils->getStatusIndex('treasury'))
    <button type="submit" name="approve" class="btn btn-success btn-sm"><i class="fa fa-send"></i> Submit</button>
    @elseif($loan->released == null && $loan->status == $utils->getStatusIndex('release'))
    <button type="submit" name="release" class="btn btn-success btn-sm"><i class="fa fa-send"></i> Release Check</button>
    @endif
  </div>
</form>
