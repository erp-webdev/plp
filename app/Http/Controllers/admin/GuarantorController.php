<?php

namespace eFund\Http\Controllers\admin;

use Illuminate\Http\Request;

use Log;
use DB;
use Auth;
use Session;
use eFund\Loan;
use eFund\Endorser;
use eFund\Guarantor;
use eFund\Http\Requests;
use eFund\Utilities\Utils;
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
    	$guarantors = Guarantor::guarantors()->orderBy('id')->paginate(20);
        
        for ($i=0; $i < count($guarantors); $i++) { 
            $guarantors[$i]->FullName = $guarantors[$i]->FullName;
        }

    	return view('admin.guarantors.index')
    			->withGuarantors($guarantors)
    			->withUtils($this->utils);
    }

    public function show($id)
    {
        try {
            $guarantor = Guarantor::findOrFail($id);

            if($guarantor->EmpID != Auth::user()->employee_id)
                abort(403);


            return view('admin.guarantors.approval')
                ->withLoan($guarantor)
                ->withUtils($this->utils);
        } catch (Exception $e) {
            abort(500);
        }
    	
    }

    public function approve(Request $request)
    {
        if(isset($_POST['approve'])){
            $guarantor = Guarantor::findOrFail($request->id);
            if($guarantor->EmpID != Auth::user()->employee_id)
                abort(403);

            if($guarantor->signed_at != '--')
                return redirect()->back()->withSuccess(trans('loan.application.approved2'));

            Log::info($request->guaranteed_amount);

            $guarantor->signed_at = date('Y-m-d H:i:s');
            $guarantor->guarantor_status = 1;
            $guarantor->guaranteed_amount = $request->guaranteed_amount;
            $guarantor->save();

            $loan = Loan::findOrFail($guarantor->eFundData_id);
            $loan->status = $this->utils->setStatus(1);
            $loan->save();

            return redirect()->back()
                    ->withSuccess(trans('loan.application.approved'));

        }else if(isset($_POST['deny'])){

                $guarantor = Guarantor::find($request->id);
                if($guarantor->EmpID != Auth::user()->employee_id)
                    abort(403);

                if($guarantor->signed_at != '--')
                    return redirect()->back()->withSuccess(trans('loan.application.denied2'));

                $guarantor->guarantor_status = 0;
                $guarantor->signed_at = date('Y-m-d H:i:s');
                $guarantor->save();


                $loan = Loan::findOrFail($guarantor->eFundData_id);
                $loan->status = $this->utils->setStatus(8);
                $loan->save();

                DB::table('endorsers')->where('id', $loan->endorser_id)->delete();

            return redirect()->back()
                    ->withSuccess(trans('loan.application.denied'));
        }else{
            // unknown function
            abort(403);
        }
    }

}
