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
use eFund\Events\LoanDenied;
use eFund\Events\EndorsementApproved;
use eFund\Http\Controllers\Controller;
use eFund\Events\LoanCreated;

class ValidationController extends Controller
{
	function __construct()
	{
        Session::set('menu', 'validation');
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

    	$validations = Loan::where('special', 1)
                        ->status(10)
                        ->whereNull('company_nurse')
                        ->search($search)
                        ->orderBy('id', 'desc')
                        ->paginate($show);
     
    	return view('admin.validation.index')
    			->withLoans($validations)
    			->withUtils($this->utils);
    }

    public function show($id)
    {
        try {
            $loan = Loan::findOrFail($id);

            dd(Auth::user()->employee_id); 
            if($loan->EmpID != Auth::user()->employee_id)
                abort(403);

            return view('admin.validation.validation')
                ->withLoan($loan)
                ->withUtils($this->utils);
        } catch (Exception $e) {
            abort(500);
        }
    	
    }

    public function approve(Request $request)
    {
        DB::beginTransaction();

        if(isset($_POST['approve'])){

            $loan_validation = Loan::findOrFail($request->id);
            if($loan_validation->EmpID != Auth::user()->employee_id)
                abort(403);

            if(!empty(trim($loan_validation->company_nurse)))
                return redirect()->back()->withSuccess(trans('loan.application.approved2'));

            $loan_validation->company_nurse = Auth()->user()->name;
            $loan_validation->company_nurse_date = date('Y-m-d H:i:s');
            $loan_validation->company_nurse_status = 'VALID';
            $loan_validation->status = 1;
            $loan_validation->save();

            Event::fire(new LoanCreated($loan_validation));
            DB::commit();

            return redirect()->route('validation.index')
                    ->withSuccess(trans('loan.application.approved'));

        }else if(isset($_POST['deny'])){

            $loan_validation = Loan::findOrFail($request->id);
            if($loan_validation->EmpID != Auth::user()->employee_id)
                abort(403);

            if(!empty(trim($loan_validation->company_nurse)))
                return redirect()->back()->withSuccess(trans('loan.application.denied2'));

            $loan_validation->company_nurse = Auth()->user()->name;
            $loan_validation->company_nurse_date = date('Y-m-d H:i:s');
            $loan_validation->company_nurse_status = 'INVALID';
            $loan_validation->save();

            $loan_validation->status = $this->utils->setStatus($this->utils->getStatusIndex('denied'));
            $loan_validation->save();

            Event::fire(new LoanDenied($loan_validation));
            DB::commit();
            return redirect()->route('validation.index')
                    ->withSuccess(trans('loan.application.denied'));
        }else{
            // Unknown function
            abort(403);
        }
    }

}