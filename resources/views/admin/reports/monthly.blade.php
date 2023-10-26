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
		<td colspan="6" style="font-weight: bold">PERSONAL LOAN PROGRAM REPORT</td>	
	</tr>
	<tr>
		<td colspan="6">As of <u>{{ date('j F Y', strtotime($args['created_at'])) }}</u></td>	
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
	<?php 
		$previous_year = [];
		if(count($data) > 0)
			// Calculate previous year records
			$previous_year = (object)['total_count' => 0, 'principal' => 0, 'interest' => 0, 'total' => 0, 'paid' => 0, 'balance' => 0];

			foreach ($data as $record) {
				if( $record->app_year < date('Y', strtotime($args['created_at']))){
					$previous_year->total_count += 1;
					$previous_year->principal += $record->principal;
					$previous_year->interest += $record->interest;
					$previous_year->total += $record->total;
					$previous_year->balance += $record->balance;
				}
			}

	 ?>	

	@if(count($previous_year) && isset($args['created_at']))
	<tr>
		<td>As of December 31, {{ date('Y', strtotime($args['created_at'])) - 1 }}</td>
		<td style="text-align: center;">{{ $previous_year->total_count }}</td>
		<td style="text-align: right">{{ number_format($previous_year->principal,2) }}</td>
		<td style="text-align: right">{{ number_format($previous_year->interest,2) }}</td>
		<td style="text-align: right">{{ number_format($previous_year->total,2) }}</td>
		<td style="text-align: right">{{ number_format($previous_year->balance,2) }}</td>
	</tr>
	<tr>
		<td style="text-align: center">Y{{ date('Y', strtotime($args['created_at'])) }}</td>
		<td colspan="5">&nbsp;</td>
	</tr>
	@endif

	@for($i = 1; $i <= 12; $i++)
		@if(date('n', strtotime($args['created_at'])) < $i)
			<?php break; ?>
		@endif
		<?php $with_records = false; ?>
		@foreach($data as $row)
			@if($row->app_year != date('Y', strtotime($args['created_at'])))
				<?php continue; ?>
			@endif

			@if($row->app_month == $i)
				<?php $with_records = true; ?>
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
			@endif
		@endforeach

		@if(!$with_records)
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
	@endfor
	<?php 
	
	$total = $previous_year;

	foreach ($data as $record) {
		if($record->app_year == date('Y', strtotime($args['created_at']))){
			$total->total_count += $record->total_count;
			$total->principal += $record->principal;
			$total->interest += $record->interest;
			$total->total += $record->total;
			$total->balance += $record->balance;
		}
	}
	 ?>

	<tr style="border-top: 1px solid; border-bottom-style: double; border-color: black; font-weight: bold">
		<td>Total</td>
		<td style="text-align: center;">{{ $total->total_count or 0 }}</td>
		<td style="text-align: right">{{ isset($total->principal) ? number_format($total->principal, 2) : 0.00 }}</td>
		<td style="text-align: right">{{ isset($total->interest) ? number_format($total->interest, 2) : 0.00 }}</td>
		<td style="text-align: right">{{ isset($total->total) ? number_format($total->total, 2) : 0.00 }}</td>
		<td style="text-align: right">{{ isset($total->balance) ? number_format($total->balance, 2) : 0.00 }}</td>
	</tr>
</table>