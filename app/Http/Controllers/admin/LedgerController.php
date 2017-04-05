<?php

namespace eFund\Http\Controllers\admin;

use Illuminate\Http\Request;

use Log;
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
    				->paginate($show);

    	return view('admin.ledger.index')
    		->withEmployees($employees)
    		->withUtils($this->utils);
    }

    public function show($EmpID, $showBalance = true)
    {
        if(isset($_GET['bal']))
            if($_GET['bal'] == 'true')
                $showBalance = true;
            else
                $showBalance = false;

    	$ledger = Ledger::where('EmpID', $EmpID)
    		// ->groupBy('ctrl_no', 'id')
    		->orderBy('ctrl_no', 'asc')
    		->paginate(50);

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
        if(isset($_GET['bal']))
            if($_GET['bal'] == 'true')
                $showBalance = true;
            else
                $showBalance = false;

        $ledger = Ledger::where('EmpID', $EmpID)
            // ->groupBy('ctrl_no', 'id')
            ->orderBy('ctrl_no', 'asc')
            ->paginate(50);

        $employee = Employee::where('EmpID', $EmpID)->first();

        $html = view('admin.ledger.ledger')
                ->withLedgers($ledger)
                ->withEmployee($employee)
                ->withUtils($this->utils)
                ->with('showBalance', $showBalance);
       
        $report = (object)['title' => '', 'html' => $html];

        echo view('admin.reports.layout')
                ->withHtml($html)
                ->withReport($report);

        return;
    }
}
