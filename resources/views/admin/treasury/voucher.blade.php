<!DOCTYPE html>
<html>
<head>
	<title>Check Voucher</title>
	<style type="text/css">
		.wrapper{
			border: .02in solid black;
			height: 11in; 
			width: 8.5in;
			font-size: 14px;
			font-family: 'Arial';
			font-weight: bold;
		}

		.inner-wrapper{
			border: .02in solid black;
			height: 10.46in;
			width: 7.94in;
			margin: .25in;
		}

		.content{
			padding: 25px;
		}

		.title{
			text-transform: capitalize;
			text-align: center;
			font-style: bold;
		}
		.sub-title{
			text-align: center;
		}

		.cv_no{
			text-align: right;
		}

		.cv_no span{
			text-align: left;
		}

		.check_no{
			text-align: right;
		} 
		.check_no span{
			text-align: right;
		}

		.loan_details{
			margin-top: 50px;
		}

		.label{
			width: 100px;
		}

		.signatories{
			width: 7.94in;
			bottom: 1in;
			position: absolute;
		}

		.received{
			float: left;
			position: relative;
			width: 4in;
			padding-bottom: 100px;
		}


		.signature{
			width: 3in;
			border-top: 2px solid black;
		}

		.received-date{
			float: left;
			position: relative;
			width: 3in; 
		}

		/*.date{
			margin-left: .5in;
			border-top: 2px solid black;
		}*/

		table tr{
			height: 50px;
		}

		table tr td span{
			padding-left: 25px;
			text-transform: capitalize;
		}


		@media print{
			@page {
				size: 8.5in 11in;
				margin: .5in .5in .5in .5in;
			}
			
		}
		
	</style>
</head>
<body>
	<div class="wrapper">
		<div class="inner-wrapper">
			<div class="content">
				<div class="title">
					<span>EMPLOYEE'S FUND</span>
				</div>
				<div class="sub-title">
					<span>Check Voucher</span>
				</div>
				
				<div class="cv_no">CV # <span>{{ $loan->cv_no }}</span></div>
				<div class="check_no"><p>Check #<span>{{ $loan->check_no }}</span></p></div>

				<div class="loan_details">
					<table>
						<tr>
							<td class="label">Payee </td>
							<td>: <span>{{ $loan->FullName }}</span></td>
						</tr>
						<tr>
							<td class="label">Date</td>
							<td>: <span>{{ date('F j, Y') }}</span></td>
						</tr>
						<tr>
							<td class="label">Amount</td>
							<td>: <span>{{ number_format($loan->loan_amount, 2) }}</span></td>
						</tr>
					</table>
				</div>

				<div class="signatories">
					<div class="received">
						<span>Received by:</span>
						<br><br><br><br><br>
						<div class="signature">Signature over printed name</div>
					</div>
					<div class="recieved-date">
						<br><br><br><br><br>
						<div class="date">Date</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script type="text/javascript">
		window.print()
	</script>
</body>
</html>