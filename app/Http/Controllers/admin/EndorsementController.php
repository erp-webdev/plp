<?php

namespace eFund\Http\Controllers\admin;

use Illuminate\Http\Request;

use DB;
use Auth;
use Session;
use eFund\Loan;
use eFund\Endorser;
use eFund\Http\Requests;
use eFund\Utilities\Utils;
use eFund\Http\Controllers\Controller;

class EndorsementController extends Controller
{
	function __construct()
	{
        Session::set('menu', 'endorsements');
		$this->utils = new Utils();
	}

     public function index()
    {
    	$endorsements = Endorser::endorsements()->orderBy('id')->paginate(20);
        // for ($i=0; $i < count($endorsements); $i++) { 
        //     $endorsements[$i]->FullName = utf8_encode($endorsements[$i]->FullName);
        //     $endorsements[$i]->guarantor_FullName = utf8_encode($endorsements[$i]->guarantor_FullName);
        // }

    	return view('admin.endorsements.index')
    			->withEndorsements($endorsements)
    			->withUtils($this->utils);
    }

    public function show($id)
    {
        try {
            $endorsement = Endorser::findOrFail($id);

            if($endorsement->EmpID != Auth::user()->employee_id)
                abort(403);

            return view('admin.endorsements.loanApproval')
                ->withLoan($endorsement)
                ->withUtils($this->utils);
        } catch (Exception $e) {
            abort(500);
        }
    	
    }

    public function approve(Request $request)
    {
        if(isset($_POST['approve'])){
                $endorsement = Endorser::findOrFail($request->id);
                if($endorsement->EmpID != Auth::user()->employee_id)
                    abort(403);

                if($endorsement->signed_at != '--')
                    return redirect()->back()->withSuccess(trans('loan.application.approved2'));

                $endorsement->signed_at = date('Y-m-d H:i:s');
                $endorsement->endorser_status = 1;
                $endorsement->save();

                $loan = Loan::findOrFail($endorsement->eFundData_id);
                $loan->status = $this->utils->setStatus(2);
                $loan->save();

            return redirect()->back()
                    ->withSuccess(trans('loan.application.approved'));
        }else if(isset($_POST['deny'])){
                $endorsement = Endorser::findOrFail($request->id);
                if($endorsement->EmpID != Auth::user()->employee_id)
                    abort(403);

                if($endorsement->signed_at != '--')
                    return redirect()->back()->withSuccess(trans('loan.application.denied2'));

                $endorsement->endorser_status = 0;
                $endorsement->signed_at = date('Y-m-d H:i:s');
                $endorsement->save();

                $loan = Loan::findOrFail($endorsement->eFundData_id);
                $loan->status = $this->utils->setStatus(8);
                $loan->save();

            return redirect()->back()
                    ->withSuccess(trans('loan.application.denied'));
        }else{
            // Unknown function
            abort(403);
        }
    }

}