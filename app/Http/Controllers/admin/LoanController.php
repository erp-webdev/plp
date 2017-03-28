<?php

namespace eFund\Http\Controllers\admin;

use Illuminate\Http\Request;

use DB;
use Auth;
use Session;
use eFund\Loan;
use eFund\Deduction;
use eFund\Http\Requests;
use eFund\Utilities\Utils;
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
    	$loans = Loan::orderBy('ctrl_no')->paginate(20);
    	
    	return view('admin.loans.index')
    			->withLoans($loans)
    			->withUtils($this->utils);
    }

    public function show($id)
    {
        try {
            $loan = Loan::findOrFail((int)($id));
            $deductions = Deduction::where('eFundData_id', $loan->id)->orderBy('date')->get();

            return view('admin.loans.loanApproval')
                ->withLoan($loan)
                ->withDeductions($deductions)
                ->withUtils($this->utils);

        } catch (Exception $e) {
            abort(500);
        }
        
    }

    public function approve(Request $request)
    {
        if(isset($request->approve)){

            $loan = Loan::findOrFail($request->id);

            $loan->approved = 1;
            $loan->approved_by = Auth::user()->id;
            $loan->approved_at = date('Y-m-d H:i:s');
            $loan->status = $this->utils->setStatus($loan->status);
            $loan->save();
            
            return redirect()->route('admin.loan')->withSuccess(trans('loan.application.approved'));   
        }else{
            $loan = Loan::findOrFail($request->id);
            $loan->status = 8;
            $loan->save();
            
            return redirect()->route('admin.loan')->withSuccess(trans('loan.application.denied'));   
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
        $loan = Loan::findOrFail($id);

        $loan->status = $this->utils->setStatus($loan->status);
        $loan->save();

        return redirect()->back();
    }
}
