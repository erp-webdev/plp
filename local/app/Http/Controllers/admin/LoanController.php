<?php

namespace eFund\Http\Controllers\admin;

use Illuminate\Http\Request;

use PDF;
use Mail;
use DB;
use Auth;
use Event;
use Input;
use Excel;
use Session;
use Storage;
use eFund\Log;
use eFund\Loan;
use eFund\Terms;
use eFund\Ledger;
use eFund\Employee;
use eFund\Treasury;
use eFund\Endorser;
use eFund\Deduction;
use eFund\Guarantor;
use eFund\Http\Requests;
use eFund\Utilities\Utils;
use eFund\Events\LoanPaid;
use eFund\Events\LoanDenied;
use eFund\Events\LoanApproved;
use eFund\Http\Controllers\Controller;
use eFund\Http\Controllers\admin\EmailController;

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
        $show = 10;
        $search = '';
        $sort = 'created_at';
        $sortBy = 'desc';
        $status = 'all';

        if(isset($_GET['show']))
            $show = $_GET['show'];

        if(isset($_GET['search']))
            $search = $_GET['search'];

        if(isset($_GET['sort']))
            $sort = $_GET['sort'];

        if(isset($_GET['by']))
            $sortBy = $_GET['by'];
        
        if(isset($_GET['status']))
            $status = $_GET['status'];

     	$loans = Loan::where('status', '<', $this->utils->getStatusIndex('denied'))
                    ->where(function($query) use ($status){
                        if($status != 'all')
                            return $query->where('status', $status);
                        if($status == 'all')
                            return $query->where('status', '>', $this->utils->getStatusIndex('guarantor'));
                    })
                    ->orderBy($sort, $sortBy)
                    ->search($search)
                    ->paginate($show);

    	return view('admin.loans.index')
    			->withLoans($loans)
    			->withUtils($this->utils);
    }

    public function show($id)
    {
        try {
            $loan = Loan::findOrFail((int)($id));
            $deductions = Deduction::where('eFundData_id', $loan->id)->orderBy('date')->get();
            $balance = Loan::where('EmpID', Auth::user()->employee_id)
                        ->where('DBNAME', Auth::user()->DBNAME)
                        ->whereNotIn('status', [0,8])
                        ->where('id', '<>', $id)
                        ->sum('balance');

            // Loan Application Counts within the current year
            $records_this_year = Loan::where('EmpID', $loan->EmpID)
                                    ->where('DBNAME', $loan->DBNAME)
                                    ->where('id', '<>', $loan->id)
                                    ->yearly()
                                    ->notDenied()
                                    ->count();
            // Employee Info
            $employee = Employee::where('EmpID', $loan->EmpID)
                            ->where('DBNAME', $loan->DBNAME)
                            ->first();
            // Employee Term Limits
            $terms = Terms::getRankLimits($employee);
            // Allowable # of months
            $months = $this->utils->getTermMonths($loan->type, $loan->special);
            if($records_this_year == 0)
                $months = 12;

            return view('admin.loans.loanApproval')
                ->withLoan($loan)
                ->withDeductions($deductions)
                ->withUtils($this->utils)
                ->withBalance($balance)
                ->withTerms($terms)
                ->withMonths($months);

        } catch (Exception $e) {
            abort(500);
        }
        
    }

    public function approve(Request $request)
    {
        DB::beginTransaction();

        $loan = Loan::findOrFail($request->id);
        $loan->terms_month = $request->terms;
        $loan->loan_amount = $request->loan_amount;
        $loan->interest = $request->interest;
        
        $loan->deductions = $this->utils->computeDeductions($request->terms, $request->loan_amount, $loan->interest);
        $loan->total = $this->utils->getTotalLoan($request->loan_amount, $loan->interest, $request->terms);

        if(isset($request->approve)){
            
            $loan->approved = 1;
            $loan->approved_by = Auth::user()->employee_id;
            $loan->approved_at = date('Y-m-d H:i:s');
            $loan->status = $this->utils->setStatus($this->utils->getStatusIndex('officer'));
            $loan->remarks = trim($request->remarks);
            $loan->save();

            Event::fire(new LoanApproved($loan));
            DB::commit();
            return redirect()->route('admin.loan')->withSuccess(trans('loan.application.approved'));   
        }elseif(isset($request->deny)){
            
            $loan->status = $this->utils->getStatusIndex('denied');
            $loan->remarks = $request->remarks;
            $loan->denied_by = Auth::user()->id;
            $loan->denied_by_name = Auth::user()->name;
            $loan->denied_date = date('Y-m-d H:i:s');
            $loan->denied_remarks = '';
            $loan->save();
            
            Event::fire(new LoanDenied($loan));
            DB::commit();
            return redirect()->route('admin.loan')->withSuccess(trans('loan.application.denied'));   
        }elseif(isset($request->calculate)){
           
            $loan->save();

            DB::commit();
            return redirect()->route('admin.loan')->withSuccess(trans('loan.application.calculated'));   
        }


    }

    public function saveDeduction(Request $request)
    {
        DB::beginTransaction();

        $loan = []; 
        if(count($request->eFundData_id) > 0)
            $loan = Loan::where('id', $request->eFundData_id[0])->first();

        // Save deductions on Schedule
        for($i = 0; $i < count($request->id); $i++){
            if(!empty(trim($request->ar_no[$i]))){
                $deduction = Deduction::find($request->id[$i]);
                $deduction->ar_no = $request->ar_no[$i];
                $deduction->amount = $request->amount[$i];
                $deduction->updated_by = Auth::user()->id;
                $deduction->save();

                // If the amount is less than the expected deduction amount per cutoff, 
                // make adjustment on deductions.
                if($request->amount[$i] < $loan->deductions){
                   $this->updateBalance($loan->id, $deduction->date, $loan->interest, $loan->total);
                }

                // Update Balance
                DB::update('EXEC updateBalance ?', [$request->id[$i]]);
            }

        }

        // Save manually added deductions; deductions not specified in the schedule
        for($i = 0; $i < count($request->date); $i++){
            if(!empty(trim($request->date[$i])) || !empty($request->amount1[$i])){
                $deduction = new Deduction();
                $deduction->eFundData_id = $loan->id;
                $deduction->date = $request->date[$i];
                $deduction->ar_no = $request->arno[$i];
                $deduction->amount = $request->amount1[$i];
                $deduction->updated_by = Auth::user()->id;
                $deduction->save();

                // If the amount is less than the expected deduction amount per cutoff, 
                // make adjustment on deductions.
                if($request->amount1[$i] < $loan->deductions){
                   $this->updateBalance($loan->id, $deduction->date, $loan->interest, $loan->total);
                }

                // Update Balance
                DB::update('EXEC updateBalance ?', [$deduction->id]);
            }
        }
        
        DB::commit();

        return redirect()->route('admin.loan');//->withSuccess(trans('loan.application.deduction'));
    }

    /**
     * Update / Adjust balance
     * 
     */
    public function updateBalance($loan_id, $deduction_date, $interest, $total)
    {
         $total_paid = Deduction::where('eFundData_id', $loan_id)
            ->sum('amount');
        $balance = $total - $total_paid;
        $remaining_months_to_pay = Deduction::where('date', '>', $deduction_date)
            ->where('eFundData_id', $loan_id)
            ->count();
        
        $adjusted_balance = $balance * (1 + ($interest/100));
        $new_deduction = $adjusted_balance / $remaining_months_to_pay;
        $new_total = $total_paid + $adjusted_balance;
        Loan::where('id', $loan_id)->update([
            'total' => round($new_total, 2),
            'deductions' => round($new_deduction, 2)
            ]);

    }

    /**
     * Complete Loan applications fo employee. 
     * Fully paid account
     * @param  int $id eFundLoan ID
     * 
     */
    public function complete($id)
    {
        DB::beginTransaction();
        $loan = Loan::findOrFail($id);

        if(round($loan->balance, 2) > 0)
            return redirect()->back()
                ->withError(trans('loan.application.balance'));

        $loan->status = $this->utils->setStatus($loan->status);
        $loan->save();

        Deduction::where('eFundData_id', $id)->whereNull('ar_no')->orWhere('ar_no', '')->update(['ar_no' => '-']);

        Event::fire(new LoanPaid($loan));

        DB::commit();
        return redirect()->back()->withSuccess(trans('loan.application.paid'));
    }

    public function printForm($id)
    {
        $loan = Loan::findOrFail($id);
        $balance = Loan::where('EmpID', Auth::user()->employee_id)
                        ->where('DBNAME', Auth::user()->DBNAME)
                        ->whereNotIn('status', [0,8])
                        ->where('id', '<>', $id)
                        ->sum('balance');

        return view('admin.loans.form')
                ->withLoan($loan)
                ->withBalance($balance)
                ->withUtils(new Utils());
        
    }

    public function printPDFForm($id)
    {
        $loan = Loan::findOrFail($id);
        $balance = Loan::where('EmpID', $loan->EmpID)
                        ->where('DBNAME',$loan->DBNAME)
                        ->whereNotIn('status', [0,8])
                        ->where('id', '<>', $id)
                        ->sum('balance');

        $view = view('admin.loans.form')
                ->withLoan($loan)
                ->withBalance($balance)
                ->withUtils(new Utils());

        $pdf = PDF::loadHTML($view)
            ->setPaper('legal', 'portrait')
            ->setWarnings(false);
            // ->save($loan->FullName . '_' . $loan->ctrl_no . '.pdf');

        $filename = $loan->ctrl_no . '_' . $loan->FullName . '.pdf';
        Storage::disk('forms')
            ->put($filename, $pdf->output());

        return $filename;
        // maatwebsite
        // $utils = new Utils();
        // Excel::load('plp_form_template.xls', function($excel) use ($loan, $balance, $utils) {

        //     // modify stuff
        //     $excel->sheet('Sheet3', function($sheet) use ($loan, $balance, $utils) {
        //         $sheet->setColumnFormat(array(
        //             'H15' => '#,##0.00',
        //             'F25' => '#,##0.00',
        //             'G39' => '#,##0.00',
        //             'G40' => '#,##0.00',
        //             'G43' => '#,##0.00',
        //             'U45' => '#,##0.00',
        //             'U56' => '#,##0.00',
        //         ));

        //         $sheet->cell('S3', $loan->ctrl_no);
        //         if($loan->type == 0)
        //             $sheet->cell('B7', '✓');
        //         else
        //             $sheet->cell('F7', '✓');
                
        //         if($loan->special == 0)
        //             $sheet->cell('B9', '✓');
        //         else
        //             $sheet->cell('F9', '✓');

        //         $sheet->cell('H11', $loan->FullName);
        //         $sheet->cell('H12', $loan->PositionDesc);
        //         $sheet->cell('H13', $loan->HireDate);
        //         $sheet->cell('H14', $loan->PermanencyDate);
        //         $sheet->cell('H15', $loan->loan_amount);
                
        //         $sheet->cell('V6', $loan->created_at);
        //         $sheet->cell('V7', $loan->loc_direct_line);

        //         $sheet->cell('V11', $loan->EmpID);
        //         $sheet->cell('V12', $loan->DeptDesc);
        //         $sheet->cell('V13', 0);
        //         $sheet->cell('U14', $loan->purpose);

        //         $sheet->cell('F18', $loan->FullName);
        //         $sheet->cell('S18', $loan->endorser_FullName);

        //         $sheet->cell('F25', $loan->guaranteed_amount);
        //         $sheet->cell('D31', $loan->guarantor_FullName);
        //         $sheet->cell('D32', $loan->guarantor_refno);

        //         $sheet->cell('G39', $balance);
        //         $sheet->cell('G40', $loan->loan_amount);
        //         $sheet->cell('G41', $loan->interest . '% x ' . $loan->terms_month . ' months');
        //         $sheet->cell('G43', $loan->total);
                
        //         $sheet->cell('R37', $loan->remarks);

        //         $sheet->cell('G45', empty($loan->start_of_deductions) ? '' : $loan->start_of_deductions);
        //         $sheet->cell('G46', $loan->terms_month * 2 );

        //         $sheet->cell('U45', $loan->deductions);
        //         $sheet->cell('U46', $loan->cv_no);
        //         $sheet->cell('U47', empty($loan->check_released) ? '' : $loan->check_released);

        //         if($loan->approved == 1)
        //             $sheet->cell('I52', '✓');
        //         else
        //             $sheet->cell('I53', '✓');

        //         $sheet->cell('J52', $loan->approved_FullName);

        //         $sheet->cell('G56', $loan->FullName);
        //         $sheet->cell('U56', $loan->loan_amount);
        //         $sheet->cell('C64', $loan->FullName);
                
        //         $sheet->cell('E66', date('Y-m-d H:i:s'));
        //         $sheet->cell('W66', '(' . Auth::user()->id . ') ' . strtolower(Auth::user()->name));
                
        //     });
        
        // })->store('xls', storage_path('app/forms'));//->export('pdf');
        //end of maat
        
        //return $filename;
    }

    public function showUpload()
    {
        return view('admin.loans.upload');
    }

    public function previewUpload(Request $request)
    {
        $loans = [];
        $ledgers = [];

        return view('admin.loans.upload')
                ->withLoans($loans)
                ->withLedgers($ledgers);
    }

    public function upload(Request $request)
    {
        $loans = [];
        $ledgers = [];
        $loansWithError = [];

        if(Input::hasFile('fileToUpload')){
            $path = Input::file('fileToUpload')->getRealPath();
            $data = Excel::load($path, function($reader) {})->get();
            $ctr = 0;

            if(!empty($data) && $data->count()){
                foreach ($data as $sheet) {
                    foreach($sheet as $cols){
                        $ctr++;

                        foreach ($cols as $key => $value) {
                            // Sheet columns
                            if($key == 'ctrlno')
                                if(!empty($value))
                                   if(Loan::where('ctrl_no', $value)->count() > 0)
                                        array_push($loansWithError, $this->createError('Loan Ctrl No Exists from the database. '.$sheet->getTitle().'.row['. $ctr .'].column[' . $key .'].value[' . $value .']' , $cols));

                            //Process not empty column
                            if(!empty(trim($key)) && $value != null){

                                if(in_array($key, ['empid', 'mos', 'principal', 'deductions', 'startofdeductions', 'type', 'status']) && empty($value)){
                                    array_push($loansWithError, $this->createError('Required Field. '.$sheet->getTitle().'.row['. $ctr .'].column[' . $key .'].value[' . $value .']' , $cols));
                                }

                                // if($key == 'status'){
                                //     if(!in_array(strtoupper($value), ['PAID', 'INC', 'DENIED']))
                                //         array_push($loansWithError, $this->createError('Invalid Status. '.$sheet->getTitle().'.row['. $ctr .'].column[' . $key .'].value[' . $value .']', $cols));
                                // }

                                if(strtoupper(trim($value)) == 'type')
                                    if($value != 'NEW' || $value != 'REAVAILMENT')
                                        array_push($loansWithError, $this->createError('Invalid Type. '.$sheet->getTitle().'.row['. $ctr .'].column[' . $key .'].value[' . $value .']', $cols));
                            }
                            // Check column data type if it matches required corresponding column
                            $dates = ['appdate', 'startofdeductions'];
                            $numbers = ['mos', 'amount', 'deductions',];
                            // Check date format
                            // if(in_array($key, $dates))
                            //     if(date('Y-m-d H:i:s', strtotime($value)) != $value)
                            //         array_push($loansWithError, $this->createError('Invalid Date. '.$sheet->getTitle().'.row['. $ctr .'].column[' . $key .'].value[' . $value .']', $cols));

                             
                            // Check numeric values
                            if(in_array($key, $numbers))
                                if(!is_numeric($value))
                                    array_push($loansWithError, $this->createError('Invalid Number. '.$sheet->getTitle().'.row['. $ctr .'].column[' . $key .'].value[' . $value .']', $cols));

                        }
                        
                        // Sheets 
                        if($sheet->getTitle() == 'Loans'){
                            array_push($loans, $cols);
                        }
                    }
                }
            }
        }

        if(count($loansWithError) > 0){
            return view('admin.loans.upload')
                ->withLoans($loans)
                ->withLedgers($ledgers)
                ->withErrorss($loansWithError)
                ->withError('Import Failed! Failed to validate records. Please check fields with red background and re-upload the file (or refresh this page).');
        }

        $successLoans = 0; $successLedger = 0;
        
        DB::beginTransaction();
        // Loans from Excel to Database
        foreach ($loans as $loan) {
            // eFundData (Loan)
            $eFundData = new Loan();
            if(empty($loan->ctrlno)){
                // Create random id (5 chars)
                $eFundData->ctrl_no = uniqid();
            }else{
                $eFundData->ctrl_no = $loan->ctrlno;
            }

            $eFundData->EmpID = $loan->empid;
            $eFundData->local_dir_line = $loan->loc;
            $eFundData->terms_month = $loan->mos;
            $eFundData->loan_amount = $loan->principal;
            $eFundData->interest = 1;
            $eFundData->deductions = $loan->deductions;
            $eFundData->start_of_deductions = date('Y-m-d H:i:s', strtotime($loan->startofdeductions));
            $eFundData->approved_by = Auth::user()->employee_id;
            $eFundData->approved_at =  date('Y-m-d H:i:s', strtotime($loan->appdate));
            $eFundData->payroll_verified = 1;
            $eFundData->total = round($loan->principal + ($loan->principal * 0.01 * $loan->mos), 2);

            // Type
            if(strtoupper(trim($loan->type)) == 'NEW')
                $eFundData->type = 0;
            elseif(strtoupper(trim($loan->type)) == 'REAVAILMENT')
                $eFundData->type = 1;
            else{
                $eFundData->type = 1;
                // array_push($loansWithError, $this->createError('Invalid Type', $loan));
                // continue;
            }

            // Status
            if(strtoupper($loan->status) == 'PAID'){
                $eFundData->status = $this->utils->getStatusIndex('paid');
                $eFundData->approved = 1;
            }
            elseif(strtoupper($loan->status) == 'DENIED'){
                $eFundData->status = $this->utils->getStatusIndex('denied');
                $eFundData->approved = 0;
            }
            elseif(strtoupper($loan->status) == 'INC'){
                $eFundData->status = $this->utils->getStatusIndex('inc');
                $eFundData->approved = 1;
            }
            else{
                $eFundData->status = $this->utils->getStatusIndex('inc');
                $eFundData->approved = 1;
            }

            $eFundData->save();
            $log = new Log();
            $log->writeToLog(json_encode($eFundData));

            if(!empty($loan->endorserempid)){
                // Endorser
                $query = [
                        'refno' => $this->utils->generateReference(),
                        'eFundData_id' => $eFundData->id,
                        'EmpID' => $loan->endorserempid,
                        'signed_at' => $loan->approvedat,
                        'status' => 1
                    ];
                $endorser = DB::table('endorsers')->insertGetId($query);

                $log = new Log();
                $log->writeOnly('Insert', 'endorsers', $query);

                $eFundData->endorser_id = $endorser;
                $eFundData->save();
            }

            if(!empty($loan->guarantorempid)){
                // Guarantor
                $query = [
                        'refno' => $this->utils->generateReference(),
                        'eFundData_id' => $eFundData->id,
                        'EmpID' => $loan->guarantorempid,
                        'signed_at' => $eFundData->approvedat,
                        'status' => 1,
                        'guaranteed_amount' => $loan->guaranteedamount
                    ];
                $guarantor = DB::table('guarantors')->insertGetId($query);
                $log->writeOnly('Insert', 'guarantors', $query);

                $eFundData->guarantor_id = $guarantor;
                $eFundData->save();
            }

            // Treasury
            $treasury = new Treasury();
            $treasury->eFundData_id = $eFundData->id;
            $treasury->created_by = Auth::user()->id;
            if(!empty($loan->cvno))
                $treasury->cv_no = $loan->cvno;
            else
                $treasury->cv_no = '-';

            if(!empty($loan->cv_date))
                $treasury->cv_date = $loan->cv_date;
            else
                $treasury->cv_date =  date('Y-m-d H:i:s', strtotime($loan->appdate));;
            
            if(!empty($loan->checkno))
                $treasury->check_no = $loan->checkno;
            else
                $treasury->check_no = '-';

            $treasury->check_released =  date('Y-m-d H:i:s', strtotime($loan->startofdeductions));
            $treasury->released = date('Y-m-d H:i:s', strtotime($loan->startofdeductions));
            $treasury->save();

            $treasury->cv_date = $loan->cv_date;
            $eFundData->created_at = date('Y-m-d H:i:s', strtotime($loan->appdate));
            $eFundData->remarks = '[Uploaded: '. date('ymdHis') .']';
            $eFundData->save();
            $successLoans++;

            // Create deduction list or Ledger
            $deductionDate = date('Y-m-d', strtotime($loan->startofdeductions));
            $balance = $eFundData->total - $loan->deductions;
            for($i = 0; $i < $loan->mos * 2; $i++){
                $deduction = new Deduction();
                $deduction->eFundData_id = $eFundData->id;
                $deduction->date = $deductionDate;
                // Set next deduction date
                if(date('d', strtotime($deductionDate)) == 15){
                    // End of Month (EOM)
                    $deductionDate = date('Y-m-t', strtotime($deductionDate));
                }else{
                    $deductionDate = date('Y-m-15', strtotime("+15 days", strtotime($deductionDate)));
                }

                if(date('Y-m-d', strtotime($deductionDate)) <= date('Y-m-d')){
                    $deduction->ar_no = '-';
                    $deduction->amount = $loan->deductions;
                    $deduction->balance = $balance;

                    if($balance < 0){
                        $deduction->amount -= abs($balance);
                        $deduction->balance = 0;
                        $eFundData->status = $this->utils->getStatusIndex('paid');
                        $eFundData->save();
                    }

                    //Compute balance
                    $balance = $balance - $loan->deductions;
                }
               
                $deduction->updated_by = Auth::user()->id;
                $deduction->updated_at = date('Y-m-d H:i:s');
                $deduction->save();
            }

        }

        DB::commit();

        return view('admin.loans.upload')
                ->withLoans($loansWithError)
                ->withSuccess('Upload Successful! But, skipped '. count($loansWithError) . ' record(s) with error. '. $successLoans . ' Loans uploaded. ');
    }

    public function upload2(Request $request)
    {
        $loans = [];
        $valid = true;
        if(Input::hasFile('fileToUpload')){
            $path = Input::file('fileToUpload')->getRealPath();
            $data = Excel::selectSheets('Loans')->load($path, function($reader) {})->get();

            DB::beginTransaction();
            // Loans from Excel to Database
            foreach ($data as $loan) {
                // validate
                // Check required
                $errors = [];
                if(!empty(trim($loan->employeeid)))
                foreach($loan->toArray() as $key=>$value)  {
                    if(in_array($key, ['startofdeductions', 'balanceamount', 'deductionpercutoff', 
                        'totalpayable', 'interestpercent', 'loanamount', 'termsmonths', 
                        'applicationdate']) && empty(trim($value)) && $key != '0'){
                            array_push($errors, $key);
                            $valid = false;
                        }

                }

                array_push($loans, (object)[
                    'data' => $loan,
                    'errors' => $errors
                ]);

            }

            if(!$valid)
                return view('admin.loans.upload')
                    ->withError('Upload failed!')
                    ->withValid($valid)
                    ->withLoans($loans);
            
            $loans=[];
            foreach ($data as $loan) {

                if(empty(trim($loan->employeeid)))
                    continue;

                // eFundData (Loan)
                $eFundData = new Loan();
                if(empty($loan->controlno)){
                    // Create random id (5 chars)
                    $eFundData->ctrl_no = uniqid('up');
                    $loan->controlno = $eFundData->ctrl_no;
                }else{
                    $eFundData->ctrl_no = $loan->controlno;
                }
    
                $eFundData->EmpID = $loan->employeeid;
                $eFundData->DBNAME = $loan->companycode;
                $eFundData->local_dir_line = $loan->localno;
                $eFundData->terms_month = $loan->termsmonths;
                $eFundData->loan_amount = $loan->loanamount;
                $eFundData->interest = $loan->interestpercent;
                $eFundData->deductions = $loan->deductionpercutoff;
                $eFundData->start_of_deductions = date('Y-m-d H:i:s', strtotime($loan->startofdeductions));
                $eFundData->approved_by = Auth::user()->employee_id;
                $eFundData->approved_at =  date('Y-m-d H:i:s', strtotime($loan->applicationdate));
                $eFundData->created_at =  date('Y-m-d H:i:s', strtotime($loan->applicationdate));
                $eFundData->payroll_verified = 1;
                $eFundData->total = $loan->totalpayable;
                $eFundData->approved = 1;
                
                // NEW
                $eFundData->type = 0;
    
                if($loan->balanceamount > 0 ){
                    $eFundData->status = $this->utils->getStatusIndex('inc');
                }else{
                    $eFundData->status = $this->utils->getStatusIndex('paid');
                }

                $eFundData->save();

                $log = new Log();
                $log->writeToLog(json_encode($eFundData));
    
                // Create deduction list or Ledger
                $deductionDate = date('Y-m-d', strtotime($loan->startofdeductions));
                $balance = $loan->totalpayable;

                for($i = 0; $i < $loan->termsmonths * 2; $i++){

                    $deduction = new Deduction();
                    $deduction->eFundData_id = $eFundData->id;
                    $deduction->date = $deductionDate;
                    
                    // upload balance on first entry of deduction schedule
                    if(date('Y-m-d', strtotime($deductionDate)) <= date('Y-m-d')){
                        $amount = $loan->deductionpercutoff;
                        
                        if($balance - $loan->balanceamount >= $loan->deductionpercutoff){
                            $balance = $balance - $loan->deductionpercutoff;
                        }elseif($balance - $loan->balanceamount == 0){
                            $amount = 0;
                            $balance = $loan->balanceamount;
                        }elseif($balance - $loan->balanceamount < $loan->deductionpercutoff){
                            $amount = $balance - $loan->balanceamount;
                            $balance = $loan->balanceamount;
                        }

                        $deduction->ar_no = '-';
                        $deduction->amount = $amount;
                        $deduction->balance = $balance;
                    }

                    $deduction->updated_by = Auth::user()->id;
                    $deduction->updated_at = date('Y-m-d H:i:s');
                    $deduction->save();

                    // Set next deduction date
                    if(date('d', strtotime($deductionDate)) == 15){
                        // End of Month (EOM)
                        $deductionDate = date('Y-m-t', strtotime($deductionDate));
                    }else{
                        $deductionDate = date('Y-m-15', strtotime("+15 days", strtotime($deductionDate)));
                    }
                }

                // Endorser
                if(!empty($loan->endorseremployeeid)){
                    $query = [
                            'refno' => $this->utils->generateReference(),
                            'eFundData_id' => $eFundData->id,
                            'EmpID' => !empty($loan->endorseremployeeid) ? $loan->endorseremployeeid : '2016-06-0457',
                            'signed_at' => date('Y-m-d H:i:s', strtotime($loan->applicationdate)),
                            'DBNAME' =>!empty($loan->endorsercompanycode) ? $loan->endorsercompanycode : 'GL',
                            'status' => 1
                        ];

                    $endorser = DB::table('endorsers')->insertGetId($query);

                    $log = new Log();
                    $log->writeOnly('Insert', 'endorsers', $query);

                    $eFundData->endorser_id = $endorser;
                    $eFundData->save();
                }
    
                // Guarantor
                if(!empty($loan->guarantoremployeeid) || !empty($loan->guarantorfullname)){
                    $guarantor_empid_based_on_name = '';
                    $guarantor_db_based_on_name = '';

                    if(empty($loan->guarantoremployeeid) && !empty($loan->guarantorfullname)){
                        $guarantor_based_on_name = 
                            Employee::where('FullName', 'LIKE', '%'. str_replace(' ', '%', $loan->guarantorfullname).'%')
                                ->first();

                        if(!empty($guarantor_based_on_name)){
                            $guarantor_empid_based_on_name = $guarantor_based_on_name->EmpID;
                            $guarantor_db_based_on_name = $guarantor_based_on_name->DBNAME;
                        }
                    }

                    $query = [
                            'refno' => $this->utils->generateReference(),
                            'eFundData_id' => $eFundData->id,
                            'EmpID' => !empty($loan->guarantoremployeeid) ? $loan->guarantoremployeeid : $guarantor_empid_based_on_name,
                            'signed_at' => date('Y-m-d H:i:s', strtotime($loan->applicationdate)),
                            'DBNAME' => !empty($loan->guarantorcompanycode) ? $loan->guarantorcompanycode : $guarantor_db_based_on_name,
                            'status' => 1,
                            'guaranteed_amount' => $loan->totalpayable
                        ];

                    $guarantor = DB::table('guarantors')->insertGetId($query);
                    $log->writeOnly('Insert', 'guarantors', $query);

                    $eFundData->guarantor_id = $guarantor;
                    $eFundData->save();
                }
    
                // Treasury
                $treasury = new Treasury();
                $treasury->eFundData_id = $eFundData->id;
                $treasury->created_by = Auth::user()->id;

                if(!empty($loan->cvno))
                    $treasury->cv_no = $loan->cvno;
                else
                    $treasury->cv_no = '-';
    
                if(!empty($loan->cv_date))
                    $treasury->cv_date = $loan->cv_date;
                else
                    $treasury->cv_date =  date('Y-m-d H:i:s', strtotime($loan->applicationdate));;
                
                if(!empty($loan->checkno))
                    $treasury->check_no = $loan->checkno;
                else
                    $treasury->check_no = '-';
    
                $treasury->check_released =  date('Y-m-d H:i:s', strtotime($loan->startofdeductions));
                $treasury->released = date('Y-m-d H:i:s', strtotime($loan->startofdeductions));
                $treasury->save();
                
                // $loan->item['controlno'] = $eFundData->ctrl_no;
                $loan_ = (object)$loan->toArray();
                $loan_->controlno = $eFundData->ctrl_no;

                array_push($loans, (object)[
                    'data' => $loan_,
                    'errors' => $errors
                ]);
    
            }
    
            DB::commit();

            return view('admin.loans.upload')
                ->withValid($valid)
                ->withLoans($loans);

        }
            
    }

    public function createError($error = '', $data)
    {
        return (object)['error' => $error, 'loan' => $data];
    }

    /**
     *
     * Get list of employees affected on the deduction date
     * @param date $date Date of deductions
     * @return array List of employees
     *
     */
    public function getDeductions()
    {
        $empList = [];

        if(isset($_GET['deductionDate'])){
            $date = $_GET['deductionDate'];
            $empList = Ledger::select('id', 'EmpID', 'FullName', 'ctrl_no', 'deductions', 'amount', 'ar_no', 'total', 'COMPANY')->deductionList($date)->get();
        }

        return view('admin.loans.deductions')->with('empList', $empList);
    }

    public function applyBatchDeductions(Request $request)
    {
        DB::beginTransaction();
        for($i = 0; $i < count($request->id); $i++){
            $id = 'id' . $request->id[$i];
            $amount = 'amount' . $request->id[$i];
            $deduction = 'deduction' . $request->id[$i];

            if(isset($request->$id)){

                // $emp = Ledger::find($request->$id)->first();

                // if(empty($emp))
                //     continue;

                if(isset($request->$amount)){

                    Deduction::where('id', $request->id[$i])
                        ->update([
                            'ar_no'         => $request->d_arno,
                            'amount'        => $request->$amount,
                            'updated_by'    => Auth::user()->id
                        ]
                    );

                    // Update Balance
                    DB::update('EXEC updateBalance ?', [$request->$id]);
                }
            }
        }
        DB::commit();


        return redirect()->route('admin.loan')->withSuccess('Deductions Applied successfully!');
    }

    public function showDeduction()
    {
        return view('admin.loans.upload_deductions');
    }

    /**
     * Upload back deductions
     */
    public function uploadDeduction(Request $request)
    {
        $deductions = [];
        $valid = true;
        if(Input::hasFile('fileToUpload')){

            $path = Input::file('fileToUpload')->getRealPath();
            $data = Excel::selectSheets('Deductions')->load($path, function($reader) {})->get();

            DB::beginTransaction();
            foreach ($data as $deduction) {
                // validate
                // Check required
                $errors = (object)[
                    'required' => [],
                    'noActiveLoan' => []
                ];

                if(empty(trim($deduction->companycode))
                    && empty(trim($deduction->employeeid)))
                        continue;
                        
                foreach($deduction->toArray() as $key=>$value)  {
                    

                    if(in_array($key, ['companycode', 'employeeid', 'paydate', 'amountpaid']) && empty(trim($value)) && $key != '0'){
                            array_push($errors->required, $key);
                            $valid = false;
                        }

                }

                // eFundData (Loan)
                $eFundData = Loan::where('EmpID', $deduction->employeeid)
                    ->where('DBNAME', $deduction->companycode)
                    ->where('status', $this->utils->getStatusIndex('inc'))
                    ->first();

                if(!$eFundData){
                    array_push($errors->noActiveLoan, 'No active loan');
                    $valid = false;
                }

                array_push($deductions, (object)[
                    'data' => $deduction,
                    'errors' => $errors
                ]);

            }

            if(!$valid)
                return view('admin.loans.upload_deductions')
                    ->withError('Upload failed!')
                    ->withValid($valid)
                    ->withDeductions($deductions);
            
            foreach ($data as $deduction) {

                if(empty(trim($deduction->companycode))
                    && empty(trim($deduction->employeeid)))
                        continue;

                $eFundData = Loan::where('EmpID', $deduction->employeeid)
                    ->where('DBNAME', $deduction->companycode)
                    ->status($this->utils->getStatusIndex('inc'))
                    ->first();
                    
                $paydate = Deduction::where('eFundData_id', $eFundData->id)
                    ->where('date', $deduction->paydate)
                    ->first();
                    
                if(!$paydate)
                    $paydate = new Deduction();

                $paydate->eFundData_id = $eFundData->id;
                $paydate->date = $deduction->paydate;
                $paydate->ar_no = $deduction->arnumber;
                $paydate->amount = $deduction->amountpaid;
                $paydate->updated_by = Auth()->user()->id;
                $paydate->updated_at = date('Y-m-d H:i:s');
                $paydate->save();

                DB::update('EXEC updateBalance ?', [$paydate->id]);
            }

            DB::commit();

            return view('admin.loans.upload_deductions')
                ->withValid($valid)
                ->withDeductions($deductions);
        }
    }

    /*
     *
     * Send list of all loan applications to payroll 
     * for loan verifications.
     * 
     */
    public function sendPayrollNotif(Request $request)
    {
        $employees = DB::table('viewUserPermissions')->where('permission', 'payroll')->get();

        if(empty($employees)){
            return redirect()->route('admin.loan')->withError('Email was not sent! No user tagged with payroll roles was found!');
        }   

        $loans = $this->getPayrollList();
        if(count($loans) == 0)
            return redirect()->route('admin.loan')->withError('Email was not sent! There are no loan applications for payroll verifications');

        $loans = $this->getFormattedPayrollList();
        $email = new EmailController;

        foreach ($employees as $employee) {
            if(empty($employee->EmailAdd))
                continue;
            
            $args = ['employee' => $employee, 'loansHtml' => $loans];

            $emp = Employee::where('EmpID', $employee->employee_id)
                ->where('DBNAME', $employee->DBNAME)
                ->first();

            if(isset($emp->EmailAdd))
                $email->send($emp, config('preferences.notif_subjects.created', 'Loan Application Notification'), 'emails.payroll_verify_list', $args, $cc = '');
        }

        return redirect()->route('admin.loan')->withSuccess('Email sent!');
    }

    /*
     *
     * List all loan applications for payroll verifications
     * 
     */
    public function getPayrollList()
    {
        // $yesterday = date('Y-m-d 17:01:00',(strtotime ( '-1 day' , strtotime ('Y-m-d'))));
        // $today = date('Y-m-d 17:00:00');

        // $loans = Loan::where('status', $this->utils->getStatusIndex('payroll'))
                    // ->where('created_at', '<' ,date('Y-m-d 17:00:00'))
                    // ->orderBy('ctrl_no')
                    // ->get();

        // All unverified loans
        $loans = Loan::where('status', $this->utils->getStatusIndex('payroll'))->orderBy('ctrl_no')->get();

        return $loans;
    }

    /*
     * Get formatted list of loans for payroll verifications
     */
    public function getFormattedPayrollList()
    {
        $loans = $this->getPayrollList();

        if(count($loans) == 0)
            return 'No loan applications for payroll verifications was found!';

        $html = 
        '<table class="table table-hover table-condensed">
            <thead >
                <th style="padding: 10px">Ctrl #</th>
                <th style="padding: 10px">Company</th>
                <th style="padding: 10px">Employee ID</th>
                <th style="padding: 10px">Employee Name</th>
                <th style="padding: 10px">Applied Date</th>
            </thead>
            <tbody>
        ';

        foreach($loans as $loan){
            $html .= 
                '<tr>
                    <td style="padding: 10px">' . $loan->ctrl_no . '</td>
                    <td style="padding: 10px">' . $loan->COMPANY . '</td>
                    <td style="padding: 10px">' . $loan->EmpID . '</td>
                    <td style="padding: 10px">' . $loan->FullName . '</td>
                    <td style="padding: 10px">' . date('Y-m-d', strtotime($loan->created_at)) . '</td>
                </tr>';
        }

        $html .= '</tbody></table>';

        return $html;
    }

    /*
     * 
     * Recalcute Deduction on missed schedule
     * Includes only previous schedules from the current date
     * 
     */
    public function recalDeductions($id)
    {
        DB::unprepared('exec spUpdateDeduction ' . $id);

        $loan = Loan::find($id);

        $employees = DB::table('viewUserPermissions')->where('permission', 'payroll')->get();
        $utils = new Utils();

        foreach ($employees as $employee) {
            if(empty($employee->EmailAdd))
                continue;
            
            $args = ['loan' => $loan, 'employee' => $employee, 'utils' => $utils];

            $emp = Employee::where('EmpID', $employee->employee_id)
                ->where('DBNAME', $employee->DBNAME)
                ->first();

            if(isset($emp->EmailAdd))
                (new EmailController())->send($emp, config('preferences.notif_subjects.payroll', 'Loan Application Notification'), 'emails.payroll', $args, $cc = '');
            
        }

        // TODO: send to payroll new sched
        return redirect()->back()->withSuccess('Loan deductions updated successfully!');
    }

    /**
     * Get list of loans for Officer's Approval 
     */
    public function getOfficerApproval($ids = NULL)
    {

        return $loan = Loan::where('status', $this->utils->getStatusIndex('officer'))
                ->where(function($query) use ($ids){
                    if(!empty($ids))
                        $query->whereIn('id', $ids);

                })->orderBy('ctrl_no')->get();

    }

    /**
     * Formatted list of officers approval for sending to email
     */
    public function getFormattedOfficerList($ids = null)
    {
        $loans = $this->getOfficerApproval($ids);

        return view('admin.reports.officer_approval')
            ->withLoans($loans);
    }

    /**
     * Send email notifications to officer on list of loans for their approval
     */
    public function sendOfficerList(Request $request)
    {
        $loans = $this->getFormattedOfficerList($request->include);
        $email = new EmailController;

        $args = ['loansHtml' => $loans];

        $to = 'fcanuto@megaworldcorp.com';
        $from = config('preferences.email_from');
        // $from = 'dpascua@megaworldcorp.com';
        $cc = 'mrosales@megaworldcorp.com';
        $utils = new Utils();
        $body = 'emails.blank';
        $subject = 'Personal Loan apps. for approval';

        $mail = Mail::send($body, ['args' => $args, 'utils' => $utils], function($message) use ($to, $subject, $from, $cc){
            $message->bcc('kayag.global@megaworldcorp.com');
            // $message->to('kayag.global@megaworldcorp.com');

            $message->to($to);
            $message->to('tgonzales@megaworldcorp.com');
            $message->from($from);
            $message->subject($subject);
            $message->cc($cc);  
            $message->cc('dpascua@megaworldcorp.com');  
        });

        $log = new Log();
        $log->writeOnly('Info', 'email', ['email' => $to, 'subject' => $subject, 'response' => $mail]);
        
        return redirect()->route('admin.loan')->withSuccess('Email sent!');

    }
}
