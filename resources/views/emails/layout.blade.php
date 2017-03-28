<!DOCTYPE html>
<html>
<head>
	<title>{{ config('preferences.app') }}</title>
	<style type="text/css">
		body, pre{
			background: #f8f8f8;
			font-family: "Arial", Georgia, Serif;
			font-size:14px;
			color: #2b2b2b;
		}
		.wrapper{
			width: 800px;
			margin: 0 auto;
			background: #f8f8f8;
			border: 1px solid #d9dadb;
			padding-bottom:10px;
		}
		.title{
			text-align: left;
			font-size:15px;
			padding: 10px;
			background: #004489;
			color: #ffffff;
		}
		.content{
			text-align: left;
			padding:10px
		}
		.logo{
			display: block;
   	 		margin: auto;
   	 		width: 200;
   	 		padding: 10px;
		}
		.footer{
			text-align: center;
			font-size:10px;
			color:#5e5e5e; 
		}
		.btn {
		  background: #3498db;
		  background-image: -webkit-linear-gradient(top, #3498db, #2980b9);
		  background-image: -moz-linear-gradient(top, #3498db, #2980b9);
		  background-image: -ms-linear-gradient(top, #3498db, #2980b9);
		  background-image: -o-linear-gradient(top, #3498db, #2980b9);
		  background-image: linear-gradient(to bottom, #3498db, #2980b9);
		  -webkit-border-radius: 8;
		  -moz-border-radius: 8;
		  border-radius: 8px;
		  font-family: Arial;
		  color: #ffffff;
		  font-size: 16px;
		  padding: 10px 20px 10px 20px;
		  text-decoration: none;
		}

		.btn:hover {
		  background: #3cb0fd;
		  background-image: -webkit-linear-gradient(top, #3cb0fd, #3498db);
		  background-image: -moz-linear-gradient(top, #3cb0fd, #3498db);
		  background-image: -ms-linear-gradient(top, #3cb0fd, #3498db);
		  background-image: -o-linear-gradient(top, #3cb0fd, #3498db);
		  background-image: linear-gradient(to bottom, #3cb0fd, #3498db);
		  text-decoration: none;
		}
	</style>
</head>
<body>
	<div class="wrapper">
		@yield('content')
		
		<div class="footer">
This is a system generated email. <br>
			{{ config('preferences.app') }} v.{{ config('preferences.version') }}<br>
			Megaworld Corporation | ISM Department | All Rights Reserved © {{ date('Y') }}
		</div>
	</div>
</body>
</html>