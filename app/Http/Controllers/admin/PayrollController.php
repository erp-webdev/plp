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
        if(isset($_GET['show']))
            $show = $_GET['show'];

        if(isset($_GET['search']))
            $search = $_GET['search'];

    	$loans = Loan::notDenied()
                    ->where('status', '>=', $this->utils->getStatusIndex('payroll'))
                    ->search($search)
                    ->orderBy('id', 'desc')
                    ->paginate($show);

    	return view('admin.payroll.index')
    		->withLoans($loans)
    		->withUtils($this->utils);
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

            $loan->payroll_verified = 1;
            $loan->status = $this->utils->setStatus($loan->status);
            $loan->save();

            Event::fire(new PayrollVerified($loan));
            
            return redirect()->route('payroll.index')->withSuccess(trans('loan.application.payroll_verified'));   
       
    	}else{
    		$loan = Loan::findOrFail($request->id);

            $loan->payroll_verified = 0;
            $loan->status = $this->utils->setStatus($loan->status);
            $loan->save();
            
            Event::fire(new PayrollVerified($loan));
            
            return redirect()->route('payroll.index')->withSuccess(trans('loan.application.payroll_denied'));  
    	}
    }

}
