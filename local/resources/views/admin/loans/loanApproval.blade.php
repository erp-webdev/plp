
	<div class="modal-header">
	  <div class="col-xs-12 col-sm-6 col-md-6">
	  	<h4>Employees Fund Loan 
	  		<span style="font-size: 14px; font-weight: normal">{!! $utils->formatStatus($loan->status) !!}</span>
	  		@if($loan->status == $utils->getStatusIndex('officer'))
		  		@if($loan->payroll_verified == 1)
		  		<span style="font-size: 14px; font-weight: normal"><label class="label label-success">Verified by Payroll</label></span>
		  		@elseif($loan->payroll_verified == 0)
		  		<span style="font-size: 14px; font-weight: normal"><label class="label label-danger">Denied by Payroll</label></span>
		  		@endif
		  	@endif
	  		</h4>
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

	   <!-- Nav tabs -->
	  <ul class="nav nav-tabs" role="tablist">
	    <li role="presentation" class="active"><a href="#loaninfo" aria-controls="loan" role="tab" data-toggle="tab">Loan</a></li>
	    <li role="presentation">
	    	<a href="#scheds" aria-controls="scheds" role="tab" data-toggle="tab">Schedule of Deductions</a>
	    </li>
	    		
	  </ul>

	  <!-- Tab panes -->
	  <div class="tab-content">
	    <div role="tabpanel" class="tab-pane active" id="loaninfo">
	    	<form class="form-horizontal table-responsive" style="font-size: 12px" action="{{ route('loan.approve') }}" method="post">
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
	        	@if(!empty($loan->cv_date))
			       <hr>
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
	        				{{ $loan->cv_date }}
	        				@endif
	        			</td>
	        		</td>
	        		<td>
		      			<td class="l">Prepared By</td>
		      			<td>{{ utf8_encode(($utils->getUserInfo($loan->prepared_by)->name)) }}</td>
		      		</td>
	        	</tr>
	        	<tr>
	        		<td>
	        			<td class="l">Check No</td>
	        			<td>{{ $loan->check_no }}</td>
	        		</td>
	        		<td>
	        			<td class="l">Check’s Issue Date</td>
	        			<td>
	        				@if(!empty($loan->check_released))
	        				{{ $loan->check_released }}
	        				@endif
	        			</td>
		      			<td>
		      				<td class="l">Released At</td>
		      				<td>
		      				@if(!empty($loan->released))
	        					{{ date('j F Y h:i A', strtotime($loan->released)) }}
	        				@endif
	        				</td>
		      			</td>
	        		</td>
	        	</tr>
	        	</table>
	        	@endif
	        	<br>
		      	<table class="table table-condensed">
		      		<tr>
			      		<td>
			      			<td class="l">Previous Balance</td>
			      			<td>
			      			
			      			Php {{ $utils->formatNumber($balance) }}
			      			
			      			</td>
			      		</td>
			      		<td>
			      			<td colspan="2"></td>
			      		</td>
			      	</tr>	
		      		<tr>
			      		<td>
			      			<td class="l">Terms</td>
			      			<td>
			      			@if($loan->status == $utils->getStatusIndex('officer'))
			      				<input type="number" name="terms" min="1" max="{{ $loan->special == 1 ? 24 : $months }}" value="{{ $loan->terms_month }}" class="form-control input-sm">
			      			@else
			      				{{ $loan->terms_month }} (mos)
			      			@endif
			      			</td>
			      		</td>
			      		<td>
			      			<td class="l">Loan Amount</td>
			      			<td style="text-align: right">
			      			@if($loan->status == $utils->getStatusIndex('officer'))
			      				<input type="number" name="loan_amount" value="{{ $loan->loan_amount }}" class="form-control input-sm" max="{{ $loan->loan_amount
			      				 }}">
			      			@else
			      				Php {{ number_format($loan->loan_amount, 2, '.', ',') }}
			      			@endif
			      			</td>
			      		</td>
			      		
			      	</tr>	
			      	<tr>
			      		<td>
			      			<td class="l">Deduction/payroll</td>
			      			<td >
			      				Php {{ number_format($loan->deductions, 2, '.', ',') }}
			      			</td>
			      		</td>
			      		<td>
			      			<td class="l">Interest ({{ $loan->interest }}%)</td>
			      			<td style="text-align: right; ">Php {{ number_format($loan->int_amount, 2, '.', ',') }}</td>
			      		</td>
			      	</tr>	
			      	<tr>
			      		<td>
			      			<td class="l">Start of Deduction</td>
			      			<td> {{ $loan->start_of_deductions or '' }}		</td>
			      		</td>
			      		<td>
			      			<td class="l">Total</td>
			      			<td style="font-weight: bold; border-top-style: double;text-align: right; font-size: 15px">Php {{ number_format($loan->total, 2, '.', ',') }}</td>
			      		</td>
			      	</tr>	
			      	@if($loan->status >= $utils->getStatusIndex('officer'))
			      	<tr>
			      		<td colspan="8">
			      			<textarea name="remarks" class="form-control" placeholder="Remarks" >{{ trim($loan->remarks) }}</textarea>
			      		</td>
			      	</tr>
			      	@endif
		      	</table>
		     	@permission(['officer'])
				@if($loan->status == $utils->getStatusIndex('officer'))
				<div class="clearfix"></div>
				<button type="submit" name="deny" class="btn btn-danger btn-sm pull-right" onsubmit="startLoading()"><i class="fa fa-thumbs-down"></i> Deny</button>
				<button type="submit" name="approve" class="btn btn-success btn-sm pull-right" onsubmit="startLoading()"><i class="fa fa-thumbs-up"></i> Approve</button>
				<button type="statusubmit" name="calculate" class="btn btn-default btn-sm pull-right" onsubmit="startLoading()"><i class="fa fa-calculator"></i> Calculate</button>
				@endif
				@endif
			</form>
	    </div>
	    <div role="tabpanel" class="tab-pane table-responsive" id="scheds">
	    	<form action="{{ route('loan.deduction') }}" method="post">
	    		<input type="hidden" name="_token" value="{{ csrf_token() }}">
	    		<input type="hidden" name="id" value="{{ $loan->id }}">
		    	<fieldset <?php if($loan->status == $utils->getStatusIndex('paid')) echo 'disabled'; ?>>
	    		<table class="table table-condensed table-hover table-striped" id="dtable">
		    		<thead>
		    			<th>Date</th>
		    			<th>Payments AR #</th>
		    			<th>Amount</th>
		    			<th>Balance</th>
		    		</thead>
		    		<tbody id="dd">
		    			<?php $totalAmount = 0; $totalBalance = 0; ?>
		    			@foreach($deductions as $deduction)
		    			<tr >
		    				<td >{{ $deduction->date }}</td>
		    				<td style="width: 25px"	>
	    						<input type="hidden" name="eFundData_id[]" value="{{ $deduction->eFundData_id }}">
		    					<input class="form-control input-sm" type="hidden" name="id[]" value="{{ $deduction->id }}">
		    					<input  class="form-control input-sm" type="text" name="ar_no[]" value="{{ $deduction->ar_no }}" >
		    				</td>
		    				<td style="width: 25px"><input class="form-control input-sm" type="number" step="any" name="amount[]" value="{{ round($deduction->amount, 2) }}" ></td>
		    				<td style="width: 25px"><input class="form-control input-sm" type="number" step="any" name="balance[]" value="{{ number_format($deduction->balance, 2, '.', '') }}" disabled ></td>
		    			</tr>
		    			<?php 
		    				$totalAmount += $deduction->amount;  
		    					if(!empty(trim($deduction->ar_no)))
		    						$totalBalance = $deduction->balance;
		    			?>
		    			@endforeach
		    		</tbody>
		    		<tbody>
		    			<tr>
		    				<td><strong>Total</strong></td>
		    				<td>&nbsp;</td>
		    				<td style="font-weight: bold">Php {{ number_format($totalAmount, 2, '.', ',') }}</td>
		    				<td style="font-weight: bold">Php {{ number_format($totalBalance, 2, '.', ',') }}</td>
		    			</tr>
		    		</tbody>
		    	</table>
		    	</fieldset>
				@permission(['custodian'])
		    	@if($loan->status != $utils->getStatusIndex('paid') && count($deductions) > 0)
		    	<button type="button" id="new_deduction" class="btn btn-sm btn-default"><i class="fa fa-plus"></i> New Deduction</button>
		    	<button type="submit" name="submit" class="btn btn-sm btn-success pull-right" onsubmit="startLoading()"><i class="fa fa-save"></i> Save</button>
		    	<a class="btn btn-sm btn-warning pull-right" onclick="confirm_recalculation('{{ route('deductions.recal', $loan->id) }}')"><i class="fa fa-save"></i> Recalculate Deductions</a>
		    	@endif
		    	@endpermission
	    	</form>
	    	
	    </div>
	  </div>

	    
	</div>
	<div class="modal-footer">
		@permission(['custodian'])
		@if($loan->status == $utils->getStatusIndex('inc'))
	    <a type="button" class="btn btn-success btn-sm" href="{{ route('loan.complete', $loan->id) }}" onsubmit="startLoading()">Paid</a>
	    @endif
	    @endpermission
	   <a type="button" class="btn btn-default btn-sm" href="{{ route('loan.print', $loan->id) }}" target="_blank"><i class="fa fa-print"></i> Print</a>
	   <button type="button" class="btn btn-default btn-sm" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
	</div>
