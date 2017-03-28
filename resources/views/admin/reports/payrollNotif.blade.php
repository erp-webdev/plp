
<div class="table-responsive">
	<table style="width: 100%;">
		<thead style="border-top: 2px solid #000; border-bottom: 2px solid #000">
			<th style="text-align: center">EF <br>Control No.</th>
			<th style="text-align: center">EMPLOYEE <br>ID NO.</th>
			<th style="text-align: center">EMPLOYEE <br>NAME</th>
			<th style="text-align: center">Date of <br>Check Release</th>
			<th style="text-align: center">TOTAL <br>AMOUNT</th>
			<th style="text-align: center">TOTAL NO. <br>OF DEDUCTIONS</th>
			<th style="text-align: center">Deduction <br>per payday</th>
			<th style="text-align: center">START <br>OF DEDUCTION</th>
		</thead>
		<tbody style="font-size: 12px;">
			@foreach($loans as $loan)
			<tr>
				<td style="text-align: left; ">{{ $loan->ctrl_no }}</td>
				<td style="text-align: left">{{ $loan->EmpID }}</td>
				<td style="text-align: left">{{ ucwords(strtolower($loan->FullName)) }}</td>
				<td style="text-align: center">{{ $loan->check_released }}</td>
				<td style="text-align: right">{{ $utils->formatNumber($loan->total) }}</td>
				<td style="text-align: center">{{ $loan->terms_month * 2 }}</td>
				<td style="text-align: right">{{ $utils->formatNumber($loan->deductions) }}</td>
				<td style="text-align: center">{{ $loan->start_of_deductions }}</td>
			</tr>
			@endforeach
		</tbody>
	</table>
	
</div>