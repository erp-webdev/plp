<?php

namespace eFund\Http\Controllers\admin;

use Illuminate\Http\Request;

use PDF;
use App;
use DB;
use Log;
use Auth;
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

    	if(isset($_GET['dateFrom']))
    		$fromDate = $_GET['dateFrom'];
    	
    	if(isset($_GET['dateTo']))
    		$toDate = $_GET['dateTo'];

    	if(isset($_GET['EmpID']))
    		$EmpID = $_GET['EmpID'];
    	
    	if(isset($_GET['status']))
    		$status = $_GET['status'];


    	switch ($type) {
    		case 'payroll':
    			$loans = $this->payrollReport($fromDate, $toDate, $EmpID, $status);

				return view('admin.reports.payrollNotif')
					->withLoans($loans)
					->withUtils($this->utils);
    		
			case 'summary':
				$loans = $this->summaryReport($fromDate, $toDate, $EmpID, $status);

				return view('admin.reports.summary')
					->withLoans($loans)
					->withUtils($this->utils);

			case 'ledger':
				$ledger = $this->ledgerReport($fromDate, $toDate, $EmpID, $status);

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
        $format = 'pdf';

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
        
        $html = '';
        $loans = [];
        $ledger = [];

        if($type == 'payroll')
            $loans = $this->payrollReport($fromDate, $toDate, $EmpID, $status);
        elseif($type == 'summary')
            $loans = $this->summaryReport($fromDate, $toDate, $EmpID, $status);
        elseif($type == 'ledger')
            $ledger = $this->ledgerReport($fromDate, $toDate, $EmpID, $status);

        if($format == 'pdf'){

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

        }elseif($format == 'csv'){

        }
        $this->stream($html);
        
    }

    public function stream($html, $size = 'letter', $orientation = 'landscape')
    {
        $report = (object)['title' => 'Megaworld eFund Report', 'html' => $html];
        $html = view('admin.reports.template')
                ->withReport($report);
        // $this->renderPDF($html, $title = 'OCS_Report', $size = 'letter', $orientation = 'landscape', $warning = false, $paging = true);


        $dompdf = new Dompdf();
        $dompdf->loadHTML($html);
        $dompdf->setPaper($size, $orientation);
        $dompdf->render();
        $dompdf->stream();
    }

    public function payrollReport($fromDate, $toDate, $EmpID, $status)
    {
          return Loan:: where(function($query) use ($fromDate, $toDate, $EmpID, $status){
                    if(!empty($fromDate) && !empty($toDate)){
                        $query->where('start_of_deductions', '>=', $fromDate)->where('start_of_deductions', '<=', $toDate)->orderBy('start_of_deductions', 'desc');
                    }

                    if(!empty($EmpID)){
                        $query->where('EmpID', $EmpID);
                    }

                    if(!empty($status)){
                        if($status == 1) // Paid
                            $query->where('status', 7);
                        elseif($status == 2)
                            $query->where('status', 6);
                        elseif($status == 3)
                            $query->where('status', '<', 6);
                        elseif($status == 4)
                            $query->where('status', 8);
                    }
                    
                })->get();
    }

    public function summaryReport($fromDate, $toDate, $EmpID, $status)
    {
        return Loan::where(function($query) use ($fromDate, $toDate, $EmpID, $status){

                if(!empty($fromDate) && !empty($toDate)){
                    $query->where('created_at', '>=', $fromDate)->where('created_at', '<=', $toDate .' 23:59:59')->orderBy('created_at', 'desc');
                }

                if(!empty($EmpID)){
                    $query->where('EmpID', 'LIKE', '%' . $EmpID . '%');
                }

                if(!empty($status)){
                    if($status == 1) // Paid
                        $query->where('status', 7);
                    elseif($status == 2)
                        $query->where('status', 6);
                    elseif($status == 3)
                        $query->where('status', '<', 6);
                    elseif($status == 4)
                        $query->where('status', 8);
                }
                
            })->get();
    }

    public function ledgerReport($fromDate, $toDate, $EmpID, $status)
    {
        return Ledger::where('EmpID', $EmpID)
                ->orderBy('ctrl_no', 'asc')->get();
    }

    public function renderPDF($view, $title = 'OCS_Report', $size = 'letter', $orientation = 'landscape', $warning = false, $paging = true)
    {
        $pdf = App::make('dompdf.wrapper');
        $pdf->setPaper($size, $orientation)->setWarnings($warning);
        $pdf->loadHTML($view);
        $pdf->output();
        return $pdf->stream('asdf.pdf');
        if($paging){
          $dom_pdf = $pdf->getDomPDF();

          $user = DB::table('viewUsers')->select('FName', 'LName')->where('id', Auth::user()->id)->first();
          $printedby = 'Printed by:' . $user->LName . ', ' . substr($user->FName, 0, 1);
          $printed = 'Printed at: ' . date('m/d/Y g:i A');
          $canvas = $dom_pdf->get_canvas();
          // $canvas->page_text($this->getX($size, $orientation), $this->getY($size, $orientation) - 10, "Page {PAGE_NUM} of {PAGE_COUNT}", null, 10, array(0, 0, 0));
          // $canvas->page_text(($this->getX($size, $orientation) / 2.5), $this->getY($size, $orientation) - 10, "*** This is a system generated report. No signature required. ***", null, 10, array(0, 0, 0));
          // $canvas->page_text(33, $this->getY($size, $orientation) - 20 , "Megaworld iClearance System", null, 10, array(0, 0, 0));
          // $canvas->page_text(33, $this->getY($size, $orientation) - 10, $printed, null, 10, array(0, 0, 0));
          // $canvas->page_text(33, $this->getY($size, $orientation), $printedby, null, 10, array(0, 0, 0));
        }
        return $pdf->download('sample.pdf');
    }

}
