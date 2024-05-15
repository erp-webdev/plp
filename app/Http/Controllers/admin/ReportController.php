<?php

namespace eFund\Http\Controllers\admin;

use Illuminate\Http\Request;

use PDF;
use App;
use DB;
use Log;
use Auth;
use Excel;
use Entrust;
use Session;
use eFund\Loan;
use eFund\Ledger;
use Dompdf\Dompdf;
use eFund\Http\Requests;
use eFund\Utilities\Utils;

use eFund\Http\Controllers\Controller;
use eFund\Http\Controllers\admin\DashboarController;

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
        $sort = 'FullName';
        $format = 'view';
        $title = "Personal Loan Program Report";

        if(isset($_GET['format'])){
            $format = $_GET['format'];
        }

        $filters = [
            'control',
            'EmpID',
            'empName',
            'checkRelease',
            'totalAmount',
            'totalDeduction',
            'deductionPerPayday',
            'startDeduction',
            'endDeduction',
            'created_at',
            'sort',
            'guarantor',
            'cvno',
            'cvdate',
            'checkno',
            'principal',
            'status',
            'interest',
            'payroll',
            'surety',
            'special',
        ];

        $args = [];

        foreach ($filters as $filter) {
            if(isset($_GET[$filter])){
                $args += [$filter => $_GET[$filter]];
            }
        }
        $view = '';

        $loans = [];
        $ledger = [];

    	switch ($type) {
    		case 'payroll':
    			$loans = $this->payrollReport($args);

				$view = view('admin.reports.payrollNotif')
					->withLoans($loans)
					->withUtils($this->utils);
    		break;
			case 'summary':
				$loans = $this->summaryReport($args);

				$view = view('admin.reports.summary')
					->withLoans($loans)
					->withUtils($this->utils);
            break;

			case 'ledger':
				$ledger = $this->ledgerReport($args);

	    		$view = view('admin.ledger.ledger')
	    			->withLedgers($ledger)
	    			->withUtils($this->utils);
            break;

            case 'monthly':
                if(empty($args['created_at']))
                    $args['created_at'] = date('m/d/Y');
                
                $data = $this->monthlyReport($args);
                $view = view('admin.reports.monthly')->withData($data)->withArgs($args);
            break;

            case 'deduction':

               $data = $this->deductionReport($args);
                $view = view('admin.reports.deduction')->withData($data);
            break;

            case 'resigned':

               $data = $this->resignedReport($args);
                $view = view('admin.reports.resigned')->withData($data);
            break;

            case 'fullypaid':
                $data = $this->fullypaidReport($args);
                $view = view('admin.reports.fullypaid')->withData($data);
            break;

    		default:
    			$view = $type;
    	}

        if(in_array($format, ['pdf', 'xlsx', 'html'])){
            return $this->export($format, $type, $view, $title, $loans);

        }else if($format == 'view'){
            return $this->preview($format, $type, $view, $title, $loans);
        }
    }

    public function export($format, $type, $view, $title, $loans = [])
    {
        if($format == 'html' || $format == 'pdf'){

            if($type == 'summary')
                return $this->stream($view, $format, 'legal', 'landscape', "EMPLOYEES' FUND SUMMARY");
            elseif($type == 'payroll')
                return $this->stream($view, $format, 'legal', 'landscape', "EMPLOYEES' FUND PAYROLL NOTIFICATION");
            else
                return $this->stream($view, $format, 'letter', 'landscape', "");

        }else if($format == 'xlsx' || $format == 'csv'){

            return $this->formatExcel($loans, $type, $format, $title, $view);
        }else{
            // Preview
            return $view;

        }
    }

    public function preview($format, $type, $view, $title, $loans = [])
    {
        return $view;
    }

    public function generate($type)
    {
        $fromDate = '';
        $toDate = '';
        $EmpID = '';
        $status = '';
        $format = 'html';
        $sort = 'FullName';
        $title = 'Megaworld PLP System';

        $filters = [
            'control',
            'EmpID',
            'empName',
            'checkRelease',
            'totalAmount',
            'totalDeduction',
            'deductionPerPayday',
            'startDeduction',
            'endDeduction',
            'fromDate',
            'toDate',
            'sort',
            'guarantor',
            'cvno',
            'cvdate',
            'checkno',
            'principal',
            'status',
            'interest',
            'payroll', 
            'special',
            'surety'
        ];

        $args = [];
        $args += ['sort' => $sort];

        foreach ($filters as $filter) {
            if(isset($_GET[$filter])){
                $args += [$filter => $_GET[$filter]];
            }
        }

        $html = '';
        $loans = [];
        $ledger = [];
        // Monthly Report
        $data = [];

        // Get loan  data
        if($type == 'payroll'){
            $loans = $this->payrollReport($args);
            $title = 'PLP Payroll Deduction List';
        }
        elseif($type == 'summary'){
            $loans = $this->summaryReport($args);
            $title = 'PLP Summary Report - ' . date('Ymd');
        }
        elseif($type == 'ledger'){
            $ledger = $this->ledgerReport($args);
            $title = 'PLP Ledger - ' . $EmpID;
        }
        elseif($type == 'monthly'){
            $data = $this->monthlyReport($args);
            $title = "EMPLOYEES' PLP REPORT" . $EmpID;
        }
        elseif($type == 'deduction'){
            $data = $this->deductionReport($args);
            $title = "EMPLOYEES' PLP REPORT" . $EmpID;
        }
        elseif($type == 'resigned'){
            $data = $this->resignedReport($args);
            $title = "EMPLOYEES' PLP REPORT" . $EmpID;
        }elseif($type == 'fullypaid'){
            $data = $this->fullypaidReport($args);
            $title = "EMPLOYEES' PLP REPORT" . $EmpID;
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
        }elseif($type == 'monthly'){
            $html = view('admin.reports.monthly')
                    ->withData($data)
                    ->withUtils($this->utils)
                    ->withArgs($args);
        }elseif($type == 'deduction'){
            $html = view('admin.reports.deduction')
                    ->withData($data)
                    ->withUtils($this->utils);
        }elseif($type == 'resigned'){
            $html = view('admin.reports.resigned')
                    ->withData($data)
                    ->withUtils($this->utils);
        }elseif(in_array($type, ['ledger'])){
            $html = view('admin.ledger.ledger')
                    ->withLedgers($ledger)
                    ->withUtils($this->utils);
        }elseif($type == 'fullypaid'){
            $html = view('admin.reports.fullypaid')
                    ->withData($data)
                    ->withUtils($this->utils);
        }

        // Generate output in specified format
        if($format == 'html' || $format == 'pdf'){

            if($type == 'summary')
                return $this->stream($html, $format, 'legal', 'landscape', "EMPLOYEES' FUND SUMMARY");
            elseif($type == 'payroll')
                return $this->stream($html, $format, 'legal', 'landscape', "EMPLOYEES' FUND PAYROLL NOTIFICATION");
            else
                return $this->stream($html, $format, 'letter', 'landscape', "");

        }else if($format == 'xlsx' || $format == 'csv'){

            return $this->formatExcel($loans, $type, $format, $title, $html);
        }

    }

    public function stream($html, $format = 'html', $size = 'letter', $orientation = 'landscape', $title = "EMPLOYEES' FUND REPORT")
    {

        if($format == 'html'){

            $report = (object)['title' => $title, 'html' => $html];
            $html = view('admin.reports.layout')
                ->withReport($report);

            return $html;

        }elseif($format == 'pdf'){

            $report = (object)['title' => $title, 'html' => $html];

            $pdf = PDF::loadView('admin.reports.layout', ['report' => $report])->setPaper($size, $orientation)->setWarnings(false);
            return $pdf->stream('saa.pdf');

        }elseif($format == 'xlsx'){

        }elseif($format == 'csv'){

        }

    }

    public function payrollReport($args)
    {

          return  Loan:: where(function($query) use ($args){

                    // Check of Release
                    $dateRange = explode("-", $args['checkRelease']);
                    if(!empty(trim($dateRange[0])) && !empty(trim($dateRange[1]))){
                        $query->where('check_released', '>=', trim($dateRange[0]))->where('check_released', '<=', trim($dateRange[1]));
                    }

                    $dateRange = explode("-", $args['startDeduction']);
                    if(!empty(trim($dateRange[0])) && !empty(trim($dateRange[1]))){
                        $query->where('start_of_deductions', '>=', trim($dateRange[0]))->where('start_of_deductions', '<=', trim($dateRange[1]));
                    }

                    $dateRange = explode("-", $args['created_at']);
                    if(!empty(trim($dateRange[0])) && !empty(trim($dateRange[1]))){
                        $query->whereBetween('created_at', [ date('Y-m-d', strtotime(trim($dateRange[0]))), date('Y-m-d', strtotime(trim($dateRange[1])))]);
                    }

                    $names = explode(' ', $args['empName']);
                    foreach ($names as $name) {
                        if(!empty(trim($name)))
                            $query->Where('FullName', 'LIKE', '%' . $name . '%');
                    }

                    if(!empty($args['EmpID'])){
                        $query->where('EmpID', $args['EmpID']);
                    }

                    if(!empty($args['surety'])){
                        $query->where('guarantor_FullName', $args['surety']);
                    }

                    if(!empty($args['status'])){
                        if($args['status'] == 1) // Paid
                            $query->where('status', $this->utils->getStatusIndex('paid'));
                        elseif($args['status'] == 2)
                            $query->where('status', $this->utils->getStatusIndex('inc'));
                        elseif($args['status'] == 3)
                            $query->where('status', '<', $this->utils->getStatusIndex('inc'));
                        elseif($args['status'] == 4)
                            $query->where('status', $this->utils->getStatusIndex('denied'));
                    }

                })->orderBy($args['sort'], 'asc')->get();

    }

    public function summaryReport($args)
    {
        return Loan::where(function($query) use ($args){

                 // Check of Release
                 $dateRange = explode("-", $args['checkRelease']);
                 if(!empty(trim($dateRange[0])) && !empty(trim($dateRange[1]))){
                     $query->where('check_released', '>=', trim($dateRange[0]))->where('check_released', '<=', trim($dateRange[1]));
                 }

                 $dateRange = explode("-", $args['startDeduction']);
                 if(!empty(trim($dateRange[0])) && !empty(trim($dateRange[1]))){
                     $query->where('start_of_deductions', '>=', trim($dateRange[0]))->where('start_of_deductions', '<=', trim($dateRange[1]));
                 }

                 $dateRange = explode("-", $args['created_at']);
                 if(!empty(trim($dateRange[0])) && !empty(trim($dateRange[1]))){
                     $query->whereBetween('created_at', [ date('Y-m-d', strtotime(trim($dateRange[0]))), date('Y-m-d', strtotime(trim($dateRange[1])))]);
                 }

                 $names = explode(' ', $args['empName']);
                 foreach ($names as $name) {
                     if(!empty(trim($name)))
                         $query->where('FullName', 'LIKE', '%' . $name . '%');
                 }

                if(!empty($args['EmpID'])){
                    $query->where('EmpID', 'LIKE', '%' . $args['EmpID'] . '%');
                }

                if(!empty($args['surety'])){
                    $query->where('guarantor_FullName', 'LIKE', '%' . $args['surety'] . '%');
                }

                // $query->where('special', $args['special']);

                if(!empty($args['status'])){
                    if($args['status'] == 1) // Paid
                        $query->where('status', $this->utils->getStatusIndex('paid'));
                    elseif($args['status'] == 2)
                        $query->where('status', $this->utils->getStatusIndex('inc'));
                    elseif($args['status'] == 3)
                        $query->where('status', '<', $this->utils->getStatusIndex('inc'));
                    elseif($args['status'] == 4)
                        $query->where('status', $this->utils->getStatusIndex('denied'));
                }

                $query->where('status', '<>', $this->utils->getStatusIndex('denied'));

            })->orderBy($args['sort'], 'asc')->get();
    }

    public function ledgerReport($args)
    {
        return Ledger::where('EmpID', $args['EmpID'])
                ->orderBy($args['sort'], 'asc')->get();
    }

    public function monthlyReport($args)
    {
        // $year_prev = DB::select('EXEC spGetPreviousYearOutstandingBalance');

        // $year_cur = DB::table('viewMonthlyReport')
        //                 ->where('app_year', date('Y'))
        //                 ->orderBy('app_month', 'asc')->get();

        // $total = DB::select('EXEC spGetTotalOutstandingBalance');

        // $records = DB::table('viewMonthlyReport')->get();
        $dateRange = explode("-", $args['created_at']);

        // set maximum execution time
        ini_set('max_execution_time', 60000);
        set_time_limit(0);
        
        $records = DB::select('EXEC spMonthlyReport ?, ?', [date('Y-m-d', strtotime($dateRange[0])), date('Y-m-d', strtotime($dateRange[1]))]);
        
        return $records;

        return (object)[
            'year_prev' => $year_prev,
            'year_cur'  => $year_cur,
            'total'     => $total
        ];

    }

    public function deductionReport($args)
    {
        $employees = DB::table('viewEmployeeWithNoDeductions')->where('date', $args['payroll'])->get();
        $total = DB::select('EXEC spGetTotalEmployeesWithNoDeductions ?', [$args['payroll']]);

        return (object)[
            'employees' => $employees,
            'total' => $total,
            'payroll' => $args['payroll']
        ];
    }

    public function resignedReport($args)
    {
        $employees = DB::table('viewResignedEmployeesWithBalance')->get();
        $total = DB::select('EXEC spGetTotalResignedEmployeesWithBalance');

        return (object)[
            'employees' => $employees,
            'total' => $total
        ];
    }

    public function fullypaidReport($args)
    {
        $employees = DB::table('LastAmortizationSchedule')
            ->where('LastDeduction', $args['endDeduction'])
            ->get();
        
        return (object)[
            'employees' => $employees
        ];
    }

    public function payrollDeductionListReport($args)
    {
        
    }


    public function formatExcel($loans, $type, $format = 'xlsx', $title = 'Megaworld PLP System', $html = [])
    {
        $utils = new Utils();
        if($type == 'payroll'){

            $data = [];

            foreach($loans as $row){
                $newRow = [
                    'PLP Ctrl No.' => $row->ctrl_no,
                    'Company' => $row->COMPANY,
                    'Employee ID NO.' => $row->EmpID,
                    'Employee Name' => $row->FullName,
                    'Surety' => $row->guarantor_FullName,
                    'Date of Check Release' => date('Y-m-d', strtotime($row->released)),
                    'TOTAL AMOUNT' => number_format($row->total, 2),
                    'TOTAL NO. OF DEDUCTIONS' => $row->terms_month * 2,
                    'Deduction per payday' => number_format($row->deductions, 2),
                    'START OF DEDUCTIONS' => $row->start_of_deductions,
                    'Status' => $utils->getStatus($row->status)
                ];
                array_push($data, $newRow);
            }

        }else if($type == 'summary'){
            $data = [];

            foreach($loans as $row){
                $newRow = [
                    'Control #' => $row->ctrl_no,
                    'Company' => $row->COMPANY,
                    'Employee ID' => $row->EmpID,
                    'Employee Name' => $row->FullName,
                    'Guarantor' => $row->guarantor_FullName,
                    'Date of Application' => date('Y-m-d', strtotime($row->created_at)),
                    'CV NO' => $row->cv_no,
                    'CV Date' => date('Y-m-d', strtotime($row->cv_date)),
                    'CHECK NO' => $row->check_no,
                    'Date of check release' => date('Y-m-d', strtotime($row->released)),
                    'Principal' => number_format($row->loan_amount, 2),
					// 'LOAN AMOUNT Interest' => number_format($row->loan_amount * $row->int_amount, 2),
                    'LOAN AMOUNT Interest' => number_format($row->int_amount, 2),
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
        }else{
            header('Content-type: application/excel');
            $filename = 'PLP REPORT.xls';
            header('Content-Disposition: attachment; filename='.$filename);

            $data = '<html xmlns:x="urn:schemas-microsoft-com:office:excel">
            <head>
                <!--[if gte mso 9]>
                <xml>
                    <x:ExcelWorkbook>
                        <x:ExcelWorksheets>
                            <x:ExcelWorksheet>
                                <x:Name>Sheet 1</x:Name>
                                <x:WorksheetOptions>
                                    <x:Print>
                                        <x:ValidPrinterInfo/>
                                    </x:Print>
                                </x:WorksheetOptions>
                            </x:ExcelWorksheet>
                        </x:ExcelWorksheets>
                    </x:ExcelWorkbook>
                </xml>
                <![endif]-->
            </head>

            <body>
               '. $html .'
            </body></html>';

            return $data;
        }

        $this->downloadExcel($data, $title, $format);
    }

    public function downloadExcel($data, $title = 'Megaworld PLP System', $type = 'xlsx')
    {
        return Excel::create($title, function($excel) use ($data) {
            $excel->sheet('Sheet1', function($sheet) use ($data)
            {
                $sheet->fromArray($data);
            });
        })->download($type);
    }

    public function printChart()
    {
        $dashboard = new DashboardController();

        $data = (object)[];

        if(Entrust::can(['officer', 'custodian'])){
            // Yearly Application Statistics
            $data->appDatasets = $dashboard->appDatasets();
            // Rank Statistics
            $data->rankDatasets = $dashboard->rankDatasets();
            $data->stats = DB::table('DashboardView')->first();
            // Yearly Income (5 years)
            $data->incomeDatasets = $dashboard->incomeDatasets();

            // Monthly Income of the current year
            $data->IncomeMonthlyDatasets = $dashboard->incomeMonthlyDatasets(date('Y'));
        }

        $html = view('admin.charts')
                ->with('data', $data);

        return $this->stream($html, 'html', 'letter', 'landscape', 'Megaworld PLP Statistics');
    }
}
