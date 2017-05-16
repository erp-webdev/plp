<style type="text/css">
	body{
		font-family: "Arial";
	}
	.table-hover{
		font-size: 12px;
		font-family: "Arial";
		border-collapse: collapse;
		width: 100%;
		border-bottom: 2px solid black;
	}
	tbody{
		font-size: 12px;
	}
	.table-hover tr th{
		border-top: 2px solid black;
		border-bottom: 2px solid black;
	}
	th{
		text-align: center
	}
	tr{
		text-align: left
	}
	.new{
		border-top: 2px solid black;
	}
	td{
		padding: 5px;
	}
	@media screen {
		  div.footer {
		    /*display: none;*/
		  }
		}
		@media print {
		  div.footer {
		    position: fixed;
		    bottom: 0;
		  }
		  
		}
</style>
<b>EMPLOYEE'S LEDGER:</b>
<table class="table-condensed">
	<tr>
		<td>EMPLOYEE NAME</td>
		<td>: {{ $employee->FullName }}</td>
	</tr>
	<tr>
		<td>EMPLOYEE ID NO</td>
		<td>: {{ $employee->EmpID }}</td>
	</tr>
	<tr>
		<td>DATE HIRED</td>
		<td>: {{ $employee->HireDate }}</td>
	</tr>
	<tr>
		<td>POSITION/RANK</td>
		<td>: {{ $employee->PositionDesc }} / {{ $employee->RankDesc }}</td>
	</tr>
</table>
<div class="table-responsive">
			<table class="table-hover">
				<tbody>
					<tr>
						<th >Date of <br>Availment</th>
						<th>EF <br>Control No.</th>
						<th>CV No.</th>
						<th>CV Date</th>
						<th>Date of <br>Check Release</th>
						<th><br>principal</th>
						<th>LOAN AMOUNT <br>interest</th>
						<th><br>total</th>
						<th>PAYMENT <br>TERM</th>
						<th>Deduction <br>per payday</th>
						<th><br>date</th>
						<th>Payments <br>AR#</th>
						<th><br>amount</th>
						<th>BALANCE</th>
					</tr>
					<?php $prevId = ''; $ctr = 0;?>
					@foreach($ledgers as $ledger)
						<tr <?php if($ledger->eFundData_id != $prevId) echo 'style="border-top: 2px solid black"'; ?>>
							<td style="text-align: left"><?php if($ledger->eFundData_id != $prevId) echo $ledger->created_at  ?></td>
							<td style="text-align: left"><?php if($ledger->eFundData_id != $prevId) echo $ledger->ctrl_no; ?></td>
							<td style="text-align: left"><?php if($ledger->eFundData_id != $prevId) echo  $ledger->cv_no; ?></td>
							<td style="text-align: left"><?php if($ledger->eFundData_id != $prevId) echo  $ledger->cv_date; ?></td>
							<td style="text-align: left"><?php if($ledger->eFundData_id != $prevId) echo  $ledger->check_released; ?></td>
							<td style="text-align: left"><?php if($ledger->eFundData_id != $prevId) echo  $ledger->loan_amount; ?></td>
							<td style="text-align: right"><?php if($ledger->eFundData_id != $prevId) echo  number_format($ledger->loan_amount_interest,2); ?></td>
							<td><?php  if($ledger->eFundData_id != $prevId) echo  number_format($ledger->total,2); ?></td>
							<td><?php if($ledger->eFundData_id != $prevId) echo  $ledger->terms_month; ?></td>
							<td style="text-align: right"><?php if($ledger->eFundData_id != $prevId) echo  number_format($ledger->deductions,2); ?></td>
							<td>{{ $ledger->date }}</td>
							<td>{{ $ledger->ar_no }}</td>
							<td style="text-align: right">{{ number_format($ledger->amount,2) }}</td>
							<td style="text-align: right">
							<?php 
								if($showBalance){
									echo number_format($ledger->balance,2);
								}else{
									if($ledger->eFundData_id != $prevId){
										$amount_paid = 0;

										foreach ($ledgers as $x) {
											if($x->eFundData_id == $ledger->eFundData_id)
												$amount_paid += $x->amount;
										}
										
										echo round($ledger->total - $amount_paid,2);
									}
								}

							 ?>
							</td>
						</tr>
						<?php $prevId = $ledger->eFundData_id; ?>
					@endforeach


					
				</tbody>
			</table>
<div class="footer">
	<table style="width: 100%">
		<tr style="font-size: 9px">
			<td>Printed at: {{ date('m/d/Y H:i:s') }}</td>
			<td style="text-align: right">Printed by: ({{ Auth::user()->id }}) {{ Auth::user()->name }} </td>
			<td><span id="pageFooter"></span></td>
		</tr>
	</table>
</div>