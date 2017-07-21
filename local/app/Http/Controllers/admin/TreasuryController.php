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
use eFund\Deduction;
use eFund\Preference;
use eFund\Http\Requests;
use eFund\Utilities\Utils;
use eFund\Events\CheckSigned;
use eFund\Events\CheckReleased;
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
        $key = '';
        if(isset($_GET['show']))
            $show = $_GET['show'];

        if(isset($_GET['search']))
            $search = $_GET['search'];

        if(isset($_GET['key']))
            $key = $_GET['key'];

    	$loans = Loan::notDenied()
                    ->where('status', '>=' ,$this->utils->getStatusIndex('treasury'))
                    ->where(function($query) use ($key, $search){
                        $searchRange = '';

                        if(!empty($search))
                            if(in_array($key, ['date', 'release'])){
                                $searchRange = explode('-', $search);

                                if(count($searchRange)==2)
                                    if($key == 'date'){
                                        $query->where('created_at', '>=', $searchRange[0])
                                        ->where('created_at', '<=', $searchRange[1]);
                                    }else{
                                         $query->where('check_released', '>=', date('Y-m-d', strtotime($searchRange[0])))
                                        ->where('check_released', '<=', date('Y-m-d', strtotime($searchRange[1])));
                                    }
                            }else{
                                $query->where('ctrl_no', 'LIKE', '%' . $search . '%')
                                    ->orWhere('cv_no', 'LIKE', '%' . $search . '%')
                                    ->orWhere('check_no', 'LIKE', '%' . $search . '%')
                                    ->orWhere('FullName', 'LIKE', '%' . $search . '%');
                            }
                    })
                    // ->OrWhere('status', $this->utils->getStatusIndex('release'))
                    // ->search($search)
                    ->orderBy('created_at', 'desc')
                    ->paginate($show); 

    	return view('admin.treasury.index')
    		->withUtils($this->utils)
    		->withLoans($loans);
    }

    public function printReport()
    {
        $search = '';
        $key = '';
        if(isset($_GET['show']))
            $show = $_GET['show'];

        if(isset($_GET['search']))
            $search = $_GET['search'];

        if(isset($_GET['key']))
            $key = $_GET['key'];

        
        $loans = Loan::notDenied()
                    ->where('status', '>=' ,$this->utils->getStatusIndex('treasury'))
                    ->where(function($query) use ($key, $search){
                        $searchRange = '';

                        if(!empty($search))
                            if(in_array($key, ['date', 'release'])){
                                $searchRange = explode('-', $search);

                                if(count($searchRange)==2)
                                    if($key == 'date'){
                                        $query->where('created_at', '>=', $searchRange[0])
                                        ->where('created_at', '<=', $searchRange[1]);
                                    }else{
                                         $query->where('check_released', '>=', date('Y-m-d', strtotime($searchRange[0])))
                                        ->where('check_released', '<=', date('Y-m-d', strtotime($searchRange[1])));
                                    }
                            }else{
                                $query->where('ctrl_no', 'LIKE', '%' . $search . '%')
                                    ->orWhere('cv_no', 'LIKE', '%' . $search . '%')
                                    ->orWhere('check_no', 'LIKE', '%' . $search . '%')
                                    ->orWhere('FullName', 'LIKE', '%' . $search . '%');
                            }
                    })
                    ->orderBy('created_at', 'desc')
                    ->get();


        return view('admin.treasury.released')->withLoans($loans);
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

    public function generateCheckVoucher($id){
        try{
            $treasury = Treasury::where('eFundData_id', $id)->first();
            if(empty($treasury))
                $treasury = new Treasury();

            $treasury->eFundData_id = $id;
            $treasury->cv_no = $this->utils->generateCheckVoucherNumber();
            $treasury->cv_date = date('Y-m-d');
            $treasury->created_by = Auth::user()->id;
            $treasury->save();
            
        } catch (Exception $e){
            abort(500);
        }
    }

    public function printCheckVoucher($id)
    {
        try{
            $loan = DB::table('viewTreasury')->where('id', (int)($id))->first();
            if(empty($loan)){
                abort(404);
            }

            return view('admin.treasury.voucher')->withLoan($loan);
        }catch (Exception $e){
            abort(500);
        }
    }

    public function approve(Request $request)
    {
    	if(isset($request->approve)){
            DB::beginTransaction();
    		$treasury = Treasury::where('eFundData_id', $request->id)->first();
            if(empty($treasury))
                $treasury = new Treasury();

    		$treasury->eFundData_id = $request->id;
            $treasury->check_no = $request->check_no;
    		$treasury->check_released = $request->check_released;
    		$treasury->created_by = Auth::user()->id;
    		$treasury->save();

    		$loan = Loan::find($request->id);
    		$loan->status = $this->utils->setStatus($this->utils->getStatusIndex('treasury'));
    		$loan->save();

            Event::fire(new CheckSigned($loan));

            DB::commit();

    		return redirect()->back()->withSuccess(trans('loan.application.cheque'));

    	}else if(isset($request->release)){
            // Released
            DB::beginTransaction();
            $released = date('m-d-y H:i:s');
    		$treasury = Treasury::where('eFundData_id', $request->id)->first();
    		$treasury->released = $released;
    		$treasury->save();
            
            // Set Start of deductions
    		$loan = Loan::find($request->id);
    		$loan->status = $this->utils->setStatus($this->utils->getStatusIndex('release'));
            $loan->start_of_deductions = $this->utils->getStartOfDeduction(date('Y/m/d')); 
    		$loan->save();

            // Loan Application Counts within the current year based on the application date
            $records_this_year = Loan::employee()->yearly()->notDenied()->count();

            // Check validity of terms based on Actual Start of Deduction on 2nd availment
            $term_expected = $this->utils->getTermMonths();
            if($records_this_year > 0){
                if($loan->terms_month > $term_expected){
                    // If applied terms_month is more than the expected terms
                    // Change loan terms and recompute deductions
                    $loan->terms_month = $term_expected;
                    $interest = Preference::name('interest');
                    $loan->total = $this->utils->getTotalLoan($loan->loan_amount, $interest->value, $term_expected);
                    $loan->deductions = $this->utils->computeDeductions($loan->term_mos, $loan->loan_amount);
                    $loan->save();

                    // TODO: Notify Custodian
                }
            }
            // Create Deduction schedule
            DB::update('EXEC spCreateDeductionSchedule ?, ?, ?, ?, ?', [$loan->start_of_deductions, $loan->terms_month, $loan->id, 0, $records_this_year]);
            // Update Balance
            $deductionId = Deduction::select('id')->where('eFundData_id', $loan->id)->first();
            DB::update('EXEC updateBalance ?', [$deductionId->id]);
            DB::commit();
            
            Event::fire(new CheckReleased($loan));

    		return redirect()->back()->withSuccess(trans('loan.application.released'));

    	}else if(isset($request->cancel)){
            DB::beginTransaction();
            $loan = Loan::find($request->id);
            $loan->status = $this->utils->getStatusIndex('denied');
            $loan->save();
            DB::commit();

            return redirect()->back()->withSuccess(trans('loan.application.cheque'));
        }

    }
}
