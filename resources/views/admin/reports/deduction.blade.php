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
		<td colspan="6" style="font-weight: bold; padding: 0px">EMPLOYEE'S FUND REPORT</td>	
	</tr>
	<tr>
		<td colspan="6" style=" padding: 0px">List of Employees with Outstanding but without Deduction
		<br>Payroll Period: {{ $data->payroll }}
		</td>	
	</tr>
		<tr>
		<td colspan="6">&nbsp;</td>
	</tr>
	<tr style="border-top: 1px solid black; border-bottom: 1px solid black">
		<th>EMPLOYEE ID NO.:</th>
		<th>EMPLOYEE <br>NAME</th>
		<th>TOTAL <br>AMOUNT</th>
		<th>PAYMENTS <br>MADE</th>
		<th>BALANCE</th>
		<th>CO-BORROWER</th>
	</tr>
	@foreach($data->employees as $employee)
	<tr>
		<td>{{ $employee->EmpID }}</td>
		<td>{{ $employee->FullName }}</td>
		<td style="text-align: right">{{ number_format($employee->total, 2) }}</td>
		<td style="text-align: center;">{{ $employee->payments_made }}</td>
		<td style="text-align: right">{{ number_format($employee->balance, 2) }}</td>
		<td style="text-align: left">{{ $employee->guarantor_FullName }}</td>
	</tr>
	@endforeach
	<tr style="border-top: 1px solid; border-bottom-style: double; border-color: black; font-weight: bold">
		<td colspan="2">Total</td>
		<td style="text-align: right">{{ number_format($data->total[0]->total, 2) }}</td>
		<td style="text-align: center;">{{ $data->total[0]->payments_made }}</td>
		<td style="text-align: right">{{ number_format($data->total[0]->balance, 2) }}</td>
		<td>&nbsp;</td>
	</tr>
</table>