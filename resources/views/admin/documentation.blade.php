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
            <li><a href="#application">Loan Application</a></li>
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
            <p>By default, Loan applications is enabled to all regular employees of Megaworld Corp. This is where employees will submit their loan applications. The eFund System provides easy and yet with several verifications of loan applications allowable to each employee. The employee can file their applications through <strong>My Loans <img src="{{ url('efund_sm.png') }}" style="background-color: black"></strong> menu.</p>
            <p><strong>My Loans</strong> provides a listing of all owned previous, current and saved loans applications of the employee.</p>

        </div>
        <div class="bs-callout bs-callout-info">
            <h5>Loan Statuses</h5>
            <ul type="none">
                @for($i = 0; $i < count($utils->stats); $i++)
                <li>{!! $utils->formatStatus($i) !!}</li>
                @endfor
            </ul>
        </div>
       
	</div>
@endsection
