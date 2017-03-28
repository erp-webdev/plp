<style type="text/css">
	table tbody tr td{
		padding: 5px;
	}
	table thead th{
		padding: 1px;
		text-align: center;
		vertical-align: text-top;
	}
</style>
<div class="table-responsive">
	<table style="width: 100%; font-size: 10px">
		<thead style="border-top: 2px solid #000; border-bottom: 2px solid #000; ">
			<th>Control #</th>
			<th>Employee No.</th>
			<th>Employee Name</th>
			<th>Guarantor</th>
			<th>Date Of <br>Application</th>
			<th>CV NO.</th>
			<th>CV Date</th>
			<th>CHECK NO.</th>
			<th>Date of <br>check release</th>
			<th><br>Principal</th>
			<th>LOAN AMOUNT <br>Interest</th>
			<th>Total</th>
			<th>Payment Terms <br>(no. of mos.)</th>
			<th>Deduction per <br>payroll period</th>
			<th>Start of <br> deduction</th>
			<th>Total amount <br>paid</th>
			<th>Balance</th>
			<th>Remarks</th>
		</thead>
		<tbody style="font-size: 10px;">
			@foreach($loans as $loan)
			<tr>
				<td>{{ $loan->ctrl_no }}</td>
				<td>{{ $loan->EmpID }}</td>
				<td>{{ ucwords(strtolower($loan->FullName)) }}</td>
				<td>{{ ucwords(strtolower($loan->guarantor_FullName)) }}</td>
				<td>{{ $loan->created_at }}</td>
				<td>{{ $loan->cv_no }}</td>
				<td>{{ $loan->cv_date }}</td>
				<td>{{ $loan->check_no }}</td>
				<td>{{ $loan->check_released }}</td>
				<td style="text-align: right">{{ $utils->formatNumber($loan->loan_amount) }}</td>
				<td style="text-align: right">{{ $utils->formatNumber($loan->int_amount) }}</td>
				<td style="text-align: right">{{ $utils->formatNumber($loan->total) }}</td>
				<td>{{ $loan->terms_month }}</td>
				<td style="text-align: right">{{ $utils->formatNumber($loan->deductions) }}</td>
				<td>{{ $loan->start_of_deductions }}</td>
				<td style="text-align: right">{{ $utils->formatNumber($loan->paid_amount) }}</td>
				<td style="text-align: right">{{ $utils->formatNumber($loan->balance) }}</td>
				<td>&nbsp;</td>
			</tr>
			@endforeach
		</tbody>
	</table>
</div>