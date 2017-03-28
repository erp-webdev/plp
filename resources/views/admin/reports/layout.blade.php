<!DOCTYPE html>
<html>
<head>
	<title>Megaworld eFund Report</title>
	<meta author="MW eFund System">
	<meta created="{{ date('m/d/y H:i:s') }}">
	<style type="text/css">
		
	</style>
</head>
<body>

<h5>{{ $report->title }}</h5>
<div>
	{!! $report->html !!}
</div>

</body>
</html>