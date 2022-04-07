<style type="text/css">
	.thead{
		border-top: 2px solid #000; 
		border-bottom: 2px solid #000;
	}
	th{
		text-align: center;
	}
	table{
		border-collapse: collapse;
	}
	td{
		white-space: nowrap;
		word-break: keep-all;
	}
</style>
<div class="table-responsive">
	<table style="width: 100%;" class="table-hover">
		<tbody style="font-size: 12px; " >
			<tr class="thead" style="font-size: 12px; font-weight: bold">
				<th>PLP <br>Control No.</th>
				<th>EMPLOYEE <br>ID NO.</th>
				<th>EMPLOYEE <br>NAME</th>
				<th>Date of <br>Check Release</th>
				<th>TOTAL <br>AMOUNT</th>
				<th>TOTAL NO. <br>OF DEDUCTIONS</th>
				<th>Deduction <br>per payday</th>
				<th>START <br>OF DEDUCTION</th>
			</tr>
			@foreach($loans as $loan)
			<tr>
				<td style="text-align: left; ">{{ $loan->ctrl_no }}</td>
				<td style="text-align: left">{{ $loan->EmpID }}</td>
				<td style="text-align: left">{{ ucwords(strtolower($loan->FullName)) }}</td>
				<td style="text-align: center">{{ empty($loan->released) ? '' : date('m/d/Y', strtotime($loan->released)) }}</td>
				<td style="text-align: right">{{ $utils->formatNumber($loan->total) }}</td>
				<td style="text-align: center">{{ $loan->terms_month * 2 }}</td>
				<td style="text-align: right">{{ $utils->formatNumber($loan->deductions) }}</td>
				<td style="text-align: center">
				@if(is_object($loan->start_of_deductions))
					{{ date_format($loan->start_of_deductions, 'Y/m/d') }}
				@else
					{{ $loan->start_of_deductions }}
				@endif
				</td>
			</tr>
			@endforeach
		</tbody>
	</table>
	
</div>