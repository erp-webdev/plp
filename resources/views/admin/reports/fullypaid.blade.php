<style type="text/css">
	table tr th{
		vertical-align: text-top;
		text-align: center;
		padding: 5px;
		font-size: 11px;
	}
	table tr td{
		word-break: keep-all;
		white-space: nowrap;
		padding: 5px;
		font-size: 11px;
	}
</style>
<table class="table-responsive" style="width: 100%">
	<tr>
		<td colspan="6" style="font-weight: bold; padding: 0px">Personal Loan Program Report</td>	
	</tr>
	<tr>
		<td colspan="6" style=" padding: 0px">Last Amortization Schedule: {{ date('Y-m-d', strtotime($_GET['endDeduction'])) }}
		</td>	
	</tr>
		<tr>
		<td colspan="6">&nbsp;</td>
	</tr>
	<tr style="border-top: 1px solid black; border-bottom: 1px solid black">
		<th>COMPANY CODE:</th>
		<th>EMPLOYEE <br>ID</th>
		<th>EMPLOYEE <br>NAME</th>
		<th>PRINCIPAL LOAN <br>AMOUNT</th>
		<th>INTEREST</th>
		<th>TOTAL LOAN</th>
		<th>LAST PAYMENT <br>DUE DATE</th>
		<th>BALANCE</th>
	</tr>
	@foreach($data->employees as $employee)
	<tr>
		<td>{{ $employee->COMPANY }}</td>
		<td>{{ $employee->EmpID }}</td>
		<td>{{ $employee->FullName }}</td>
		<td style="text-align: right">{{ number_format($employee->PrincipalLoan, 2) }}</td>
		<td style="text-align: center;">{{ $employee->interest }}</td>
		<td style="text-align: right">{{ number_format($employee->TotalLoan, 2) }}</td>
		<td style="text-align: center;">{{ date('Y-m-d', strtotime($_GET['endDeduction'])) }}</td>
		<td style="text-align: right">{{ number_format($employee->Balance, 2) }}</td>
	</tr>
	@endforeach
</table>