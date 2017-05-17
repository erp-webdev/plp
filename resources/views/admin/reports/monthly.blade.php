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
		<td colspan="6" style="font-weight: bold">EMPLOYEE'S FUND REPORT</td>	
	</tr>
	<tr>
		<td colspan="6">As of <u>{{ date('j F Y') }}</u></td>	
	</tr>
	<tr>
		<td colspan="6">&nbsp;</td>
	</tr>
	<tr style="border-top: 1px solid black; border-bottom: 1px solid black">
		<th>Month</th>
		<th>total <br>accounts</th>
		<th>principal <br>amount</th>
		<th>interest</th>
		<th>total <br>amount</th>
		<th>outstanding <br>balance</th>
	</tr>
	@if(count($data->year_prev) > 0)
	<tr>
		<td>As of December 31, {{ date('Y') - 1 }}</td>
		<td style="text-align: center;">{{ $data->year_prev[0]->total_count }}</td>
		<td style="text-align: right">{{ number_format($data->year_prev[0]->principal,2) }}</td>
		<td style="text-align: right">{{ number_format($data->year_prev[0]->interest,2) }}</td>
		<td style="text-align: right">{{ number_format($data->year_prev[0]->total,2) }}</td>
		<td style="text-align: right">{{ number_format($data->year_prev[0]->balance,2) }}</td>
	</tr>
	@endif
	<tr>
		<td style="text-align: center">Y{{ date('Y') }}</td>
		<td colspan="5">&nbsp;</td>
	</tr>
	@for($i = 1; $i <= 12; $i++)
		@if($i <= date('n'))
			@foreach($data->year_cur as $row)
				@if($row->app_month == $i)
				<tr>
					<td>
					<?php 
					$monthNum  = $i;
					$dateObj   = DateTime::createFromFormat('!m', $monthNum);
					$monthName = $dateObj->format('F'); // March
					echo $monthName; 
					?>
					</td>
					<td style="text-align: center;">{{ $row->total_count }}</td>
					<td style="text-align: right">{{ number_format($row->principal, 2) }}</td>
					<td style="text-align: right">{{ number_format($row->interest, 2) }}</td>
					<td style="text-align: right">{{ number_format($row->total, 2) }}</td>
					<td style="text-align: right">{{ number_format($row->balance, 2) }}</td>
				</tr>
				@else
				<tr>
					<td>
					<?php 
					$monthNum  = $i;
					$dateObj   = DateTime::createFromFormat('!m', $monthNum);
					$monthName = $dateObj->format('F'); // March
					echo $monthName; 
					?>
					</td>
					<td style="text-align: center;">0</td>
					<td style="text-align: right">0.00</td>
					<td style="text-align: right">0.00</td>
					<td style="text-align: right">0.00</td>
					<td style="text-align: right">0.00</td>
				</tr>
				@endif
			@endforeach
		@endif 	
	@endfor
	<tr style="border-top: 1px solid; border-bottom-style: double; border-color: black; font-weight: bold">
		<td>Total</td>
		<td style="text-align: center;">{{ $data->total[0]->total_count }}</td>
		<td style="text-align: right">{{ number_format($data->total[0]->principal, 2) }}</td>
		<td style="text-align: right">{{ number_format($data->total[0]->interest, 2) }}</td>
		<td style="text-align: right">{{ number_format($data->total[0]->total, 2) }}</td>
		<td style="text-align: right">{{ number_format($data->total[0]->balance, 2) }}</td>
	</tr>
</table>