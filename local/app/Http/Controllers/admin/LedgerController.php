<?php

namespace eFund\Http\Controllers\admin;

use Illuminate\Http\Request;

use Log;
use PDF;
use Excel;
use Session;
use eFund\Loan;
use eFund\Ledger;
use eFund\Employee;
use eFund\Http\Requests;
use eFund\Utilities\Utils;
use eFund\Http\Controllers\Controller;

class LedgerController extends Controller
{
    private $utils;

	function __construct()
	{
        Session::set('menu', 'ledger');
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

    	$employees = Employee::whereRaw('EmpID in (SELECT DISTINCT EmpID FROM eFundData)')
                    ->search($search)
    				->orderBy('LName')
                    ->get();

    	return view('admin.ledger.index')
    		->withEmployees($employees)
    		->withUtils($this->utils);
    }

    public function show($EmpID, $showBalance = true)
    {
        $from = '';
        $to = '';

        if(isset($_GET['bal']))
            if($_GET['bal'] == 'true')
                $showBalance = true;
            else
                $showBalance = false;

        if(isset($_GET['to']))
            $to = $_GET['to'];

        if(isset($_GET['from']))
            $from = $_GET['from'];

    	$ledger = Ledger::where('EmpID', $EmpID)->where(function ($query) use ($to, $from){

                    if(!empty($to) && !empty($from))
                        $query->where('created_at', '<=', $to)
                            ->where('created_at', '>=', $from);
                    })
                    ->orderBy('ctrl_no', 'asc')
                    ->get();

        if(count($ledger) <= 0){
            abort(404);
        }

    	$employee = Employee::where('EmpID', $EmpID)->first();
        $balance = Loan::where('EmpID', $EmpID)->sum('balance');

    	return view('admin.ledger.show')
    			->withLedgers($ledger)
    			->withEmployee($employee)
    			->withUtils($this->utils)
                ->withBalance($balance)
                ->with('showBalance', $showBalance);
    }

    public function printLedger($EmpID, $showBalance = true)
    {
        $showBalance = true;
        $format = 'html';
        $from = '';
        $to = '';


        if(isset($_GET['format']))
            $format = $_GET['format'];

        if(isset($_GET['bal']))
            if($_GET['bal'] == 'true')
                $showBalance = true;
            else
                $showBalance = false;

        if(isset($_GET['to']))
            $to = $_GET['to'];

        if(isset($_GET['from']))
            $from = $_GET['from'];

        $ledger = Ledger::where('EmpID', $EmpID)->where(function ($query) use ($to, $from){

                    if(!empty($to) && !empty($from))
                        $query->where('created_at', '<=', $to)
                            ->where('created_at', '>=', $from);
                    })
                    ->orderBy('ctrl_no', 'asc')
                    ->get();

        $employee = Employee::where('EmpID', $EmpID)->first();
        $balance = Loan::where('EmpID', $EmpID)->sum('balance');

        $html = view('admin.ledger.ledger')
                ->withLedgers($ledger)
                ->withEmployee($employee)
                ->withUtils($this->utils)
                ->withBalance($balance)
                ->with('showBalance', $showBalance);

        if($format == 'pdf'){   
            // PDF
            $pdf = PDF::loadHtml($html)->setPaper('legal', 'landscape')->setWarnings(false);

            return $pdf->stream('EFund Ledger - ' . $EmpID . '.pdf');

        }else if($format == 'xls'){

            return $this->formatExcel($html, $EmpID);
        }

        // HTML
        return $html;
        
    }

    public function formatExcel($html, $EmpID)
    {
        header('Content-type: application/excel');
        $filename = 'EFund Ledger - ' . $EmpID . '.xls';
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
