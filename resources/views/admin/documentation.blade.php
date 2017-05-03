@extends('admin.layouts.app')

@section('content')
<?php 
    $utils = new eFund\Utilities\Utils();
?>  
<style type="text/css">
	.bs-callout {
    padding: 20px;
    margin: 20px 0;
    border: 1px solid #eee;
    border-left-width: 5px;
    border-radius: 3px;
}
.bs-callout h4 {
    margin-top: 0;
    margin-bottom: 5px;
}
.bs-callout p:last-child {
    margin-bottom: 0;
}
.bs-callout code {
    border-radius: 3px;
}
.bs-callout+.bs-callout {
    margin-top: -5px;
}
.bs-callout-default {
    border-left-color: #777;
}
.bs-callout-default h4 {
    color: #777;
}
.bs-callout-primary {
    border-left-color: #428bca;
}
.bs-callout-primary h4 {
    color: #428bca;
}
.bs-callout-success {
    border-left-color: #5cb85c;
}
.bs-callout-success h4 {
    color: #5cb85c;
}
.bs-callout-danger {
    border-left-color: #d9534f;
}
.bs-callout-danger h4 {
    color: #d9534f;
}
.bs-callout-warning {
    border-left-color: #f0ad4e;
}
.bs-callout-warning h4 {
    color: #f0ad4e;
}
.bs-callout-info {
    border-left-color: #5bc0de;
}
.bs-callout-info h4 {
    color: #5bc0de;
}
</style>
<!-- https://1drv.ms/w/s!AttElQx2Dc5ohtw8rbcp2ZMOrpyVpg -->
	<div class="col-xs-12 col-sm-12 col-md-12">
		<h1>Documentation</h1>
		<p></p>
	</div>	
	<div class="col-xs-12 col-sm-3 col-md-3" style="height: 75vh; overflow: auto;" >
		<h3>Contents</h3>
        <ul>
            <li><a href="#login">Login System</a></li>
            <li><a href="#application">Loan Application</a>
                <ul>
                    <li><a href="#appMgt"> Application Management</a></li>
                </ul>

            </li>
            <li><a href="#loan">Loan Management</a></li>
            <li><a href="#endorsements">Endorsements</a></li>
            <li><a href="#guarantors">Guarantors</a></li>
            <li><a href="#payroll">Payroll</a></li>
            <li><a href="#treasury">Treasury</a></li>
            <li><a href="#reports">Reports</a></li>
            <li><a href="#users">User Management</a></li>
        </ul>
	</div>
	<div class="col-xs-12 col-sm-9 col-md-9" style="height: 75vh; overflow: auto;">
        <div>
            <h1 id="login">Login System</h1>
            <p>The login system provides access to EFund system online. Only Regular Megaworld Employees are allowed to access the system. All regular employees are provided standard user roles in the system which allows loan applications. To login, </p>
            <ol>
                <li>Go to {{ url('login') }}</li>
                <li>Enter <strong>EMPLOYEE ID</strong>, wait for the system to verify your employment status.</li>
                <li>Enter your <strong>PASSWORD</strong> you used to login from SSEP.</li>
                <li>Optional. Check <strong>Keep me signed in</strong></li>
                <li>Click <i class="fa fa-sign-in"></i> <strong>Login</strong> btn</li>
                <li>You will be redirected to  your dashboard with specific and assigned menus.</li>
            </ol>
        </div>

        <div>
            <h1 id="application">Loan Application</h1>
            <p>By default, Loan applications is enabled to all regular employees of Megaworld Corp. This is where employees will submit their loan applications. The eFund System provides easy and yet with several verifications of loan applications allowable to each employee. The employee can file their applications through <strong>My eFunds <img src="{{ url('efund_sm.png') }}" style="background-color: black"></strong> menu.</p>

            <h4 id="appMgt">Application Management</h4>
            <p><strong>My eFunds</strong> provides a listing of all owned previous, current and saved loans applications of the employee.</p>
            <p>To view application, click <i class="fa fa-eye"></i> icon that correspond to the application to be viewed.</p> 
            <p>Applications can only be edited or modified when it is saved only and not yet submitted.</p>
            <p>Application can be monitored through its status.</p>
            <div class="bs-callout bs-callout-info">
                <h4>Application Statuses</h4>
                <div class="table-responsive">
                    <table class="table table-condensed table-hover">
                        <thead>
                            <th>Status</th>
                            <th>Description</th>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{!! $utils->formatStatus(0) !!}</td>
                                <td>Application that has been created and verified but not yet submitted for approval.</td>
                            </tr>
                            <tr>
                                <td>{!! $utils->formatStatus(1) !!}</td>
                                <td>Submitted application that is for approval of the specified endorser.</td>
                            </tr>
                            <tr>
                                <td>{!! $utils->formatStatus(2) !!}</td>
                                <td>Submitted application that is for approval of the specified guarantor.</td>
                            </tr>
                            <tr>
                                <td>{!! $utils->formatStatus(3) !!}</td>
                                <td>Loan application is for verification of payroll if the employee can be qualified to avail the requested loan amount based on the employees' ave. salary for 2 months.</td>
                            </tr>
                            <tr>
                                <td>{!! $utils->formatStatus(4) !!}</td>
                                <td>Loan application is for final approval of the eFund approver.</td>
                            </tr>
                            <tr>
                                <td>{!! $utils->formatStatus(5) !!}</td>
                                <td>Approved applications for check and voucher preparation by the treasury.</td>
                            </tr>
                            <tr>
                                <td>{!! $utils->formatStatus(6) !!}</td>
                                <td>A check has been prepared and ready for claiming.</td>
                            </tr>
                            <tr>
                                <td>{!! $utils->formatStatus(7) !!}</td>
                                <td>Check has been released and for deductions process.</td>
                            </tr>
                            <tr>
                                <td>{!! $utils->formatStatus(8) !!}</td>
                                <td>Loan has been paid in full.</td>
                            </tr>
                            <tr>
                                <td>{!! $utils->formatStatus(9) !!}</td>
                                <td>Denied loan application.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <h4>Appying eFund</h4>
            <p>To apply for a loan, </p>
            <ol>
                <li>Click <label class="label label-success">Apply Loan</label> button.</li>
                <li>You will be redirected to a loan application form.</li>
                <li>Fill out all fields.</li>
                <ul>
                    <li><strong>Type of Application</strong> is automatically determined by the system. So, you don't have to change its option.</li>
                    <li>Provide <strong>local or direct line</strong> telephone number of your department.</li>
                    <li>Specify number of months to pay on <strong>Terms</strong>. If you are applying for the first time within the current year, you can choose up to 12 months. However, if it is your second application within the current year, you can only choose the number of months until December of the current year. Note, terms applied are subject to changes by the eFund approver.</li>
                    <li>Enter <strong>Loan Amount</strong> to be applied. Loan amount varries by rank. Loan amount range is displayed just below the field. If you are applying for loan amount above the minimum range, you will be required to provide a surety or co-borrower or guarantor.</li>
                    <li><strong>Interest</strong> is fixed value and set by EFund Custodian.</li>
                    <li>Specify employee id of your <strong>Endorser.</strong></li>
                    <li>Specify employee id of your guarantor if required.</li>
                    <li>Click Verify to verify and validate your loan application.</li>
                    <li>Once verified, you can now submit the application.</li>
                </ul>
                <div class="bs-callout bs-callout-info">
                    <p>As you specify loan amount, Total and payroll deductions are computed. </p>
                    <p><strong>Total</strong> is the sum of specified loan amount and the interest amount.</p>
                    <p><strong>Interest amount</strong> is the product of loan amount multiplied by percentage of interest.</p>
                    <p><strong>Payroll Deductions</strong> is the quotient of total amount divided by terms.</p>
                </div>
            </ol>

            <h1></h1>
        </div>

        <div>
            <h1 id="endorsements">Endorsements</h1>
            <p>In general, Endorsements is an act of giving one's approval or support.</p>
            <h4>Endorsement Management</h4>
            <p>To view application, click <i class="fa fa-eye"></i> icon that correspond to the application to be viewed.</p>
            <p>Applications can only be approved or denied.</p>
            <ol></ol>
            <h4></h4>
        </div>

        <div></div>
	</div>
@endsection
