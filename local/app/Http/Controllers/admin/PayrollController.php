<?php

namespace eFund\Http\Controllers\admin;

use Illuminate\Http\Request;

use DB;
use Auth;
use Event;
use Session;
use eFund\Loan;
use eFund\Http\Requests;
use eFund\Utilities\Utils;
use eFund\Events\PayrollVerified;
use eFund\Http\Controllers\Controller;

class PayrollController extends Controller
{
    private $utils;

    function __construct()
    {
    	$this->utils = new Utils();
        Session::set('menu', 'payroll');
    }

    public function index()
    {
        $show = 10;
        $search = '';
        $comp = '';
        if(isset($_GET['show']))
            $show = $_GET['show'];

        if(isset($_GET['search']))
            $search = $_GET['search'];

        if(isset($_GET['company']))
            if($_GET['company'] != 'all')
                $comp = $_GET['company'];

    	$loans = Loan::notDenied()
                    ->where('status', '=', $this->utils->getStatusIndex('payroll'))
                    ->where(function($query) use ($comp){
                        if(!empty(trim($comp)))
                            $query->where('COMPANY', $comp);
                    })
                    ->search($search)
                    ->orderBy('id', 'desc')
                    ->paginate($show);

        $companies = Loan::select('COMPANY')->distinct()->get();

    	return view('admin.payroll.index')
    		->withLoans($loans)
    		->withUtils($this->utils)
            ->withCompanies($companies);
    }

    public function show($id)
    {
    	$loan = Loan::findOrFail($id);

    	return view('admin.payroll.verify')
            ->withLoan($loan)
            ->withUtils($this->utils);
    }

    public function verify(Request $request)
    {
    	if(isset($request->verify)){
            $loan = Loan::findOrFail($request->id);

            $loan->payroll_remarks = $request->payroll_remarks;
            $loan->payroll_verified = 1;
            $loan->status = $this->utils->getStatusIndex('officer');
            $loan->save();

            Event::fire(new PayrollVerified($loan));
            
            return redirect()->route('payroll.index')->withSuccess(trans('loan.application.payroll_verified'));   
       
    	}else{
    		$loan = Loan::findOrFail($request->id);
            $loan->payroll_remarks = $request->payroll_remarks;
            $loan->payroll_verified = 0;
            $loan->status = $this->utils->getStatusIndex('officer');
            $loan->save();
            
            Event::fire(new PayrollVerified($loan));
            
            return redirect()->route('payroll.index')->withSuccess(trans('loan.application.payroll_denied'));  
    	}
    }

    public function getDeductions()
    {
        $empList = [];

        if(isset($_GET['deductionDate'])){
            $date = $_GET['deductionDate'];
            $empList = Ledger::select('EmpID', 'FullName', 'ctrl_no', 'deductions', 'COMPANY', 'total')->deductionList($date)->get();
        }

        return view('admin.loans.deductions')->with('empList', $empList);
    }

}
