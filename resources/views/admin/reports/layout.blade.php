<!DOCTYPE html>
<html>
<head>
	<title>Megaworld eFund Report</title>
	<meta author="MW eFund System">
	<meta created="{{ date('m/d/y H:i:s') }}">
	<style type="text/css">
		@media screen {
		  div.footer {
		    /*display: none;*/
		  }
		}
		@media print {
		  div.footer {
		    position: fixed;
		    bottom: 0;
		  }
		  
		}

	</style>
</head>
<body>

<h4>{{ $report->title }}</h4>
<div id="content" style="page-break-after: always">
	{!! $report->html !!}
</div>
<div class="footer" style="page-break-after: always">
	<table style="width: 100%">
		<tr style="font-size: 9px">
			<td>Printed at: {{ date('m/d/Y H:i:s') }}</td>
			<td style="text-align: right">Printed by: ({{ Auth::user()->id }}) {{ Auth::user()->name }} </td>
			<td><span id="pageFooter"></span></td>
		</tr>
	</table>
</div>
</body>
</html>