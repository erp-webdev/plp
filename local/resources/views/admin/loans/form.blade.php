<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Personal Loan Program {{ $loan->ctrl_no }}</title>
    {{-- <link rel="stylesheet" href="style.css"> --}}
    <style>
        *, *::before, *::after {
    box-sizing: border-box;
    padding: 0;
    margin: 0;
    font-family: Arial, Helvetica, sans-serif;
    }
    html, body {
        height: 100%;
    }
    .container {
        width: 816px;
        height: 1344px;
        margin: 25px auto 0;
        border: 2px solid #111;
        background-color: #fff;
    }
    .container form input[type='text'] {
        border: 0;
        border-bottom: 1px solid #111;
    }
    .container .required-text {
        font-size: 9px;
        font-style: italic;
        color: #555;
    }
    .container div {
        width: 100%;
    }
    .container form > div + div {
        border-top: 2px solid #111;
    }
        /* Application details CSS */
        .applicant-details {
            height: 480px;
        }
            .form-title {
                width: 100%;
                min-height: 30px;
                padding: 3px;
                position: relative;
            }
            .form-title h1 {
                font-size: 20px;
                font-weight: 600;
                text-decoration: underline;
            }
            .form-title h5 {
                margin-top: 5px;
            }
            .form-title #pl-control-number {
                display: block;
                width: 200px;
                height: 50px;
                padding: 12px 25px;
                text-align: center;
                border-left: 1px solid #111;
                border-bottom: 1px solid #111;
                position: absolute;
                top: 0;
                right: 0;
            }
                #pl-control-number p {
                    font-size: 10px;
                    text-align: center;
                    text-decoration: underline;
                }
                #pl-control-number #cn {
                    margin: 3px 0 0;
                    text-decoration: none;
                }
                    #cn input {
                        font-size: 10px;
                        width: 33px;
                    }
            .form-header {
                height: 200px;
                padding: 5px 0 0;
            }
                .form-header div {
                    display: inline-block;
                    vertical-align: top;
                    width: 50%;
                }
                .form-header .type-of-app {
                    height: 100%;
                    padding: 2px;
                    border: 2px solid #111;
                    border-left: 0;
                    position: relative;
                }
                    .type-of-app .type-app-table {
                        display: table;
                        width: 100%;
                        padding: 0 15px;
                    }
                        .type-app-table .type-app-table-row {
                            display: table-row;
                            margin: 10px 0 0;
                        }
                            .type-app-table-row .type-app-table-cell {
                                display: table-cell;
                            }
                                .type-app-table-cell > label {
                                    font-size: 11px;
                                    font-style: italic;
                                    font-weight: 700;
                                }
                            .type-app-table-row .type-app-table-cell.re-evailment ul {
                                margin: 0 0 0 15px;
                            }
                                .re-evailment ul li {
                                    list-style: none;
                                    position: relative;
                                }
                                .re-evailment ul li label {
                                    font-size: 11px;
                                }
                                .re-evailment ul li input {
                                    width: 60px;
                                    position: absolute;
                                    bottom: 0;
                                    right: 0;
                                }
                                .type-app-table-cell input.application-type {
                                    width: 30px;
                                    font-size: 11px;
                                    margin: 0 5px 0 0;
                                }
                            .type-app-table-row .app-type-special label {
                                font-size: 15px;
                                font-style: normal;
                                color: rgb(0, 100, 255);
                            }
                                .app-type-special p {
                                    margin: 10px 0 0 10px;
                                    font-size: 11px;
                                    color: rgb(0, 100, 255);
                                }
                    /* --- Nurse Signature --- */
                    .type-of-app .nurse-signature {
                        width: 60%;
                        height: 20px;
                        border-top: 3px solid #111;
                        position: absolute;
                        bottom: 0;
                        right: 0;
                    }
                        .nurse-signature p {
                            line-height: 15px;
                            font-size: 11px;
                            color: rgb(0, 100, 255);
                        }
                    /* ---------------------- */
                .form-header .type-of-loan {
                    width: 49%;
                    padding: 3px;
                }
                    .type-of-loan ul {
                        margin: 6px 0 0;
                    }
                    .type-of-loan ul li {
                        display: inline-block;
                        font-size: 11px;
                    }
                    .type-of-loan ul li input[type='checkbox'] {
                        margin: 0 12px;
                        transform: scale(1.5);
                    }
                    .type-of-loan ul li input[type='text'] {
                        width: 70px;
                    }
                    .type-of-loan .date-of-loan {
                        width: 200px;
                        height: 50px;
                        margin: 10px 0 0 30px;
                    }
                    .date-of-loan label {
                        display: block;
                        font-size: 11px;
                        position: relative;
                    }
                    .date-of-loan label input {
                        height: 10px;
                        font-size: 11px;
                        position: absolute;
                        bottom: 0;
                        right: 0;
                    }
                        .date-of-loan #date-input {
                            width: 150px;
                        }
                        .date-of-loan #line-loan {
                            width: 80px;
                        }
            .form-body {
                height: 150px;
                padding: 10px 0 0;
            }
                .form-body div {
                    display: inline-block;
                    vertical-align: top;
                }
                .form-body .first-form-body {
                    width: 53%;
                    margin: 0 2% 0 0;
                }
                .form-body .second-form-body {
                    width: 44%;
                }
                    .first-form-body label, .second-form-body label {
                        display: block;
                        width: 100%;
                        height: 18px;
                        padding: 0 0 0 3px;
                        font-size: 11px;
                        position: relative;
                    }
                    .first-form-body label + label, .second-form-body label + label {
                        margin: 6px 0 0 0;
                    }
                    .first-form-body input, .second-form-body input {
                        width: 200px;
                        height: 18px;
                        font-size: 12px;
                        position: absolute;
                        bottom: 0;
                        right: 0;
                    }
                    .first-form-body label:last-child {
                        font-weight: 700;
                        font-size: 13px;
                    }
                    .first-form-body label:last-child input {
                        font-weight: 700;
                        border-bottom: 2px solid #111;
                    }
                    .second-form-body label:last-child input {
                        width: 100%;
                    }
            .applicant-signature-1 {
                width: 100%;
                height: 80px;
                padding: 3px;
            }
            .applicant-signature-1 > div {
                display: inline-block;
                vertical-align: top;
                width: calc(50% - 3px);
                position: relative;
            }
                .requester-sign label, .endorser-sign label {
                    font-weight: 700;
                    font-size: 11px;
                }
                .requester-sign > span, .endorser-sign > span {
                    display: block;
                    width: 300px;
                    min-height: 20px;
                    position: absolute;
                    top: 0;
                    right: 0;
                }
                .requester-sign span input,
                .endorser-sign span input {
                    width: 250px;
                    height: 18px;
                }
                .requester-sign span p,
                .endorser-sign span p {
                    line-height: 15px;
                    font-size: 8px;
                }
                .requester-sign span .sign-label,
                .endorser-sign span .sign-label {
                    display: block;
                    line-height: 20px;
                    font-size: 9px;
                }

        /* Surety/Co-Borrower CSS */
        .surety {
            height: 240px;
        }
            .surety h5{
                padding: 3px;
            }
            .surety .statement {
                display: block;
                width: 100%;
                padding: 20px 10px 20px 3px;
                font-size: 11px;
                position: relative;
                line-height: 1.5;
                /*background-color:yellow;*/
                border:0;
            }
            .surety .conforme {
                width:100%;
                padding: 0 0 0 3px;
                font-size:11px;
                position:relative;
                font-weight:bold;
            }
            .surety div input[type='text']{
                width:30%;
                margin: 25px 0 0;
                border-bottom: 2px solid #111;
            }
            .surety .conforme-signatory {
                display:block;
                width:30%;
                padding: 1px 0 0 3px;
                font-size:10px;
                font-weight:bold;
                text-align:center;
                line-height:1.5;
                position:relative;
            }
        
        /* Custodian CSS */
        .custodian {
            height: 288px;
            padding: 20px 3px;
            position: relative;
        }
            .custodian h5 {
                text-decoration: underline;
                margin: 0 0 10px;
            }
            .custodian .qualification {
                display: block;
                width: 250px;
                height: 100px;
                position: absolute;
                top: 20px;
                right: 3px;
            }
                .qualification label {
                    display: block;
                    margin: 0 0 10px;
                    font-size: 12px;
                }
                .qualification label input {
                    vertical-align: middle;
                    margin: 0 25px 0 0;
                    transform: scale(1.5, 1.2);
                }
            .custodian > label {
                font-size: 11px;
                line-height: 30px;
            }
            .custodian > label input {
                width: 140px;
            }
            .custodian label .approved-loan-amount {
                margin: 0 0 0 50px;
            }
            .custodian label .interest-percentage {
                font-size: 12px;
                font-weight: 700;
            }
            .custodian label .interest-month {
                width: 20px;
            }
            .custodian .total-php {
                display: block;
                width: 250px;
                margin: 15px 0 0 -3px;
                font-weight: 700;
                font-size: 12px;
                line-height: 15px;
                border-bottom: 2px solid #111;;
            }
            .custodian .payment-details li {
                display: inline-block;
                vertical-align: top;
                list-style: none;
                width: 49%;
                line-height: 30px;
                font-weight: 700;
                font-size: 11px;
            }

        /* Action-taken CSS */
        .action-taken {
            height: 150px;
            padding: 3px;
        }
            .action-taken > div {
                width: 60%;
                min-height: 110px;
                margin: 0 auto;
            }
                .action-officers {
                    font-style: italic;
                    font-size: 12px;
                    font-weight: 700;
                    text-align: center;
                }
            .action-taken div .approval-wrapper {
                width: 90%;
                height: 25px;
                margin: 10px auto 0;
            }
                .approval-wrapper .approval-row {
                    width: 100%;
                    height: 25px;
                    line-height: 25px;
                }
                    .approval-row span {
                        display: inline-block;
                        vertical-align: top;
                        width: 30%;
                        height: 25px;
                        font-size: 12px;
                        position: relative;
                    }
                    .approval-row span::after {
                        content: "";
                        width: 25px;
                        height: 25px;
                        border: 1px solid #111;
                        position: absolute;
                        top: 0;
                        right: 13px;
                    }
                    .approval-row input {
                        width: 200px;
                    }

        /* Certification CSS */
        .certification {
            width: 100%;
            height: 183px;
            padding: 3px;
            position: relative;
        }
            .certification p.statement {
                margin: 20px 0 0;
                font-size: 11px;
                line-height: 17px;
            }
            .certification .applicant-signature {
                width: 245px;
                position: absolute;
                bottom: 25px;
                left: 0;
            }
            .certification .applicant-signature input.applicant-sign-input {
                width: 100%;
                border-bottom: 2px solid #111;
            }
            .certification .applicant-signature p {
                text-indent: 3px;
                font-size:10px;
                font-weight: 700;
            }
    </style>
</head>
<body>
    <div class="container">
        <form action="url_or_some_php_file.php" method="post">
            <!-- Applicant's Main Info row -->
            <div class="applicant-details">
                <div class="form-title">
                    <h1>PERSONAL LOAN PROGRAM</h1>
                    <h5>Application Form</h5>
                    <span id="pl-control-number">
                        <p>PL CONTROL NUMBER</p>
                        <p id="cn">{{ $loan->ctrl_no }}</p>
                    </span>
                </div>
                <div class="form-header">
                    <div class="type-of-app">
                        <h5>TYPE OF APPLICATION</h5>
                        <div class="type-app-table">
                            <div class="type-app-table-row">
                                <div class="type-app-table-cell app-type-reg">
                                    <label><input class="application-type" type="text" value="{{ $loan->type == 0 ? '✓' : '' }}">NEW</label>
                                </div>
                                <div class="type-app-table-cell re-evailment">
                                    <label><input class="application-type" type="text" value="{{ $loan->type == 0 ? '' : '✓' }}">RE-AVAILMENT</label>
                                    <ul>
                                        <li><label>Previous loan amount:</label><input type="text" value=""></li>
                                        <li><label>Balance:</label><input type="text"></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="type-app-table-row">
                                <div class="type-app-table-cell app-type-reg">
                                    <label><input class="application-type" type="text" value="{{ $loan->special == 0 ? '✓': '' }}">REGULAR</label>
                                </div>
                                <div class="type-app-table-cell app-type-special">
                                    <label><input class="application-type" type="text" value="{{ $loan->special == 0 ? '': '✓' }}">SPECIAL</label>
                                    <p>Validated by:</p>
                                </div>
                            </div>
                        </div>
                        <div class="nurse-signature">
                            {{ $loan->company_nurse }} - {{ $loan->company_nurse_date }}
                            <p>Company Nurse's signature over printed name</p>
                        </div>
                    </div>
                    <div class="type-of-loan">
                        <ul>
                            <li><input type="checkbox" value="{{ $loan->COMPANY == 'MEGA01' ? '✓' : '' }}">MEG</li>
                            <li><input type="checkbox" value="{{ $loan->COMPANY == 'GLOBAL01' ? '✓' : '' }}">GLO</li>
                            <li><input type="checkbox" value="{{ $loan->COMPANY == 'LGMI01' ? '✓' : '' }}">LUX</li>
                            <li><input type="checkbox" value="{{ in_array($loan->COMPANY, ['MEGA01', 'GLOBAL01', 'LGMI01'])  ? '✓' : '' }}">OTHERS<input type="text" value="{{ $loan->COMPANY }}"></li>
                        </ul>
                        <div class="date-of-loan">
                            <label>DATE: <input id="date-input" type="text" value="{{ date('Y-m-d', strtotime($loan->created_at)) }}"></label>
                            <label>LOCAL / DIRECT LINE: <input id="line-loan" type="text" value="{{ $loan->loc_direct_line }}"></label>
                            <span class="required-text">(Required)</span>
                        </div>
                    </div>
                </div>
                <div class="form-body">
                    <div class="first-form-body">
                        <label>NAME OF APPLICANT: <input type="text" value="{{ $loan->FullName }}"></label>
                        <label>POSITION: <input type="text" value="{{ $loan->PositionDesc }}"></label>
                        <label>DATE HIRED: <input type="text" value="{{ $loan->HireDate }}"></label>
                        <label>EMPLOYMENT REGULARIZATION DATE: <input type="text" value="{{ $loan->PermanencyDate }}"></label>
                        <label>REQUESTED LOAN AMOUNT: <input type="text" value="PHP {{ $utils->formatNumber($loan->loan_amount) }}"></label>
                    </div>
                    <div class="second-form-body">
                        <label>No of years in the company: <input type="text" value=""></label>
                        <label>EMPLOYEE NO: <input type="text" value="{{ $loan->EmpID }}"></label>
                        <label>DEPARTMENT: <input type="text" value="{{ $loan->DeptDesc }}"></label>
                        <label>PURPOSE: <input type="text" value="{{ $loan->purpose }}"></label>
                        <label><input type="text"></label>
                    </div>
                </div>
                <div class="applicant-signature-1">
                    <!-- Requester Signature -->
                    <div class="requester-sign">
                        <label for="requester-sign-field">Requested by:</label>
                        <span class="signature-wrapper">
                            <input id="requester-sign-field" type="text" value="{{ $loan->FullName }}">
                            <p>SIGNATURE OVER PRINTED NAME</p>
                        </span>
                    </div>
                    <!-- Endorser Signature | DEPARTMENT HEAD / IMMEDIATE HEAD -->
                    <div class="endorser-sign">
                        <label for="endorser-sign-field">Endorsed by:</label>
                        <span class="signature-wrapper">
                            <input id="endorser-sign-field" value="{{$loan->endorser_FullName}}" type="text">
                            <span class="sign-label">DEPARTMENT HEAD / IMMEDIATE HEAD</span>
                            <p>SIGNATURE OVER PRINTED NAME</p>
                        </span>
                    </div>
                </div>
            </div>
            <!-- Surety / Co-borrower row -->
            <div class="surety">
                <h5>FOR THE SURETY/CO-BORROWER:</h5>
				<p class="statement">
					I hereby consent to act as surety of the applicant and agree to pay the abovenamed applicant's loan up to the amount of PHP <input type="text" value="{{ $utils->formatNumber($loan->guaranteed_amount) }}"><br>
					As surety, I hereby authorize Megaworld Corporation to deduct from my salary, allowances, bonuses and other benefits without 
					any need of prior notice any oustanding balance of the Applicant's loan including interest and penalty until full payment thereof 
					in case of applicant's default, resignation, termination, <br>dismissal or failure to pay amortization relating to the loan.
				</p>
                <div>
                    <p class="conforme">Conforme:<p>
                    <input type="text" value="{{ $loan->guarantor_FullName }}">
                    <label class="conforme-signatory">
                        DEPARTMENT HEAD / DIVISION HEAD<br>
                        <label style="font-size:11px">SIGNATURE OVER PRINTED NAME</label>
                    </label>
                </div>
            </div>
            <!-- Personal Loan Fund Custodian row -->
            <div class="custodian">
                <h5>FOR PERSONAL LOAN FUND CUSTODIAN USE</h5>
                <span class="qualification">
                    <label><input type="checkbox">Qualified to avail</label>
                    <label><input type="checkbox">NOT qualified to avail</label>
                </span>
                <label>Previous loan amount <input type="text" value=""></label>
                <label>Balance: <input type="text" value="{{ $utils->formatNumber($balance) }}"></label>
                <br>
                <label>Approved loan: <span class="approved-loan-amount">Php: <input type="text" value="{{ $utils->formatNumber($loan->loan_amount) }}"></span></label>
                <br>
                <label>Interest(<span class="interest-percentage"> {{ $loan->interest }}%</span> x <input class="interest-month" type="text" value="{{ $loan->terms_month }}"> months)</label>
                <br>
                <span class="total-php">TOTAL Php</span>
                <ul class="payment-details">
                    <li><label>Start of payment: <input type="text" value="{{ !empty($loan->start_of_deductions) ? $loan->start_of_deductions : '' }}"></label></li>
                    <li><label>Every Payroll deduction: <input type="text" value="{{ $utils->formatNumber($loan->deductions) }}"></label></li>
                    <li><label>Number of payments to be made: <input type="text" value="{{ $loan->terms_month * 2 }}"></label></li>
                </ul>
            </div>
            <!-- Action Taken row -->
            <div class="action-taken">
                <h5>ACTION TAKEN</h5>
                <div>
                    <p class="action-officers">APPROVING OFFICERS</p>
                    <div class="approval-wrapper">
                        <div class="approval-row">
                            <span>APPROVED</span>
                            <input type="text" value="{{ $loan->approved == 1 ? '✓' . $loan->approved_FullName  : '' }}">
                        </div>
                        <div class="approval-row">
                            <span>DISAPPROVED</span>
                            <input type="text" value="{{ $loan->approved == 0 ? '✓' . $loan->approved_FullName : '' }}">
                        </div>
                    </div>
                </div>
            </div>
            <!-- Certification row -->
            <div class="certification">
                <p class="statement">This is to certify that I<input type="text" value="{{ $loan->FullName }}">, received the amount of <input type="text" value="{{ $utils->formatNumber($loan->loan_amount) }}"> as loan from the Megaworld Personal Loan Program. I have agreed to all the Terms and Conditions written above. Furthermore, I hereby acknowledge my obligation to pay my loan amortization as it fall due. If in case there is no deduction made from my salary, I will notify the personal loan program custodian immediately that I will pay via cash to Treasury Department. I am aware that any undeducted / unpaid amount due shall be subject to an interest rate of 1% per mo. and will become immediately due and demandable.</p>
                <div class="applicant-signature">
                    <input class="applicant-sign-input" type="text" value="{{ $loan->FullName }}">
                    <p>APPLICANT'S SIGNATURE OVER PRINTED NAME</p>
                </div>
            </div>
        </form>
    </div>
</body>
</html>