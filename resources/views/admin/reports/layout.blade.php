<!DOCTYPE html>
<html>
<head>
	<title>Megaworld eFund Report</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<meta author="Megaworld eFund System">
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
<div id="content">
	{!! $report->html !!}
</div>
<div class="footer">
	<table style="width: 100%">
		<tr style="font-size: 9px">
			<td>Printed at: {{ date('m/d/Y H:i:s') }}</td>
			<td style="text-align: right">Printed by: ({{ Auth::user()->id }}) {{ Auth::user()->name }} </td>
			<td><span id="pageFooter"></span></td>
		</tr>
	</table>
</div>
<script type="text/javascript">
	window.print()
</script>
</body>
</html>