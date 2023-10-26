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
		<td colspan="6" style="font-weight: bold; padding: 0px">PERSONAL LOAN PROGRAM REPORT</td>	
	</tr>
	<tr>
		<td colspan="6" style=" padding: 0px">List of Resigned Employees with Outstanding Balance</td>	
	</tr>
	<tr>
		<td colspan="6" style="padding: 0px">As of <u>{{ date('j F Y') }}</u></td>	
	</tr>
	<tr>
		<td colspan="6">&nbsp;</td>
	</tr>
	<tr style="border-top: 1px solid black; border-bottom: 1px solid black">
		<th>RESIGNATION DATE</th>
		<th>EMPLOYEE ID NO.:</th>
		<th>EMPLOYEE <br>NAME</th>
		<th>TOTAL <br>AMOUNT</th>
		<th>PAYMENTS <br>MADE</th>
		<th>BALANCE</th>
		<th>CO-BORROWER</th>
	</tr>
	@foreach($data->employees as $employee)
	<tr>
		<td>{{ date('Y-m-d',strtotime($employee->DateResigned))  }}</td>
		<td>{{ $employee->EmpID }}</td>
		<td>{{ $employee->FullName }}</td>
		<td style="text-align: right">{{ number_format($employee->total, 2) }}</td>
		<td>{{ $employee->payments_made }}</td>
		<td style="text-align: right">{{ number_format($employee->balance, 2) }}</td>
		<td style="text-align: left">{{ $employee->guarantor_FullName }}</td>
	</tr>
	@endforeach
	@if(count($data->employees))
	<tr style="border-top: 1px solid; border-bottom-style: double; border-color: black; font-weight: bold">
		<td colspan="2">Total</td>
		<td style="text-align: right">{{ number_format($data->total[0]->total, 2) }}</td>
		<td style="text-align: center;">{{ $data->total[0]->payments_made }}</td>
		<td style="text-align: right">{{ number_format($data->total[0]->balance, 2) }}</td>
		<td>&nbsp;</td>
	</tr>
	@endif
</table>