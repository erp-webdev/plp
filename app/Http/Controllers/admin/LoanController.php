<?php

namespace eFund\Http\Controllers\admin;

use Illuminate\Http\Request;

use DB;
use Auth;
use Event;
use Input;
use Excel;
use Session;
use eFund\Loan;
use eFund\Terms;
use eFund\Employee;
use eFund\Deduction;
use eFund\Http\Requests;
use eFund\Utilities\Utils;
use eFund\Events\LoanPaid;
use eFund\Events\LoanApproved;
use eFund\Http\Controllers\Controller;

class LoanController extends Controller
{
	private $utils;

	function __construct()
	{
        Session::set('menu', 'loans');
        $this->utils = new Utils();
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
                    ->search($search)
                    ->paginate($show);

    	return view('admin.loans.index')
    			->withLoans($loans)
    			->withUtils($this->utils);
    }

    public function show($id)
    {
        try {
            $loan = Loan::findOrFail((int)($id));
            $deductions = Deduction::where('eFundData_id', $loan->id)->orderBy('date')->get();
            $balance = Loan::where('EmpID', Auth::user()->employee_id)
                        ->whereNotIn('status', [0,8])
                        ->where('id', '<>', $id)
                        ->sum('balance');

            // Loan Application Counts within the current year
            $records_this_year = Loan::where('EmpID', $loan->EmpID)
                                    ->where('id', '<>', $loan->id)
                                    ->yearly()
                                    ->notDenied()
                                    ->count();
            // Employee Info
            $employee = Employee::where('EmpID', $loan->EmpID)->first();
            // Employee Term Limits
            $terms = Terms::getRankLimits($employee->RankDesc);
            // Allowable # of months
            $months = $this->utils->getTermMonths();
            if($records_this_year == 0)
                $months = 12;

            return view('admin.loans.loanApproval')
                ->withLoan($loan)
                ->withDeductions($deductions)
                ->withUtils($this->utils)
                ->withBalance($balance)
                ->withTerms($terms)
                ->withMonths($months);

        } catch (Exception $e) {
            abort(500);
        }
        
    }

    public function approve(Request $request)
    {
        DB::beginTransaction();

        $loan = Loan::findOrFail($request->id);
        $loan->terms_month = $request->terms;
        $loan->loan_amount = $request->loan_amount;
        $loan->deductions = $this->utils->computeDeductions($request->terms, $request->loan_amount);
        $loan->total = $this->utils->getTotalLoan($request->loan_amount, $loan->interest, $request->terms);

        if(isset($request->approve)){
            
            $loan->approved = 1;
            $loan->approved_by = Auth::user()->id;
            $loan->approved_at = date('Y-m-d H:i:s');
            $loan->status = $this->utils->setStatus($loan->status);
            $loan->save();

            Event::fire(new LoanApproved($loan));
            DB::commit();
            return redirect()->route('admin.loan')->withSuccess(trans('loan.application.approved'));   
        }elseif(isset($request->deny)){
            
            $loan->status = $this->utils->getStatusIndex('denied');
            $loan->save();
            
            DB::commit();
            return redirect()->route('admin.loan')->withSuccess(trans('loan.application.denied'));   
        }elseif(isset($request->calculate)){
           
            $loan->save();

            DB::commit();
            return redirect()->route('admin.loan')->withSuccess(trans('loan.application.calculated'));   
        }


    }

    public function saveDeduction(Request $request)
    {
        DB::beginTransaction();

        $loan = []; 
        if(count($request->eFundData_id) > 0)
            $loan = Loan::select('total')->where('id', $request->eFundData_id[0])->first();

        $balance = $loan->total;

        for($i = 0; $i < count($request->id); $i++){

            $deduction = Deduction::find($request->id[$i]);
            $deduction->ar_no = $request->ar_no[$i];
            $deduction->amount = $request->amount[$i];
            $balance = $balance - $request->amount[$i];
            $deduction->balance = $balance;
            $deduction->updated_by = Auth::user()->id;
            $deduction->save();
        }
        DB::commit();

        return redirect()->route('admin.loan');//->withSuccess(trans('loan.application.deduction'));
    }

    public function complete($id)
    {
        DB::beginTransaction();
        $loan = Loan::findOrFail($id);

        if($loan->balance > 0)
            return redirect()->back()
                ->withError(trans('loan.application.balance'));

        $loan->status = $this->utils->setStatus($loan->status);
        $loan->save();

        Event::fire(new LoanPaid($loan));

        DB::commit();
        return redirect()->back()->withSuccess(trans('loan.application.paid'));
    }

    public function printForm($id)
    {
        $loan = Loan::findOrFail($id);
        $balance = Loan::where('EmpID', Auth::user()->employee_id)
                        ->whereNotIn('status', [0,8])
                        ->where('id', '<>', $id)
                        ->sum('balance');



        return view('admin.loans.form')
                ->withLoan($loan)
                ->withBalance($balance)
                ->withUtils(new Utils());
        
    }

    public function showUpload()
    {
        return view('admin.loans.upload');
    }

    public function previewUpload(Request $request)
    {
        $loans = [];
        $ledgers = [];

        return view('admin.loans.upload')
                ->withLoans($loans)
                ->withLedgers($ledgers);
    }

    public function upload(Request $request)
    {
        $loans = [];
        $ledgers = [];

        if(Input::hasFile('fileToUpload')){
            $path = Input::file('fileToUpload')->getRealPath();
            $data = Excel::load($path, function($reader) {})->get();
            
            if(!empty($data) && $data->count()){
                foreach ($data as $key => $value) {
                    $loan =['date' => $value->CtrlNo];
                    array_push($loans, $loan);
                }

            }
        }

        return view('admin.loans.upload')
                ->withLoans($loans)
                ->withLedgers($ledgers);
    }
}
