<?php

namespace eFund\Http\Controllers\admin;

use Illuminate\Http\Request;

use DB;
use Auth;
use Event;
use Session;
use eFund\Loan;
use eFund\Endorser;
use eFund\Http\Requests;
use eFund\Utilities\Utils;
use eFund\Events\LoanDenied;
use eFund\Events\EndorsementApproved;
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
        $show = 10;
        $search = '';
        if(isset($_GET['show']))
            $show = $_GET['show'];

        if(isset($_GET['search']))
            $search = $_GET['search'];

    	$endorsements = Endorser::endorsements()
                        ->search($search)
                        ->orderBy('id', 'desc')
                        ->paginate($show);
     
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
        DB::beginTransaction();
        if(isset($_POST['approve'])){
            $endorsement = Endorser::findOrFail($request->id);
            if($endorsement->EmpID != Auth::user()->employee_id)
                abort(403);

            if($endorsement->signed_at != '--')
                return redirect()->back()->withSuccess(trans('loan.application.approved2'));

            $endorsement->refno = $this->utils->generateReference();
            $endorsement->signed_at = date('Y-m-d H:i:s');
            $endorsement->endorser_status = 1;
            $endorsement->save();

            $loan = Loan::findOrFail($endorsement->eFundData_id);
            $loan->status = $this->utils->setStatus($this->utils->getStatusIndex('endorser'), $loan->guarantor_id);
            $loan->save();

            Event::fire(new EndorsementApproved($loan));
            DB::commit();
            return redirect()->route('endorsements.index')
                    ->withSuccess(trans('loan.application.approved'));
        }else if(isset($_POST['deny'])){
                $endorsement = Endorser::findOrFail($request->id);
                if($endorsement->EmpID != Auth::user()->employee_id)
                    abort(403);

                if($endorsement->signed_at != '--')
                    return redirect()->back()->withSuccess(trans('loan.application.denied2'));

                $endorsement->refno = $this->utils->generateReference();
                $endorsement->endorser_status = 0;
                $endorsement->signed_at = date('Y-m-d H:i:s');
                $endorsement->save();

                $loan = Loan::findOrFail($endorsement->eFundData_id);
                $loan->status = $this->utils->setStatus($this->utils->getStatusIndex('denied'));
                $loan->denied_by = Auth::user()->id;
                $loan->denied_by_name = Auth::user()->name;
                $loan->denied_date = date('Y-m-d H:i:s');
                $loan->denied_remarks = '';
                $loan->save();

                Event::fire(new LoanDenied($loan));
            DB::commit();
            return redirect()->route('endorsements.index')
                    ->withSuccess(trans('loan.application.denied'));
        }else{
            // Unknown function
            abort(403);
        }
    }

}