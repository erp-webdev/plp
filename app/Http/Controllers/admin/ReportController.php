<?php

namespace eFund\Http\Controllers\admin;

use Illuminate\Http\Request;

use PDF;
use App;
use DB;
use Log;
use Auth;
use Excel;
use Session;
use eFund\Loan;
use Dompdf\Dompdf;
use eFund\Http\Requests;
use eFund\Utilities\Utils;
use eFund\Http\Controllers\Controller;

class ReportController extends Controller
{
	private $utils;
   
	function __construct()
	{
		Session::set('menu', 'reports');
		$this->utils = new Utils();
	}

    public function index()
    {
    	
    	return view('admin.reports.index');
    }

    public function show($type)
    {
    	$fromDate = '';
    	$toDate = '';
    	$EmpID = '';
    	$status = '';
        $sort = 'FullName';

    	if(isset($_GET['dateFrom']))
    		$fromDate = $_GET['dateFrom'];
    	
    	if(isset($_GET['dateTo']))
    		$toDate = $_GET['dateTo'];

    	if(isset($_GET['EmpID']))
    		$EmpID = $_GET['EmpID'];
    	
    	if(isset($_GET['status']))
    		$status = $_GET['status'];

        if(isset($_GET['sort']))
            $sort = $_GET['sort'];

        $args = [   'dateFrom' => $fromDate, 
                    'toDate' => $toDate, 
                    'EmpID' => $EmpID, 
                    'status' => $status, 
                    'sort' => $sort
                ];

    	switch ($type) {
    		case 'payroll':
    			$loans = $this->payrollReport($args);

				return view('admin.reports.payrollNotif')
					->withLoans($loans)
					->withUtils($this->utils);
    		
			case 'summary':
				$loans = $this->summaryReport($args);

				return view('admin.reports.summary')
					->withLoans($loans)
					->withUtils($this->utils);

			case 'ledger':
				$ledger = $this->ledgerReport($args);

	    		return view('admin.ledger.ledger')
	    			->withLedgers($ledger)
	    			->withUtils($this->utils);
	    			
    		default:
    			return $type;
    	}
		
    }

    public function generate($type)
    {
        $fromDate = '';
        $toDate = '';
        $EmpID = '';
        $status = '';
        $format = 'html';
        $sort = 'FullName';
        $title = 'Megaworld Efund System';

        if(isset($_GET['dateFrom']))
            $fromDate = $_GET['dateFrom'];
        
        if(isset($_GET['dateTo']))
            $toDate = $_GET['dateTo'];

        if(isset($_GET['EmpID']))
            $EmpID = $_GET['EmpID'];
        
        if(isset($_GET['status']))
            $status = $_GET['status'];

         if(isset($_GET['format']))
            $format = $_GET['format'];

        if(isset($_GET['sort']))
            $sort = $_GET['sort'];

        $args = [   'dateFrom' => $fromDate, 
                    'toDate' => $toDate, 
                    'EmpID' => $EmpID, 
                    'status' => $status, 
                    'sort' => $sort
                ];
        
        $html = '';
        $loans = [];
        $ledger = [];

        // Get loan  data
        if($type == 'payroll'){
            $loans = $this->payrollReport($args);
            $title = 'Efund Payroll Deduction List';
        }
        elseif($type == 'summary'){
            $loans = $this->summaryReport($args);
            $title = 'Efund Summary Report - ' . date('Ymd');
        }
        elseif($type == 'ledger'){
            $ledger = $this->ledgerReport($args);
            $title = 'Efund Ledger - ' . $EmpID;
        }

        // format loan data to table
        if($type == 'payroll'){
            $html = view('admin.reports.payrollNotif')
                    ->withLoans($loans)
                    ->withUtils($this->utils);

        }elseif($type == 'summary'){
            $html = view('admin.reports.summary')
                    ->withLoans($loans)
                    ->withUtils($this->utils);
        }elseif(in_array($type, ['ledger'])){
            $html = view('admin.ledger.ledger')
                    ->withLedgers($ledger)
                    ->withUtils($this->utils);
        }

        // Generate output in specified format
        if($format == 'html' || $format == 'pdf'){

            if($type == 'summary')
                return $this->stream($html, $format, 'legal', 'landscape');
            else
                return $this->stream($html, $format);

        }else if($format == 'xlsx' || $format == 'csv'){
            return $this->formatExcel($loans, $type, $format, $title);
        }
        
    }

    public function stream($html, $format = 'html', $size = 'letter', $orientation = 'landscape' )
    {
      
        if($format == 'html'){

            $report = (object)['title' => 'Megaworld EFund Payroll Notification', 'html' => $html];
            $html = view('admin.reports.layout')
                ->withReport($report);

            return $html;

        }elseif($format == 'pdf'){
            
            $report = (object)['title' => 'Megaworld EFund Payroll Notification', 'html' => $html];

            $pdf = PDF::loadView('admin.reports.layout', ['report' => $report])->setPaper($size, $orientation)->setWarnings(false);
            return $pdf->stream('saa.pdf'); 

        }elseif($format == 'xlsx'){

        }elseif($format == 'csv'){

        }
        
    }

    public function payrollReport($args)
    {
          return Loan:: where(function($query) use ($args){
                    if(!empty($arg['fromDate']) && !empty($arg['toDate'])){
                        $query->where('start_of_deductions', '>=', $arg['fromDate'])->where('start_of_deductions', '<=', $arg['toDate']);
                    }

                    if(!empty($args['EmpID'])){
                        $query->where('EmpID', $args['EmpID']);
                    }

                    if(!empty($status)){
                        if($status == 1) // Paid
                            $query->where('status', $this->utils->getStatusIndex('paid'));
                        elseif($status == 2)
                            $query->where('status', $this->utils->getStatusIndex('inc'));
                        elseif($status == 3)
                            $query->where('status', '<', $this->utils->getStatusIndex('inc'));
                        elseif($status == 4)
                            $query->where('status', $this->utils->getStatusIndex('denied'));
                    }
                    
                })->orderBy($args['sort'], 'asc')->get();
    }

    public function summaryReport($args)
    {
        return Loan::where(function($query) use ($args){

                if(!empty($args['fromDate']) && !empty($args['toDate'])){
                    $query->where('created_at', '>=', $args['fromDate'])->where('created_at', '<=', $args['toDate'] .' 23:59:59');
                }

                if(!empty($EmpID)){
                    $query->where('EmpID', 'LIKE', '%' . $args['EmpID'] . '%');
                }

                if(!empty($status)){
                    if($status == 1) // Paid
                        $query->where('status', $this->utils->getStatusIndex('paid'));
                    elseif($status == 2)
                        $query->where('status', $this->utils->getStatusIndex('inc'));
                    elseif($status == 3)
                        $query->where('status', '<', $this->utils->getStatusIndex('inc'));
                    elseif($status == 4)
                        $query->where('status', $this->utils->getStatusIndex('denied'));
                }
                
            })->orderBy($args['sort'], 'asc')->get();
    }

    public function ledgerReport($args)
    {
        return Ledger::where('EmpID', $args['EmpID'])
                ->orderBy($args['sort'], 'asc')->get();
    }

    public function formatExcel($loans, $type, $format = 'xlsx', $title = 'Megaworld EFund System')
    {
        if($type == 'payroll'){

            $data = [];

            foreach($loans as $row){
                $newRow = [
                    'EF Ctrl No.' => $row->ctrl_no,
                    'Employee ID NO.' => $row->EmpID,
                    'Employee Name' => $row->FullName,
                    'Date of Check Release' => date('Y-m-d', strtotime($row->released)),
                    'TOTAL AMOUNT' => number_format($row->total, 2),
                    'TOTAL NO. OF DEDUCTIONS' => $row->terms_month * 2,
                    'Deduction per payday' => number_format($row->deductions, 2),
                    'START OF DEDUCTIONS' => $row->start_of_deductions
                ];
                array_push($data, $newRow);
            }

        }else if($type == 'summary'){
            $data = [];
            
            foreach($loans as $row){
                $newRow = [
                    'Control #' => $row->ctrl_no,
                    'Employee ID' => $row->EmpID,
                    'Employee Name' => $row->FullName,
                    'Guarantor' => $row->guarantor_FullName,
                    'Date of Application' => date('Y-m-d', strtotime($row->created_at)),
                    'CV NO' => $row->cv_no,
                    'CV Date' => date('Y-m-d', strtotime($row->cv_date)),
                    'CHECK NO' => $row->check_no,
                    'Date of check release' => date('Y-m-d', strtotime($row->released)),
                    'Principal' => number_format($row->loan_amount, 2),
                    'LOAN AMOUNT Interest' => number_format($row->loan_amount * $row->int_amount, 2),
                    'Total' => number_format($row->total, 2),
                    'Payment Terms (no. of mos)' => $row->terms_month,
                    'Deduction per payroll period' => number_format($row->deductions, 2),
                    'START OF DEDUCTIONS' => $row->start_of_deductions,
                    'Total amount paid' => number_format($row->paid_amoun, 2),
                    'Balance' => number_format(round($row->balance, 2), 2),
                    'Remarks' => $row->Remarks
                ];

                array_push($data, $newRow);
            }
        }

        $this->downloadExcel($data, $title, $format);
    }

    public function downloadExcel($data, $title = 'Megaworld EFund System', $type = 'xlsx')
    {
        return Excel::create($title, function($excel) use ($data) {
            $excel->sheet('Sheet1', function($sheet) use ($data)
            {
                $sheet->fromArray($data);
            });
        })->download($type);
    }
   
}
