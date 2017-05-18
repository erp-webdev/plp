
	<div class="modal-header">
	  <div class="col-xs-12 col-sm-6 col-md-6">
	  	<h4>Loan Amount Verification 
	  		<span style="font-size: 14px; font-weight: normal">{!! $utils->formatPayrollStatus($loan->payroll_verified) !!}</span></h4>
	  </div>
	  <div class="col-xs-12 col-sm-6 col-md-6">
	  	<p class="pull-right"><small>Ctrl No: </small><strong >{{ $loan->ctrl_no }}</strong></p>
	  </div>
	</div>
	<div class="modal-body">
	  <style type="text/css">
	  	.l{
	  		font-weight: bold;
	  	}
	  </style>
	  <form class="form-horizontal table-responsive" style="font-size: 12px" action="{{ route('payroll.verify') }}" method="post">
			  	<input type="hidden" name="_token" value="{{ csrf_token() }}">
			  	<input type="number" name="id" value="{{ $loan->id }}" style="display: none">
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
			      	<tr>
			      		<td>
			      			<td class="l">Endorsed By</td>
			      			<td>{{ $loan->endorser_FullName }}</td>
			      		</td>
			      		<td>
			      			<td class="l">Reference #</td>
			      			<td>{{ $loan->endorser_refno }}</td>
			      		</td>
			      	</tr>
			      	@if($loan->guarantor_id != NULL)
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
			      	
			      </table>
			       <hr>
	        	@if(!empty($loan->cv_date))
	        	<table class="table-condensed">
	        	<tr>
	        		<td>
	        			<td class="l">CV No</td>
	        			<td>{{ $loan->cv_no }}</td>
	        		</td>
	        		<td>
	        			<td class="l">CV Date</td>
	        			<td>
	        				@if(!empty($loan->cv_date))
	        				{{ date('j F Y', strtotime($loan->cv_date)) }}
	        				@endif
	        			</td>
	        		</td>
	        		<td>
		      			<td class="l">Prepared By</td>
		      			<td>&nbsp;</td>
		      		</td>
	        	</tr>
	        	<tr>
	        		<td>
	        			<td class="l">Check No</td>
	        			<td>{{ $loan->check_no }}</td>
	        		</td>
	        		<td>
	        			<td class="l">Check Release</td>
	        			<td>
	        				@if(!empty($loan->check_released))
	        				{{ date('j F Y', strtotime($loan->check_released)) }}
	        				@endif
	        			</td>
		      			<td>
		      				<td>{{ utf8_encode(($utils->getUserInfo($loan->prepared_by)->name)) }}</td>
		      				<td>&nbsp;</td>
		      			</td>
	        		</td>
	        	</tr>
	        	</table>
	        	@endif
	        	<br>
		      	<table class="table table-condensed">
		      		<tr>
			      		<td>
			      			<td class="l">Terms</td>
			      			<td>{{ $loan->terms_month }} (mos)</td>
			      		</td>
			      		<td>
			      			<td class="l">Loan Amount</td>
			      			<td style="text-align: right">Php {{ number_format($loan->loan_amount, 2, '.', ',') }}</td>
			      		</td>
			      		
			      	</tr>	
			      	<tr>
			      		<td>
			      			<td class="l">Deduction/payroll</td>
			      			<td >Php {{ number_format($loan->deductions, 2, '.', ',') }}</td>
			      		</td>
			      		<td>
			      			<td class="l">Interest ({{ $loan->interest }}%)</td>
			      			<td style="text-align: right; ">Php {{ number_format($loan->int_amount, 2, '.', ',') }}</td>
			      		</td>
			      	</tr>	
			      	<tr>
			      		<td>
			      			<td class="l">Start of Deduction</td>
			      			<td> @if(!empty($loan->start_of_deductions))
			      				 	{{ date('j F Y', strtotime($loan->start_of_deductions)) }}
			      				 @endif
			      			</td>
			      		</td>
			      		<td>
			      			<td class="l">Total</td>
			      			<td style="font-weight: bold; border-top-style: double;text-align: right; font-size: 15px">Php {{ number_format($loan->total, 2, '.', ',') }}</td>
			      		</td>
			      	</tr>	
		      	</table>
		     	@if($loan->status == $utils->getStatusIndex('payroll'))
		     	<div class="clearfix"></div>
				<button type="submit" name="verify" class="btn btn-success btn-sm pull-right" onsubmit="startLoading()"><i class="fa fa-check-square-o"></i> Verify</button>
				<button type="submit" name="deny" class="btn btn-danger btn-sm pull-right" onsubmit="startLoading()"><i class="fa fa-thumbs-down"></i> Deny</button>
			    @endif
			</form>
	</div>
	<div class="modal-footer">
		
	   <button type="button" class="btn btn-default btn-sm" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
	</div>

