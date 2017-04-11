<?php

namespace eFund\Http\Controllers\admin;

use Illuminate\Http\Request;

use Log;
use DB;
use Auth;
use Event;
use Session;
use eFund\Loan;
use eFund\Treasury;
use eFund\Http\Requests;
use eFund\Utilities\Utils;
use eFund\Events\CheckSigned;
use eFund\Http\Controllers\Controller;


class TreasuryController extends Controller
{
     private $utils;
    private $logs;

	function __construct()
    {
        Session::set('menu', 'treasury');
        $this->utils = new Utils;
    }

    public function index()
    {
        $show = 10;
        $search = '';
        if(isset($_GET['show']))
            $show = $_GET['show'];

        if(isset($_GET['search']))
            $search = $_GET['search'];


    	$loans = Loan::notDenied()->where('status', '>', 4)
                    ->search($search)
                    ->orderBy('created_at', 'desc')
                    ->paginate($show); 

    	return view('admin.treasury.index')
    		->withUtils($this->utils)
    		->withLoans($loans);
    }

    public function show($id)
    {
        try {
            $loan = DB::table('viewTreasury')->where('id', (int)($id))->first();

            if(empty($loan))
                abort(404);

            return view('admin.treasury.check')
                ->withLoan($loan)
                ->withUtils($this->utils);
        } catch (Exception $e) {
            abort(500);
        }
    }

    public function approve(Request $request)
    {
    	if(isset($request->approve)){
            DB::beginTransaction();
    		$treasury = new Treasury();
    		$treasury->eFundData_id = $request->id;
            $treasury->cv_no = $request->cv_no;
    		$treasury->cv_date = $request->cv_date;
            $treasury->check_no = $request->check_no;
    		$treasury->check_released = $request->check_released;
    		$treasury->created_by = Auth::user()->id;
    		$treasury->save();

            // Set Start of deductions
    		$loan = Loan::find($request->id);
    		$loan->status = $this->utils->setStatus($loan->status);
            $loan->start_of_deductions = $this->utils->getStartOfDeduction($treasury->check_released);
    		$loan->save();

            // Create Deduction schedule
            DB::select('EXEC spCreateDeductionSchedule ?, ?, ?, ?', [$loan->start_of_deductions, $loan->terms_month, $loan->id, 0]);

            Event::fire(new CheckSigned($loan));

            DB::commit();

    		return redirect()->back()->withSuccess(trans('loan.application.cheque'));

    	}else{
            DB::beginTransaction();
    		$treasury = Treasury::where('eFundData_id', $request->id)->first();
    		$treasury->released = date('m-d-y H:i:s');
    		$treasury->save();

    		$loan = Loan::find($request->id);
    		$loan->status = $this->utils->setStatus($loan->status);
    		$loan->save();
            DB::commit();
    		return redirect()->back()->withSuccess(trans('loan.application.released'));

    	}

    }
}
