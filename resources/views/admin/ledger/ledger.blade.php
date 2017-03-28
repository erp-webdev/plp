<div class="table-responsive">
			<table class="table table-condensed table-hover">
				<thead style="text-align: center">
					<th style="text-align: center">Date of <br>Availment</th>
					<th style="text-align: center">EF <br>Control No.</th>
					<th style="text-align: center">CV No.</th>
					<th style="text-align: center">CV Date</th>
					<th style="text-align: center">Date of <br>Check Release</th>
					<th style="text-align: center"><br>principal</th>
					<th style="text-align: center">LOAN AMOUNT <br>interest</th>
					<th style="text-align: center"><br>total</th>
					<th style="text-align: center">PAYMENT <br>TERM</th>
					<th style="text-align: center">Deduction <br>per payday</th>
					<th style="text-align: center"><br>date</th>
					<th style="text-align: center">Payments <br>AR#</th>
					<th style="text-align: center"><br>amount</th>
					<th style="text-align: center">BALANCE</th>
				</thead>
				<tbody>
					<?php $ctr = 0;?>
					@foreach($ledgers as $ledger)
					<tr style="text-align: center; <?php if($utils->index == 0) echo 'border-top-width: 2px; border-top-color: #000; border-top-style: solid'; else echo 'border-top-width: 0px'; ?>">
						<td>{{ $utils->formatLedger($ledger->created_at, $ctr) }}</td>
						<td>{{ $utils->formatLedger($ledger->ctrl_no, $ctr) }}</td>
						<td>{{ $utils->formatLedger($ledger->cv_no, $ctr) }}</td>
						<td>{{ $utils->formatLedger($ledger->cv_date, $ctr) }}</td>
						<td>{{ $utils->formatLedger($ledger->check_released, $ctr) }}</td>
						<td>{{ $utils->formatLedger($ledger->loan_amount, $ctr) }}</td>
						<td>{{ $utils->formatLedger($ledger->loan_amount_interest, $ctr) }}</td>
						<td>{{ $utils->formatLedger($ledger->total, $ctr) }}</td>
						<td>{{ $utils->formatLedger($ledger->terms_month, $ctr) }}</td>
						<td>{{ $utils->formatLedger($ledger->deductions, $ctr) }}</td>
						<td>{{ $ledger->date }}</td>
						<td>{{ $ledger->ar_no }}</td>
						<td style="text-align: right">{{ $ledger->amount }}</td>
						<td>
						@if($showBalance)
							{{ $ledger->balance }}
						@else
							@if($ledger->status == 7)
							{{ $utils->formatLedger('Paid', $ctr) }}
							@else
							{{ $utils->formatLedger('Inc', $ctr) }}
							@endif
						@endif
						</td>
					</tr>
					<?php $ctr++; ?>
					@endforeach
				</tbody>
			</table>