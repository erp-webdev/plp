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
	.table-hover thead{
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
				<thead>
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
				</thead>
				<tbody>
					<?php $ctr = 0;?>
					@foreach($ledgers as $ledger)
					<tr class="<?php if($utils->formatLedger($ledger->eFundData_id, $ctr, $ledger->eFundData_id) != '') echo'new' ?>" >
						<td>{{ $utils->formatLedger($ledger->created_at, $ctr, $ledger->eFundData_id) }}</td>
						<td>{{ $utils->formatLedger($ledger->ctrl_no, $ctr, $ledger->eFundData_id) }}</td>
						<td>{{ $utils->formatLedger($ledger->cv_no, $ctr, $ledger->eFundData_id) }}</td>
						<td>{{ $utils->formatLedger($ledger->cv_date, $ctr, $ledger->eFundData_id) }}</td>
						<td>{{ $utils->formatLedger($ledger->check_released, $ctr, $ledger->eFundData_id) }}</td>
						<td style="text-align: right">{{ $utils->formatLedger($ledger->loan_amount, $ctr, $ledger->eFundData_id) }}</td>
						<td style="text-align: right">{{ $utils->formatLedger($ledger->loan_amount_interest, $ctr, $ledger->eFundData_id) }}</td>
						<td>{{ $utils->formatLedger($ledger->total, $ctr, $ledger->eFundData_id) }}</td>
						<td>{{ $utils->formatLedger($ledger->terms_month, $ctr, $ledger->eFundData_id) }}</td>
						<td style="text-align: right">{{ $utils->formatLedger($ledger->deductions, $ctr, $ledger->eFundData_id) }}</td>
						<td>{{ $ledger->date }}</td>
						<td>{{ $ledger->ar_no }}</td>
						<td style="text-align: right">{{ $ledger->amount }}</td>
						<td style="text-align: right">
						@if($showBalance)
							{{ $ledger->balance }}
						@else
							@if($ctr == 0)
								{{ $utils->formatNumber($balance) }}
							@endif
						@endif
						</td>
					</tr>
					<?php $ctr++; ?>
					@endforeach
				</tbody>
			</table>