<!DOCTYPE html>
<html>
<head>
	<title>Megaworld eFund</title>
	<meta HTTP-EQUIV="Refresh" CONTENT="10000; URL={{ url('/') }}"/>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <!-- <meta http-equiv="X-UA-Compatible" content="IE=edge"> -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image-png" href="{{ url('/') }}/favicon.ico">
    <link rel="stylesheet" href="{{ url('/') }}/assets/fa/css/font-awesome.min.css">
    <link rel="stylesheet" href="{{ url('/') }}/assets/css/bootstrap.min.css">
    <link href="{{ url('/') }}/assets/css/dataTables.bootstrap.min.css" rel="stylesheet">
	<style type="text/css">
		body, pre{
			background: #d9dbdc;
			font-family: "Arial", Georgia, Serif;
			font-size:14px;
			color: #3c3c3c;
		}
		.wrapper{
			margin: 0 auto;
			background: #d9dbdc;
			border: 0px solid #d9dadb;
			/*padding-bottom:10px;*/
			display: flex;
		    justify-content: center;
		    width: 80%;
		    height: 500px;
		}
		.content{
			align-self: center;
		}
		.icon{
			margin: 0 auto;
			text-align: right;
		}
		.title{
			text-align: left;
			font-size:60px;
		}
		.footer{
			text-align: center;
			font-size:10px;
			color:#5e5e5e; 
		}
	</style>
</head>
<body>
	<div class="wrapper">
		@yield('content')
		<div class="footer navbar navbar-default navbar-fixed-bottom">
			eFund (v1.0)<br>
			Megaworld Corporation | ISM Department | All Rights Reserved © <?php echo date('Y'); ?>
		</div>
	</div>
    <!-- <script src="{{ url('/') }}/assets/js/bootstrap.min.js"></script> -->
</body>
</html>