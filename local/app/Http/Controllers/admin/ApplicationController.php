<?php

namespace eFund\Http\Controllers\admin;

use Illuminate\Http\Request;

use DB;
use Auth;
use Event;
use Session;
use eFund\Log;
use eFund\Loan;
use eFund\Terms;
use eFund\SpecialTerm;
use eFund\GLimits;
use eFund\Ledger;
use eFund\Endorser;
use eFund\Guarantor;
use eFund\Employee;
use eFund\Preference;
use eFund\AllowedAboveMaxLoan;
use eFund\Http\Requests;
use eFund\Utilities\Utils;
use eFund\Events\LoanCreated;
use eFund\Http\Controllers\Controller;
use eFund\Http\Controllers\admin\NotificationController;

class ApplicationController extends Controller
{
    private $utils;
    private $logs;

	function __construct()
    {
        Session::set('menu', 'myloans');
        $this->utils = new Utils;
        $this->logs = new Log();
    }

    public function index()
    {
        $show = 10;
        $search = '';
        if(isset($_GET['show']))
            $show = $_GET['show'];

        if(isset($_GET['search']))
            $search = $_GET['search'];

        $employee = Employee::current()->first();
        $loans = Loan::employee($employee)
                    ->search($search)
                    ->orderBy('ctrl_no', 'desc')
                    ->paginate($show);

    	return view('admin.applications.index')
                ->withLoans($loans)
                ->withUtils($this->utils);
    }

    public function show($id)
    {
        // Loan Record
        $loan = DB::table('viewLoan')->where('id', $id)->first();

        // Check existing record
        if(empty($loan))
            abort(404);

        // Check Ownership
        if(Auth::user()->employee_id != $loan->EmpID)
            abort(403);

        // Employee Information
        $employee = Employee::current()->first();
        // Loan Application Counts
        $records = Loan::employee($employee)->notDenied()->count();
        // Loan Application Counts within the current year
        $records_this_year = Loan::employee($employee)->yearly()->notDenied()->count();
        // Interest percentage
        $interest = Preference::name('interest');
        // Employee Term Limits
        $terms = Terms::getRankLimits($employee);
        $special_loan = SpecialTerm::getRankLimits($employee);
        // Employee Standing balance
        $balance = $this->getStandingBalance();
        // Previous loan application
        $previous_loan = $this->getPreviousLoan();
        // Allowable # of months
        $months = Preference::name('payment_term');
        $months = $months->value;
        if($records_this_year > 0)
            $months = $this->utils->getTermMonths($loan->type, $loan->special, $loan->terms_month);
            
        $months_special = Preference::name('payment_term_special');
        
        $endorser = $this->getEndorser();
        $guarantor = $this->getGuarantor();
        $approvers = $this->getApprovers();

        $allow_max = Preference::name('allow_over_max');
        $allow_max_ex = AllowedAboveMaxLoan::where('EmpID', Auth::user()->employee_id)
            ->where('DBName', Auth::user()->DBNAME)
            ->where('ExpiredAt', '>=', date('Y-m-d'))
            ->first();

        if($loan->status < 1)
        {
        	return view('admin.applications.create3')->withLoan($loan)
                    ->withEmployee($employee)
                    ->withRecords($records)
                    ->withInterest($interest->value)
                    ->withTerms($terms)
                    ->withBalance($balance)
                    ->withMonths($months)
                    ->with('records_this_year', $records_this_year)
                    ->with('overMax', $allow_max->value)
                    ->withEndorser($endorser)
                    ->withPreviousLoan($previous_loan)
                    ->withGuarantor($guarantor)
                    ->withSpecial($special_loan)
                    ->withMonthsSpecial($months_special->value)
                    ->with('approvers', $approvers)
                    ->with('allowed_above_max', $allow_max_ex)
                    ->withUtils(new Utils());
        }else{
            return view('admin.applications.show3')
                ->withPreviousLoan($previous_loan)
                ->withLoan($loan)
                ->withUtils(new Utils());
        }
    }

    public function create()
    {
        // Employee Information
    	$employee = Employee::current()->first();
        // Count saved applications
        $saved = Loan::employee($employee)->status(0)->count();
        if($saved > 0)
            return redirect()->back()
                ->withError('Cannot apply loan! Please submit or cancel other SAVED application first.');

        // Loan Application Counts
    	$records = Loan::employee($employee)->notDenied()->count();
        // Loan Application Counts within the current year based on the application date
        $records_this_year = Loan::employee($employee)->yearly()->notDenied()->count();
        // Interest percentage
    	$interest = Preference::name('interest');
        // Employee Term Limits
    	$terms = Terms::getRankLimits($employee);
        // Special Loan limits
        $special_loan = SpecialTerm::getRankLimits($employee);
        // Employee Standing balance
        $balance = $this->getStandingBalance();
        // Previous loan application
        $previous_loan = $this->getPreviousLoan();
        // Allowable # of months
        $months = Preference::name('payment_term');
        $months = $this->utils->getTermMonths($records, 0, $months->value);
        $months_special = Preference::name('payment_term_special');
        
        $allow_max = Preference::name('allow_over_max');

        $endorser = $this->getEndorser();
        // if(count($endorser) > 0)
        //     $endorser = $endorser[0];
        // else
        //     $endorser = '';

        $guarantor = $this->getGuarantor();
        // if(count($guarantor) > 0)
        //     $guarantor = $guarantor[0];
        // else
        //     $guarantor = '';
        $approvers = $this->getApprovers();

        $allow_max_ex = AllowedAboveMaxLoan::where('EmpID', Auth::user()->employee_id)
            ->where('DBName', Auth::user()->DBNAME)
            ->where('ExpiredAt', '>=', date('Y-m-d'))
            ->first();

    	return view('admin.applications.create3')
    	->withEmployee($employee)
    	->withRecords($records)
    	->withInterest($interest->value)
    	->withTerms($terms)
        ->withBalance($balance)
        ->withMonths($months)
        ->with('records_this_year', $records_this_year)
        ->with('overMax', $allow_max->value)
        ->withEndorser($endorser)
        ->withGuarantor($guarantor)
        ->withUtils(new Utils())
        ->with('approvers', $approvers)
        ->withPreviousLoan($previous_loan)
        ->withSpecial($special_loan)
        ->withMonthsSpecial($months_special->value)
        ->with('allowed_above_max', $allow_max_ex);
    }


    public function getEmployee(Request $request)
    {

        $employee = Employee::where('Active', 1)
                    ->where(function($query) use ($request){
                        $query->where('FullName', 'LIKE', '%' . $request->search . '%')
                        ->orWhere('EmpID', 'LIKE', '%' . $request->search . '%');
                    })
                    ->get();

        return $employee;
    }

    public function store(Request $request)
    {
        $msg = '';
        $errors = $this->checkValidity($request);

        if(count($errors) == 0){

            DB::beginTransaction();

            try {
                $loan = new Loan();

                // Interest percentage
                $interest = Preference::name('interest');
                // Employee Term Limits
                $employee = Employee::current()->first();
                $terms = Terms::getRankLimits($employee);

                $loan = Loan::find($request->id);
                if(empty($loan))
                    $loan = new Loan();

                $loan->setTable('eFundData');
                
                $loan->ctrl_no = '0000';
                $loan->type = $request->type;
                $loan->EmpID = Auth::user()->employee_id;
                $loan->loan_amount = $request->loan_amount;
                $loan->local_dir_line = $request->loc;
                $loan->interest = $interest->value;
                $loan->terms_month = $request->term_mos;
                $loan->total = $this->utils->getTotalLoan($request->loan_amount, $interest->value, $request->term_mos);
                $loan->deductions = $this->utils->computeDeductions($request->term_mos, $request->loan_amount);
                $loan->status = $this->utils->setStatus();
                $loan->DBNAME = $employee->DBNAME;
                $loan->save();

                // Create Endorser
                $endorser = Endorser::firstOrNew(['eFundData_id' => $loan->id]);
                // $endorser->refno = $this->utils->generateReference();
                $endorser->eFundData_id = $loan->id;
                $endorser->EmpID = $request->head;
                $endorser->save();
                
                $loan->endorser_id = $endorser->id;
                $loan->save();

                // Create Guarantor if applicable
                $guarantor = Guarantor::firstOrNew(['eFundData_id' => $loan->id]);

                if($this->validateAboveMinAmount($request->loan_amount)){
                    // $guarantor->refno = $this->utils->generateReference();
                    $guarantor->eFundData_id = $loan->id;
                    $guarantor->EmpID = $request->surety;
                    $guarantor->save();
                    
                    $loan->guarantor_id = $guarantor->id;
                    $loan->save();
                }else{
                    // Delete guarantor record if exists
                    $g = DB::table('guarantors')->where('eFundData_id', $loan->id)->get();
                    DB::table('guarantors')->where('eFundData_id', $loan->id)->delete();
                    $log = new Log();
                    $log->writeOnly('Delete', 'guarantors', $g);
                }

                if(isset($_POST['verify'])){

                    $msg = trans('loan.application.verified');
                    
                }else{

                    // Save and Submit
                    $loan = Loan::find($request->id);
                    $loan->ctrl_no = $this->utils->generateCtrlNo();
                    $loan->status = $this->utils->setStatus($loan->status, $loan->endorser_id);
                    $loan->created_at = date('Y-m-d H:i:s');
                    $loan->save();

                    $msg = trans('loan.application.success');

                    // Notification
                    $notif = new NotificationController();
                    $notif->notifyAppSubmission($loan);

                    Event::fire(new LoanCreated($loan));
                }

                DB::commit();

                return redirect()->route('applications.show', $loan->id)->withSuccess($msg);

            } catch (Exception $e) {

                DB::rollback();
                $request->flash();

                return redirect()->back()->withError(trans('error.general'))->withLoan($loan)->withInput();
            }
                

        }else{

            $request->flash();
            return redirect()->back()->withErrors($errors)->withInput();

        }
    }

    public function store2(Request $request)
    {
        $msg = '';
        $errors = $this->checkValidity($request);

        if(count($errors) > 0){
            $request->flash();
            return redirect()->back()->withErrors($errors)->withInput();
        }

        DB::beginTransaction();

        try {

            // Create Loan Record
            $loan = $this->create_loan($request);

            // Create Endorser
            $endorser = $this->create_endorser($loan, $request->endorsed_by, $request->endorsed_dbname);
            $loan->endorser_id = $endorser->id;

            // Create Guarantor
            $guarantor = $this->create_guarantor($loan, $request->guarantor_by, $request->guarantor_dbname);
            $loan->guarantor_id = $guarantor->id;

            // if($request->special == 1){
            //     // Create company nurse endorsement
            //     $company_nurse = $this->create_company_nurse_endorsement($loan);

            // }
            
            $loan->save();
            
            if(isset($_POST['verify'])){

                $msg = trans('loan.application.verified');
                
            }else{

                // Save and Submit
                // $loan = Loan::find($request->id);
                $loan->ctrl_no = $this->utils->generateCtrlNo();
                if($loan->special == 1)
                    $loan->status = 10;
                else
                    $loan->status = 1;

                $loan->created_at = date('Y-m-d H:i:s');
                $loan->save();

                $msg = trans('loan.application.success');

                // Notification
                $notif = new NotificationController();
                $notif->notifyAppSubmission($loan);

                Event::fire(new LoanCreated($loan));
            }

            DB::commit();

            return redirect()->route('applications.show', $loan->id)->withSuccess($msg);

        } catch (Exception $e) {

            DB::rollback();
            $request->flash();

            return redirect()->back()->withError(trans('error.general'))->withLoan($loan)->withInput();
        }
                

        }

    public function create_loan(Request $request)
    {
        $loan = new Loan();
        if(!empty(trim($request->id)))
            $loan = Loan::find($request->id);

        // Interest percentage
        $interest = Preference::name('interest');
        $interest_special = Preference::name('interest');
        // Employee Term Limits
        $employee = Employee::current()->first();
        $terms = Terms::getRankLimits($employee);

        $loan->setTable('eFundData');
        
        $loan->ctrl_no = '0000';
        $loan->type = $request->type;
        $loan->special = $request->special;
        $loan->EmpID = Auth::user()->employee_id;
        $loan->DBNAME = $employee->DBNAME;

        $loan->loan_amount = $request->loan_amount;
        $loan->local_dir_line = $request->local;

        $loan->purpose = $request->purpose;

        if($request->special == 1){
            // Special Loan, no interest at 2 years payment
            $loan->interest = 0;
            $loan->terms_month = $request->terms;
        }else{
            $loan->interest = $interest->value;
            // count remaining months until december
            $loan->terms_month = $request->terms;
        }

        $loan->total = $this->utils->getTotalLoan(
                $request->loan_amount, 
                $loan->interest, 
                $loan->terms_month);

        $loan->deductions = $this->utils->computeDeductions(
                $loan->terms_month, 
                $request->loan_amount,
                $loan->interest);

        $loan->status = $this->utils->setStatus();
        $loan->save();

        return $loan;
    }

    public function create_endorser(Loan $loan, $endorser_empid, $endorser_dbname)
    {
        $endorser = Endorser::firstOrNew(['eFundData_id' => $loan->id]);
        $endorser->eFundData_id = $loan->id;
        $endorser->EmpID = $endorser_empid;
        $endorser->DBNAME = $endorser_dbname;
        $endorser->save();

        return $endorser;
    }

    public function create_guarantor(Loan $loan, $guarantor_empid, $guarantor_dbname)
    {
        $guarantor = Guarantor::firstOrNew(['eFundData_id' => $loan->id]);
        $guarantor->eFundData_id = $loan->id;
        $guarantor->EmpID = $guarantor_empid;
        $guarantor->DBNAME = $guarantor_dbname;
        $guarantor->save();

        return $guarantor;
    }

    public function create_company_nurse_endorsement(Loan $loan)
    {
        
    }

    public function  checkValidity($request)
    {
        // Loan Application validation and verifier
        $errors = [];

        // Check Standing Balance
        if($this->getStandingBalance($request->id) != NULL && $this->getStandingBalance($request->id) > 0)
            array_push($errors, trans('loan.validation.balance'));

        // Application Type
        if(!$this->validateType($request->type, $request->id))
            array_push($errors, trans('loan.validation.type'));

        // Availment
        if($request->id == 0)
            if($this->validateAvailment())
                array_push($errors, trans('loan.validation.availment'));

        // Terms
        if(!$this->validateTerms($request->type, $request->special, $request->terms))
            array_push($errors, trans('loan.validation.terms'));

        // Regular and Active Employee
        if(!$this->validateEmployeeStatus(Auth::user()->employee_id, Auth::user()->DBNAME))
            array_push($errors, trans('loan.validation.regular'));

        // Minimum Loan amount
        if(!$this->validateMinAmount($request->loan_amount))
            array_push($errors, trans('loan.validation.minimum'));

        // Maximum Loan amount
        $allow_over_max = Preference::name('allow_over_max');
        if($allow_over_max->value != 1)
            if($this->validateMaxAmount($request->loan_amount, $request->special))
                array_push($errors, trans('loan.validation.maximum'));

        // Endorser
        $endorsers = $this->getEndorser();
        if(!$this->validateEndorser($request->endorsed_by, $request->endorsed_dbname)){
            array_push($errors, trans('loan.validation.endorser'));
        }
        
        // else if(!in_array($request->endorsed_by, [$endorsers->SIGNATORYID1,
        // $endorsers->SIGNATORYID2,
        // $endorsers->SIGNATORYID3,
        // $endorsers->SIGNATORYID4,
        // $endorsers->SIGNATORYID5,
        // $endorsers->SIGNATORYID6])){
        //     // Check if endorser belongs to the valid signatories of the employee
        //     array_push($errors, trans('loan.validation.endorser'));
        // }
        
        // Guarantor
        $guarantors = $this->getGuarantor();
        if($this->validateAboveMinAmount($request->loan_amount)){
            if(!$this->validateGuarantor($request->guarantor_by, $request->guarantor_dbname)){
                array_push($errors, trans('loan.validation.guarantor'));
            }else
                if(!$this->validateGuaranteedAmount($request->guarantor_by, $request->guarantor_dbname, $request->loan_amount))
                    array_push($errors, trans('loan.validation.guaranteed_amount'));
            // else 
            //     if(!in_array($request->guarantor_by, [$guarantors->SIGNATORYID1,
            //         $guarantors->SIGNATORYID2,
            //         $guarantors->SIGNATORYID3,
            //         $guarantors->SIGNATORYID4,
            //         $guarantors->SIGNATORYID5,
            //         $guarantors->SIGNATORYID6])){
            //         // Check expected against inputted guarantor
            //         array_push($errors, trans('loan.validation.guarantor'));
            //     }
        }

        return $errors;
    }

    public function getEndorser()
    {
        $endorser = DB::table('viewSignatories')
            ->where('EmpID', Auth::user()->employee_id)
            ->where('DBNAME', Auth::user()->DBNAME)
            ->first();

        return $endorser;
        $valid_signatories = [];

        // Creates a list of valid signatories
        foreach ($endorser as $key => $value) {
            if(in_array($key, ['SIGNATORYID1', 'SIGNATORYID2', 'SIGNATORYID3', 'SIGNATORYID4', 'SIGNATORYID5', 'SIGNATORYID6'])){
                if(!empty($value)){
                    if($this->validateEndorser($value)){
                        array_push($valid_signatories, $value);
                    }
                }
            }
        }

        return $valid_signatories;
    }

    public function getApprovers()
    {
        $approvers = DB::table('viewSignatories')
            ->where('EmpID', Auth::user()->employee_id)
            ->where('DBNAME', Auth::user()->DBNAME)
            ->first();

        return $approvers;
    }

    public function getGuarantor()
    {
        $guarantor = DB::table('viewSignatories')
            ->where('EmpID', Auth::user()->employee_id)
            ->where('DBNAME', Auth::user()->DBNAME)
            ->first();
        return $guarantor;

        $valid_signatories = [];

        // Creates a list of valid signatories
        foreach ($guarantor as $key => $value) {
            if(in_array($key, ['SIGNATORYID1', 'SIGNATORYID2', 'SIGNATORYID3', 'SIGNATORYID4', 'SIGNATORYID5', 'SIGNATORYID6'])){
                if(!empty($value)){
                    if($this->validateEndorser($value)){
                        array_push($valid_signatories, $value);
                    }
                }
            }
        }

        return $valid_signatories;
    }

    public function getPreviousLoan($id  = 0)
    {
        $employee = Employee::current()->first();
        $loan = Loan::employee($employee)
                    ->whereNotIn('status', [0,9])
                    ->where('id', '<>', $id)
                    ->first();

        if(empty($loan))
            return 0;

        return $loan->loan_amount;
    }

    public function getStandingBalance($id  = 0)
    {
        $employee = Employee::current()->first();
        $balance = Loan::employee($employee)
                    ->whereNotIn('status', [0,9])
                    ->where('id', '<>', $id)
                    ->sum('balance');

        return round($balance, 2);
    }

    public function validateAvailment()
    {
        // Loan Application Counts within the current year
        $employee = Employee::current()->first();

        $loans = Loan::employee($employee)->yearly()->notDenied()->count();
        $max_availment = Preference::name('max_availment');

        if($loans >= $max_availment->value)
            return true;
        else
            return false;
    }

    /**
     *
     * Validate Application Type
     * If $id > 0, for verification only
     * else, new application
     * @param int $type Application Type
     * @param int $id Application ID
     * @return boolean
     *
     */
    public function validateType($type, $id = 0)
    {
        return true;
        // Loan Application Counts
        $employee = Employee::current()->first();
        $loans = Loan::employee($employee)->notDenied()->count();

        if($type == 0){
            if($id == 0 && $loans == 0){
                return true;
            }else if($id > 0 && $loans <= 1){
                return true;
            }else{
                return false;
            }
        }else{
            if($id == 0 && $loans > 0){
                return true;
            }else if($id > 0 && $loans > 1){
                return true;
            }else{
                return false;
            }
        }
    }

    public function validateTerms($type = 0, $special = 0, $terms = null)
    {
        /**
         * Terms 
         * For NEW appication can have 12 maximum months regardless of the year.
         * For REAVAILMENT can have 12 maximum months but until the same year only.
         */

        $employee = Employee::current()->first();
        $records = Loan::employee($employee)->notDenied()->count();
        $allowedMonths = $this->utils->getTermMonths($type, $special, $terms);

        if($type == 0)
            if($terms > $allowedMonths)
                return false;

        if($type > 0 || $special == 1)
            if($terms > $allowedMonths)
                return false;

        return true;
    }

    public function validateEmployeeStatus($EmpID, $DB)
    {
        $emp = Employee::where('EmpID', $EmpID)
                ->where('DBNAME', $DB)
                ->regular()->active()->count();

        if($emp > 0)
            return true;
        else
            return false;
    }

    public function validateMinAmount($amount)
    {
        // Employee Term Limits
        $emp = Employee::select('RankDesc')->current()->first();
        $terms = Terms::getRankLimits($emp);

        if($amount < 1000)//$terms->min_loan_amount)
            return false;
        else
            return true;
    }

    public function validateAboveMinAmount($amount)
    {
        // Employee Term Limits
        $emp = Employee::select('RankDesc')->current()->first();
        $terms = Terms::getRankLimits($emp);

        if($amount > $terms->min_loan_amount)
            return true;
        else
            return false;
    }

    public function validateMaxAmount($amount, $special = 0)
    {
        $employee = Employee::select('RankDesc')->current()->first();

        $allow_max_ex = AllowedAboveMaxLoan::where('EmpID', Auth::user()->employee_id)
            ->where('DBName', Auth::user()->DBNAME)
            ->where('ExpiredAt', '>=', date('Y-m-d'))
            ->first();

        if(!empty($allow_max_ex)){
            return false;
        }



        if($special == 0){
            $terms = Terms::getRankLimits($employee);

            
            if($amount > $terms->max_loan_amount)
                return true;
            else
                return false;
        }else{
            $terms = SpecialTerm::getRankLimits($employee);


            if($amount > $terms->max_loan_amount)
                return true;
            else
                return false;
        }
    }

    public function validateEndorser($EmpID, $DB)
    {
        $valid = true;

        $endorser = Employee::where('EmpID', $EmpID)
                    ->where('DBNAME', $DB)
                    ->active()->regular()->first();

        if(empty($endorser))
            return false;

        // Endorser must not be the employee him/herself
        if($EmpID == Auth::user()->employee_id)
            $valid = false;

        $applicant = Employee::where('EmpID', Auth::user()->employee_id)
                    ->where('DBNAME', Auth::user()->DBNAME)
                    ->first();

        // Endorser must not be the same rank of the applicant
        $endorser_rank = $this->utils->getRank($endorser->RankDesc);
        $applicant_rank = $this->utils->getRank($applicant->RankDesc);

        if($endorser_rank <= $applicant_rank)
            $valid = false;

        return $valid;
    }

    public function validateGuarantor($EmpID, $DB)
    {
        $valid = true;

        $guarantor = Employee::where('EmpID', $EmpID)
                    ->where('DBNAME', $DB)
                    ->active()->regular()->first();
                    
        if(empty($guarantor)){
            $valid = false;
            return $valid;
        }

        // Guarantor must not be the employee him/herself
        if($EmpID == Auth::user()->employee_id)
            $valid = false;

        $applicant = Employee::where('EmpID', Auth::user()->employee_id)
            ->where('DBNAME', Auth::user()->DBNAME)
            ->first();

        // Guarantor must not be the same rank of the applicant
        $guarantor_rank = $this->utils->getRank($guarantor->RankDesc);
        $applicant_rank = $this->utils->getRank($applicant->RankDesc);

        if($guarantor_rank <= $applicant_rank)
            $valid = false;

        return $valid;
    }

    public function validateSignatoryRank($EmpID)
    {
        $signatory = Employee::where('EmpID', $EmpID)->first();
        $employee = Employee::current()->first();

    }

    public function validateGuaranteedAmount($EmpID, $DB, $amount)
    {
        $valid = true;
        // Check Guarateed amount total
        $totalGuaranteedAmount = Guarantor::guaranteedAmountLimit($EmpID, $DB)->sum('guaranteed_amount');
        if(empty($totalGuaranteedAmount))
            $totalGuaranteedAmount = 0;

        // Get Employee Rank Limit 
        $employee = Employee::current()->first();
        $terms = Terms::getRankLimits($employee);
        $gAmountLimit = GLimits::limit($EmpID, $DB);

        // No limit
        if($gAmountLimit->Amount == 0)
            return $valid;

        if($totalGuaranteedAmount < $gAmountLimit->Amount){
            // Total guaranteed amount of active accounts
            // is less than the maximum limit of a guarantor's rank
            if($gAmountLimit->Amount - $totalGuaranteedAmount < $amount - $terms->min_loan_amount){
                // Total guaranteed amount is not sufficient to 
                // guarantee min amount of the loan application.
                $valid = false;
            }

        }else{

            $valid = false;

        }

        return $valid;
    }

    public function destroy($id)
    {
        $loan = DB::table('eFundData')->where('id', $id)->first();

        if(!empty($loan))
            if($loan->EmpID != Auth::user()->employee_id)
                abort(403);

        DB::table('eFundData')->where('id', $id)->delete();
        $log = new Log();
        $log->writeOnly('Delete', 'eFundData', $loan);
        return redirect()->route('applications.index')->with('success', trans('loan.application.delete'));
    }

    public function cancel($id)
    {
        $loan = Loan::where('id', $id)->first();
        
        if(!$loan)
            abort(404);

        if($loan->EmpID != Auth()->user()->employee_id)
            abort(404);

        $loan->setTable('eFundData');
        $loan->status = 9;
        $loan->save();

        $endorsement = Endorser::findOrFail($request->id);
        $endorsement->refno = $this->utils->generateReference();
        $endorsement->endorser_status = 0;
        $endorsement->save();

        $loan = Loan::findOrFail($endorsement->eFundData_id);
        $loan->status = $this->utils->setStatus($this->utils->getStatusIndex('denied'));
        $loan->save();

        $endorsements = Endorser::where('id', $loan->endorser_id)->first();
        if($endorsements)  {
            $endorsements->status = 0; 
            $endorsements->save();   
        }

        $guarantors = Guarantor::where('id', $loan->guarantor_id)->first(); 
        if($guarantors)  {
            $guarantors->status = 0; 
            $guarantors->save();   
        }

        return redirect()->back()
            ->withSuccess('Loan has been cancelled successfully!');
        
    }

}
