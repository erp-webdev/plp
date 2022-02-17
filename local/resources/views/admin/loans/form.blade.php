<!DOCTYPE html>
<html>
<head>
	<title>EFund {{ $loan->ctrl_no }}</title>
	<style type="text/css">
		body{
			font-family: 'Arial';
			width: 100%;
		}
		table{
			width: 100%;
			font-size: 12px;
			border: 1px solid #000;
		}
		.box {
			border: 1px solid #000;
		}
		.u{
			border-bottom: 2px solid #000;
		}
		small{
			font-size: 10px
		}
		
	</style>
</head>
<body>
<table>
	<tr class="box" >
		<tr>
			<td style="" colspan="3"><strong>MEGAWORLD CORPORATION</strong></td>
			<td style=" text-align: center; border-left: 1px solid #000" ><u>EF CONTROL NUMBER</u></td>
		</tr>
		<tr>
			<td colspan="3"><strong>EMPLOYEES FUND LOAN</strong></td>
			<td colspan="1" style="text-align: center; border-bottom: 1px solid #000; border-left: 1px solid #000; "><strong>{{ $loan->ctrl_no }}</strong></td>
		</tr>
		<tr style="height: 50px; vertical-align: bottom">
			<td>TYPE OF APPLICATION</td>
			<td>&nbsp;</td>
			<td><strong>DATE</strong></td>
			<td class="u">{{ $utils->formatDate($loan->created_at) }}</td>
		</tr>
		<tr>
			<td>
				@if($loan->type == 0)
					<u>__✓__ </u><i><strong> NEW</strong></i></u>
				@else
					<u>__✓__ </u><i><strong> REAVAILMENT</strong></i>
					<br> <span>Previous loan amount: </span>
					<br> <span>Balance: </span>
				@endif
			</td>
			<td>
				<u> {{ $loan->special == 0 ? '__✓__': '_____' }} </u><i><strong> REGULAR</strong></i></u> 
				<u>{{ $loan->special == 0 ? '__✓__': '_____' }} </u><i><strong> SPECIAL</strong></i></u> <br>
			</td>
			<td><strong>LOCAL/DIRECT LINE</strong></td>
			<td class="u">{{ $loan->loc_direct_line }}</td>
		</tr>
		<tr>
			<td colspan="2" style="height: 20px"></td>
		</tr>
		<tr>
			<td>
				<tr>
					<td><strong>NAME OF APPLICANT</strong></td>
					<td class="u">{{ $loan->FullName }}</td>
					<td><strong>EMPLOYEE NO.</strong></td>
					<td class="u">{{ $loan->EmpID }}</td>
				</tr>
				<tr>
					<td><strong>POSITION</strong></td>
					<td class="u">{{ $loan->PositionDesc }}</td>
					<td><strong>DEPARTMENT</strong></td>
					<td class="u">{{ $loan->DeptDesc }}</td>
			</tr>
				<tr>
					<td><strong>DATE HIRED</strong></td>
					<td class="u">{{ $loan->HireDate }}</td>
					<td><strong>No of Years in the company:</strong></td>
					<td class="u"></td>
				</tr>
				<tr>
					<td><strong>EMPLOYMENT REGULARIZATION DATE</strong></td>
					<td class="u">{{ $loan->PermanencyDate }}</td>
					<td><strong>PURPOSE:</strong></td>
					<td class="u">{{ $loan->purpose }}</td>
				</tr>
				<tr>
					<td><strong>LOAN AMOUNT</strong></td>
					<td class="u">Php {{ $utils->formatNumber($loan->loan_amount) }}</td>
				</tr>
			</td>
		</tr>
	</tr>
	<tr style="height: 10px"></tr>
</table>
<table>
	<tr class="box">
		<tr style="height: 35px; vertical-align: bottom;">
			<td><strong>Requested By</strong></td>
			<td class="u" style="text-align: center">{{ $loan->FullName }}</td>
			<td><strong>Endorsed By</strong></td>
			<td class="u" style="text-align: center">{{ $loan->endorser_FullName }} <br><small>Ref. #{{ $loan->endorser_refno }}</small></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td style="text-align: center"><small>SIGNATURE OVER PRINTED NAME <br> &nbsp;</small></td>
			<td>&nbsp;</td>
			<td style="text-align: center; vertical-align: text-top;"><small>DEPARTMENT HEAD / IMMEDIATE HEAD <br>SIGNATURE OVER PRINTED NAME</small></td>
		</tr>
		<tr style="height: 10px"></tr>
	</tr>
</table>
<table style="height: 15px">
</table>
<table>
	<tr class="box">
		<td colspan="4">
			<strong>FOR THE SURETY/CO-BORROWER</strong><br><br>
			<p>I hereby consent to act as surety of the applicant and agree to pay the abovenamed applicant's loan up to the amount of PHP <strong><u>{{ $utils->formatNumber($loan->guaranteed_amount) }}</u></strong>. As surety, I hereby authorize Megaworld Corporation to deduct from my salary, allowances, bonuses and other benefits without any need of prior notice, any outstanding balance of the applicant's loan including interest and penalty until full payment thereof in case of applicant's default, resignation, termination, dismissal or failure to pay amortization relating to the loan.</p><br>
			<strong>Conforme:</strong><br><br><br>
			
		</td>
	</tr>
	<tr>
		<td style="text-align: center; vertical-align: bottom;">
			{{ $loan->guarantor_FullName }} <br><small>{{ $loan->guarantor_refno }}</small>
		</td>
		<td colspan="3">&nbsp;</td>
	</tr>
	<tr>
		<td style="width: 250px; border-top: 2px solid black; text-align: center">
				<small>DEPARTMENT HEAD / IMMEDIATE HEAD <br>
					SIGNATURE OVER PRINTED NAME</small>
		</td>
		<td colspan="3">&nbsp;</td>
	</tr>	
</table>
<table>
	<tr style="height: 10px">
		<td colspan="4"></td>
	</tr>
	<tr >
		<td style="height: 15px; background-color: #ccc" colspan="4"></td>
	</tr>
	<tr class="box">
		<td colspan="2">
			<strong><u>FOR EMPLOYEES FUND CUSTODIAN USE</u></strong><br><br>
		Previous Balance: <strong><u>{{ $utils->formatNumber($balance) }}</u></strong><br><br>
		Approved Loan: <strong><u>Php {{ $utils->formatNumber($loan->loan_amount) }}</u></strong><br><br>
		Interest: <strong><u>{{ $loan->interest }}% </u></strong> x <strong><u>{{ $loan->terms_month }}</u> </strong> months <br><br>
		<u><strong>TOTAL </strong>  <strong>Php <u>{{ $utils->formatNumber($loan->total) }}</u></strong></u>
		</td>
		<td colspan="2" style="text-align: left; vertical-align: text-top; border-left: 1px solid #ccc; padding-left: 10px">
			<strong>Remarks:</strong> <br>
			<p>{{ $loan->remarks }}</p>
		</td>
	</tr>
	<tr >
		<td colspan="4" style="height: 15px; background-color: #ccc"></td>
	</tr>
	<tr style="height: 10px" colspan="4"></tr>
	<tr>
		<td style="width: 25%"><strong>Start of Payment</strong></td>
		<td style="width: 25%" class="u">
		@if(!empty($loan->start_of_deductions))
			{{ $loan->start_of_deductions }}
		@endif
		</td>
		<td style="width: 25%"><strong>Every Payroll deduction</strong></td>
		<td style="width: 25%" class="u">{{ $utils->formatNumber($loan->deductions) }}</td>
	</tr>
	<tr>
		<td><strong>Number of payments to be made</strong></td>
		<td class="u">{{ $loan->terms_month * 2 }}</td>
		<td><strong>CV#</strong></td>
		<td>{{ $loan->cv_no }}</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td><strong>Check Release Date:</strong></td>
		<td>
		@if(!empty($loan->check_released))
			{{ $loan->check_released }}
		@endif
		</td>
	</tr>
		<tr style="height: 10px">
		<td colspan="4"></td>
	</tr>
	<tr >
		<td style="height: 15px; background-color: #ccc" colspan="4"></td>
	</tr>
</table>
<table>
	<tr>
		<td colspan="4"><strong>ACTION TAKEN</strong></td>
	</tr>
	<tr>
		<td style="width: 100px">&nbsp;</td>
		<td style="width: 10px">&nbsp;</td>
		<td style="width: 50px"><i><strong>APPROVING OFFICERS</strong></i></td>
		<td style="width: 150px"></td>
	</tr>
	<tr>
		<td style="width: 100px; text-align: right"><strong>APPROVED</strong></td>
		<td style="width: 10px; text-align: center; border: 1px solid black">
			@if($loan->approved == 1)
			✓
			@endif
		</td>
		<td style="width: 100px; border-bottom: 2px solid black">
			@if($loan->approved == 1)
			<!-- {{ $loan->approved_FullName }} -->
			@endif</td>
		<td></td>
	</tr>
	<tr>
		<td style="width: 100px; text-align: right"><strong>DISAPPROVED</strong></td>
		<td style="width: 10px; text-align: center; border: 1px solid black">
			@if($loan->approved == 0 && !empty($loan->approved_FullName))
			✓
			@endif
		</td>
		<td style="width: 50px; border-bottom: 2px solid black">
			@if($loan->approved == 0)
			{{ $loan->approved_FullName }}
			@endif
		</td>
	</tr>
	<tr style="height: 10px"></tr>
</table>
<table>
	<tr>
		<td colspan="4">
			<p>This is to certify that I, <u>{{ $loan->FullName }}</u>, received the amount of <u>{{ $utils->formatNumber($loan->loan_amount) }}</u> as loan from the Megaworld Personal Loan Program. I have agreed to all the Terms and Conditions written above. Furthermore, I hereby acknowledge my obligation to pay my loan amortization as it fall due. If in case there is no deduction made from my salary, I will notify the personal loan program custodian immediately that I will pay via cash to Treasury Department. I am aware that any undeducted / unpaid amount due shall be subject to an interest rate of 1% per mo. and will become immediately due and demandable.</p><br><br><br>
		</td>
	</tr>
	<tr>
		<td class="u" colspan="1" style="width: 250px">{{ $loan->FullName }}</td>
		<td colspan="3">&nbsp;</td>
	</tr>
	<tr>
		<td colspan="1"> <strong><small>SIGNATURE OVER PRINTED NAME</small></strong></td>
		<td colspan="3">&nbsp;</td>
	</tr>
</table>
<table style="border: 0px solid black">
	<tr>
		<td style="width: 45%; text-align: right"><strong><small>THIS FORM CANNOT BE REPRODUCED.</small></strong></td>
		{{-- <td style="width: 25%; text-align: center"><small>(REVISED 01/12/17)</small></td> --}}
	</tr>
	<tr style="font-size: 9px">
		<td>Printed at: {{ date('m/d/Y H:i:s') }}</td>
		<td style="text-align: right">Printed by: ({{ Auth::user()->id }}) {{ strtolower(Auth::user()->name) }} </td>
	</tr>
</table>
<script type="text/javascript">
	window.print()
</script>
</body>
</html>