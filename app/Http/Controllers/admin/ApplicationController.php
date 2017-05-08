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
use eFund\GLimits;
use eFund\Ledger;
use eFund\Endorser;
use eFund\Guarantor;
use eFund\Employee;
use eFund\Preference;
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

        $loans = Loan::orderBy('ctrl_no', 'desc')
                    ->employee()
                    ->search($search)
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
        $records = Loan::employee()->notDenied()->count();
        // Loan Application Counts within the current year
        $records_this_year = Loan::employee()->yearly()->notDenied()->count();
        // Interest percentage
        $interest = Preference::name('interest');
        // Employee Term Limits
        $terms = Terms::getRankLimits($employee->RankDesc);
        // Employee Standing balance
        $balance = $this->getStandingBalance($loan->id);
        // Allowable # of months
        $months = $this->utils->getTermMonths();
        if($records_this_year == 0)
            $months = Preference::name('payment_term');

        $allow_max = Preference::name('allow_over_max');
    	return view('admin.applications.create')->withLoan($loan)
                ->withEmployee($employee)
                ->withRecords($records)
                ->withInterest($interest->value)
                ->withTerms($terms)
                ->withBalance($balance)
                ->withMonths($months)
                ->with('records_this_year', $records_this_year)
                ->with('overMax', $allow_max->value)
                ->withUtils(new Utils());
    }

    public function create()
    {
        // Count saved applications
        $saved = Loan::employee()->status(0)->count();
        if($saved > 0)
            return redirect()->back()->withError('Cannot apply loan! Please submit or cancel other SAVED application first.');

        // Employee Information
    	$employee = Employee::current()->first();
        // Loan Application Counts
    	$records = Loan::employee()->notDenied()->count();
        // Loan Application Counts within the current year based on the application date
        $records_this_year = Loan::employee()->yearly()->notDenied()->count();
        // Interest percentage
    	$interest = Preference::name('interest');
        // Employee Term Limits
    	$terms = Terms::getRankLimits($employee->RankDesc);
        // Employee Standing balance
        $balance = $this->getStandingBalance();
        // Allowable # of months
        $months = 12;
        if($records_this_year > 0)
            $months = $this->utils->getTermMonths();
        $allow_max = Preference::name('allow_over_max');

    	return view('admin.applications.create')
    	->withEmployee($employee)
    	->withRecords($records)
    	->withInterest($interest->value)
    	->withTerms($terms)
        ->withBalance($balance)
        ->withMonths($months)
        ->with('records_this_year', $records_this_year)
        ->with('overMax', $allow_max->value)
        ->withUtils(new Utils());
    }

    public function getEmployee($EmpID = '')
    {
        if(empty($EmpID)){
            $EmpID =  $_GET['EmpID'];
        }

        $employee = Employee::select('FullName')
                    ->where('EmpID', $EmpID)
                    ->where('Active', 1)
                    ->first();

        if(!empty($employee))
            return $employee->FullName;
        else 
            return '';
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
                $terms = Terms::getRankLimits($employee->RankDesc);

                $loan = Loan::find($request->id);
                if(empty($loan))
                    $loan = new Loan();

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

     public function checkValidity($request)
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
        if(!$this->validateTerms($request->term_mos))
            array_push($errors, trans('loan.validation.terms'));

        // Regular and Active Employee
        if(!$this->validateEmployeeStatus(Auth::user()->employee_id))
            array_push($errors, trans('loan.validation.regular'));

        // Minimum Loan amount
        if(!$this->validateMinAmount($request->loan_amount))
            array_push($errors, trans('loan.validation.minimum'));

        // Maximum Loan amount
        $allow_over_max = Preference::name('allow_over_max');
        if($allow_over_max->value != 1)
            if($this->validateMaxAmount($request->loan_amount))
                array_push($errors, trans('loan.validation.maximum'));

        // Endorser
        if(!$this->validateEndorser($request->head))
            array_push($errors, trans('loan.validation.endorser'));

        // Guarantor
        if($this->validateAboveMinAmount($request->loan_amount))
            if(!$this->validateGuarantor($request->surety))
                array_push($errors, trans('loan.validation.guarantor'));
            else
                if(!$this->validateGuaranteedAmount($request->surety, $request->loan_amount))
                    array_push($errors, trans('loan.validation.guaranteed_amount'));

        return $errors;
    }


    public function getStandingBalance($id  = 0)
    {
        $balance = Loan::where('EmpID', Auth::user()->employee_id)
                    ->whereNotIn('status', [0,9])
                    ->where('id', '<>', $id)
                    ->sum('balance');

        return round($balance, 2);
    }

    public function validateAvailment()
    {
        // Loan Application Counts within the current year
        $loans = Loan::employee()->yearly()->notDenied()->count();
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
        // Loan Application Counts
        $loans = Loan::employee()->notDenied()->count();

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

    public function validateTerms($terms)
    {
        $records_this_year = Loan::employee()->yearly()->notDenied()->count();
        $allowedMonths = $this->utils->getTermMonths();
        if($records_this_year > 0)
            if($terms > $allowedMonths)
                return false;

        return true;
    }

    public function validateEmployeeStatus($EmpID)
    {
        $emp = Employee::where('EmpID', $EmpID)->regular()->active()->count();

        if($emp > 0)
            return true;
        else
            return false;
    }

    public function validateMinAmount($amount)
    {
        // Employee Term Limits
        $emp = Employee::select('RankDesc')->current()->first();
        $terms = Terms::getRankLimits($emp->RankDesc);

        if($amount < 1000)//$terms->min_amount)
            return false;
        else
            return true;
    }

    public function validateAboveMinAmount($amount)
    {
        // Employee Term Limits
        $emp = Employee::select('RankDesc')->current()->first();
        $terms = Terms::getRankLimits($emp->RankDesc);

        if($amount > $terms->min_amount)
            return true;
        else
            return false;
    }

    public function validateMaxAmount($amount)
    {
        $emp = Employee::select('RankDesc')->current()->first();
        $terms = Terms::getRankLimits($emp->RankDesc);

        if($amount > $terms->max_amount)
            return true;
        else
            return false;
    }

    public function validateEndorser($EmpID)
    {
        $valid = true;

        if(empty(Employee::where('EmpID', $EmpID)->active()->regular()->first()))
            $valid = false;

        if($EmpID == Auth::user()->employee_id)
            $valid = false;

        return $valid;
    }

    public function validateGuarantor($EmpID)
    {
        $valid = true;

        if($this->validateEmployeeStatus($EmpID)){
            // Check Balance
            $balance = Loan::where('EmpID', $EmpID)
                    ->whereNotIn('status', [0,9])
                    ->sum('balance');

            if($balance > 0)
                $valid = false;

        }

        if($EmpID == Auth::user()->employee_id)
            $valid = false;

        return $valid;
    }

    public function validateGuaranteedAmount($EmpID, $amount)
    {
        $valid = true;
        // Check Guarateed amount total
        $totalGuaranteedAmount = Guarantor::guaranteedAmountLimit($EmpID)->sum('guaranteed_amount');
        // Get Employee Rank Limit 
        $employee = Employee::current()->first();
        $terms = Terms::getRankLimits($employee->RankDesc);
        $gAmountLimit = GLimits::limit($EmpID);

        if($totalGuaranteedAmount < $gAmountLimit->Amount){
            // Total guaranteed amount of active accounts
            // is less than the maximum limit of a guarantor's rank
            if($totalGuaranteedAmount < $amount - $terms->min_amount){
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

}
