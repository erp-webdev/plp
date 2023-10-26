<!DOCTYPE html>
<html>
<head>
	<title>Employee's Fund Release Checks</title>
	<meta charset="utf-8">
	<meta author="Megaworld Employee's Fund">
	<style type="text/css">
		tr:nth-child(even){background-color: #f2f2f2}

		html{
			font-size: 14px;
			font-family: 'Arial';
			color: #373737;
		}
		#pageFooter{
			font-size: 10px;
		}

	</style>
</head>
<body>
<div id="content">
	<h3>Megaworld Employee's Fund</h3>
	<br>
	<br>
	<table style="width: 100%; border-collapse: collapse; display: table">
	<thead style="border-top: 0px solid #373737; border-bottom: 1px solid #373737;">
		<th style="padding: 5px; text-align: left;" >Name</th>
		<th style="padding: 5px; text-align: center;" >CV #</th>
		<th style="padding: 5px; text-align: center;" >Check #</th>
		<th style="padding: 5px; text-align: right;" >Amount</th>
		<th style="padding: 5px; text-align: center;" >Released</th>
	</thead>
	<tbody>
		@foreach($loans as $loan)
		<tr>
			<td style="padding: 5px">{{ $loan->FullName }}</td>
			<td style="padding: 5px; text-align: center;">{{ $loan->cv_no }}</td>
			<td style="padding: 5px; text-align: center;">{{ $loan->check_no }}</td>
			<td style="padding: 5px; text-align: right">{{ number_format($loan->loan_amount, 2, '.', ',') }}</td>
			<td style="padding: 5px; text-align: center;">{{ isset($loan->check_released) ? date('m/d/Y', strtotime($loan->check_released)) : '' }}</td>
		</tr>
		@endforeach
	</tbody>
	</table>
	
</div>
<footer id="pageFooter" >
		<p>Printed at: {{ date('m/d/Y H:i:s') }}</p>
		<p>Printed by: ({{ Auth::user()->id }}) {{ Auth::user()->name }} </p>
	</footer>
<script type="text/javascript">
	window.print()
</script>
</body>
</html>