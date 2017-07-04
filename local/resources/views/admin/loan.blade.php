  <div class="modal fade" tabindex="-1" role="dialog" id="loan">
    <div class="modal-dialog modal-sm" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h5 class="modal-title">Loan Application 
              <label class="label label-primary" ng-show="loan.approved_at == null" style="font-size: 10px">For Approval</label>
          </h5>
        </div>
        <div class="modal-body">
          <form class="form-horizontal" style="font-size: 12px" action="@{{ approvalUrl }}" method="post">
              <input type="hidden" name="_token" value="{{ csrf_token() }}">
              <input type="number" name="id" value="@{{ loan.id }}" style="display: none">
              <div class="form-group">
                <label class="col-xs-12 col-sm-5 col-md-5"><span>Reference #</span></label>
                <div class="col-xs-12 col-sm-7 col-md-7"><span ng-bind="loan.refno"></span></div>
              </div>  
              <div class="form-group">
                <label class="col-xs-12 col-sm-5 col-md-5"><span>Ctrl No</span></label>
                <div class="col-xs-12 col-sm-7 col-md-7"><span ng-bind="loan.ctrl_no"></span></div>
              </div>
              <div class="form-group">
                <label class="col-xs-12 col-sm-5 col-md-5"><span>Employee</span></label>
                <div class="col-xs-12 col-sm-7 col-md-7"><span ng-bind="loan.FullName"></span></div>
              </div>
              <div class="form-group">
                <label class="col-xs-12 col-sm-5 col-md-5"><span>Terms</span></label>
                <div class="col-xs-12 col-sm-7 col-md-7"><span ng-bind="loan.terms_month"></span> month(s)</div>
              </div>
              <div class="form-group">
                <label class="col-xs-12 col-sm-5 col-md-5"><span>Loan Amount</span></label>
                <div class="col-xs-12 col-sm-7 col-md-7" style="text-align: right"><span ng-bind="loan.loan_amount | currency: 'Php '"></span></div>
              </div>
              <div class="form-group">
                <label class="col-xs-12 col-sm-5 col-md-5"><span>Interest Amount</span></label>
                <div  class="col-xs-12 col-sm-7 col-md-7" style="text-align: right"><span ng-bind="loan.int_amount | currency: 'Php '" ></span></div>
              </div>
              <div class="form-group">
                <label class="col-xs-12 col-sm-5 col-md-5"><span>Total Amount</span></label>
                <div class="col-xs-12 col-sm-7 col-md-7"><div style="border-top-style: double; text-align: right" ><span style="font-weight: bold"  ng-bind="loan.total | currency: 'Php '"></span></div></div>
              </div>
              <div class="form-group" ng-show="loan.signed_at == '--' && isGuarantor">
                <label class="col-xs-12 col-sm-5 col-md-5"><span>Guaranteed Amount</span></label>
                <div class="col-xs-12 col-sm-7 col-md-7"><input type="number" name="guaranteed_amount" class="form-control input-sm"></div>
              </div>
              <div class="form-group" ng-show="isGuarantor == false">
                <label class="col-xs-12 col-sm-5 col-md-5"><span>Guarantor</span></label>
                <div class="col-xs-12 col-sm-7 col-md-7"><span ng-bind="loan.guarantor_EmpID | currency: 'Php '"></span></div>
              </div>
              <div class="form-group" ng-show="loan.guaranteed_amount != null && loan.status > 1">
                <label class="col-xs-12 col-sm-5 col-md-5"><span>Guaranteed Amount</span></label>
                <div class="col-xs-12 col-sm-7 col-md-7" style="text-align: right" ><span ng-bind="loan.guaranteed_amount | currency: 'Php '"></span></div>
              </div>
              <div class="form-group">
                <label class="col-xs-12 col-sm-5 col-md-5"><span>Signed At</span></label>
                <div class="col-xs-12 col-sm-7 col-md-7"><span ng-bind="loan.signed_at | date: 'medium'"></span></div>
              </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default btn-sm" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
          <button type="submit" name="deny" class="btn btn-danger btn-sm" ng-show="loan.signed_at == '--'"><i class="fa fa-thumbs-down"></i> Deny</button>
          <button type="submit" name="approve" class="btn btn-success btn-sm"  ng-show="loan.signed_at == '--'"><i class="fa fa-thumbs-up"></i> Approve</button>
        </div>
        </form>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->
