<?php

namespace eFund\Http\Controllers\admin;

use Illuminate\Http\Request;

use Log;
use DB;
use Auth;
use Event;
use Session;
use eFund\Loan;
use eFund\Terms;
use eFund\GLimits;
use eFund\Endorser;
use eFund\Employee;
use eFund\Guarantor;
use eFund\Http\Requests;
use eFund\Utilities\Utils;
use eFund\Events\LoanDenied;
use eFund\Events\GuarantorApproved;
use eFund\Http\Controllers\Controller;

class GuarantorController extends Controller
{
    private $utils;

   function __construct()
	{
        Session::set('menu', 'guarantors');
		$this->utils = new Utils();
	}

     public function index()
    {
    	$guarantors = Guarantor::guarantors()->orderBy('id', 'desc')->paginate(10);

        for ($i=0; $i < count($guarantors); $i++) { 
            $guarantors[$i]->FullName = $guarantors[$i]->FullName;
        }

        $GLimit = GLimits::limit(Auth::user()->employee_id, Auth::user()->DBNAME);
        $GAmount = Guarantor::guaranteedAmountLimit(Auth::user()->employee_id, Auth::user()->DBNAME)->sum('guaranteed_amount');

        $GAmount = $GLimit->Amount - $GAmount;
        if($GAmount < 0)
            $GAmount = 0;

    	return view('admin.guarantors.index')
    			->withGuarantors($guarantors)
                ->with('GAmount', $GAmount) 
    			->withUtils($this->utils);
    }

    public function show($id)
    {
        try {
            $guarantor = Guarantor::findOrFail($id);
            $loan = Loan::findOrFail($guarantor->eFundData_id);
            $employee = Employee::where('EmpID', $loan->EmpID)
                            ->where('DBNAME', $loan->DBNAME)->first();
            $terms = Terms::getRankLimits($employee);

            $limits = GLimits::limit($guarantor->EmpID, $guarantor->DBNAME);
            
            if(empty($limits))
                $limits = 0;
            else 
                $limits = $limits->Amount;

            if($guarantor->EmpID != Auth::user()->employee_id)
                abort(403);

            return view('admin.guarantors.approval')
                ->withLoan($loan)
                ->withTerms($terms)
                ->withLimits($limits)
                ->withUtils($this->utils);

        } catch (Exception $e) {
            abort(500);
        }
    	
    }

    public function approve(Request $request)
    {
        DB::beginTransaction();
        if(isset($_POST['approve'])){
            dd($request->id);
            $guarantor = Guarantor::findOrFail($request->id);
            if($guarantor->EmpID != Auth::user()->employee_id)
                abort(403);

            if($guarantor->signed_at != '--')
                return redirect()->back()->withSuccess(trans('loan.application.approved2'));

            $guarantor->refno = $this->utils->generateReference();
            $guarantor->signed_at = date('Y-m-d H:i:s');
            $guarantor->guarantor_status = 1;
            $guarantor->guaranteed_amount = $request->guaranteed_amount;
            $guarantor->save();

            $loan = Loan::findOrFail($guarantor->eFundData_id);
            $loan->status = $this->utils->setStatus($this->utils->getStatusIndex('guarantor'));
            $loan->save();

            Event::fire(new GuarantorApproved($loan));
            DB::commit();
            return redirect()->route('guarantors.index')
                    ->withSuccess(trans('loan.application.approved'));

        }else if(isset($_POST['deny'])){

                $guarantor = Guarantor::find($request->id);
                if($guarantor->EmpID != Auth::user()->employee_id)
                    abort(403);

                if($guarantor->signed_at != '--')
                    return redirect()->route('guarantors.index')->withSuccess(trans('loan.application.denied2'));

                $guarantor->refno = $this->utils->generateReference();
                $guarantor->guarantor_status = 0;
                $guarantor->signed_at = date('Y-m-d H:i:s');
                $guarantor->save();


                $loan = Loan::findOrFail($guarantor->eFundData_id);
                $loan->status = $this->utils->setStatus($this->utils->getStatusIndex('denied'));
                $loan->save();

                Event::fire(new LoanDenied($loan));
                DB::commit();
                DB::table('endorsers')->where('id', $loan->endorser_id)->delete();

            return redirect()->route('guarantors.index')
                    ->withSuccess(trans('loan.application.denied'));
        }else{
            // unknown function
            abort(403);
        }
    }

}
