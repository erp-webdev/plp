<?php

namespace eFund\Http\Controllers\admin;
use Illuminate\Http\Request;
use eFund\Http\Requests;
use eFund\Http\Controllers\Controller;
use Session;
use Redirect;
use eFund\Events\SendMail;
use Event;
use Log;
use DB;
use Auth;
use eFund\job;
use eFund\Endorser;
use Entrust;

class DashboardController extends Controller
{
	function __construct()
	{
		Session::set('menu', 'dashboard');
	}

    public function index()
    {
        $data = (object)[];

        if(Entrust::can(['officer', 'custodian'])){
            // Yearly Application Statistics
            $data->appDatasets = $this->appDatasets();

            // Rank Statistics
            $data->rankDatasets = $this->rankDatasets();

            $data->stats = DB::table('DashboardView')->first();
        }

        // echo json_encode($data->rankDatasets->yearly); exit;

    	return view('admin.index')
                ->with('data', $data);
        // ->with('error', $error);
    }

    /*=====================================================
    =            Yearly Application Statistics            =
    =====================================================*/
    public function appDatasets()
    {
        $appDatasets = [];

        if(Entrust::can(['officer', 'custodian'])){
            $thisYear = date('Y');
            $lastYear = date("Y",strtotime("-1 year"));

            $lastYearCount = DB::table('DashboardView')->whereBetween('approved_at', [$lastYear . '-01-01 00:00:00', $lastYear . '-12-31 23:59:59'])->count();
            $thisYearCount = DB::table('DashboardView')->whereBetween('approved_at', [$thisYear . '-01-01 00:00:00', $thisYear . '-12-31 23:59:59'])->count();
            $appLastYear = $this->getMonthData($lastYear);
            $appThisYear = $this->getMonthData($thisYear);

            if($lastYearCount > 0){
                $prop = (object)[
                            'type'  => 'bar',
                            'label' => 'EFund Applications ' . $lastYear,
                            'data'  => $appLastYear,
                            ];

                array_push($appDatasets, $prop);
            }

            if($thisYearCount > 0){

                $prop = (object)[
                    'type'              => 'bar',
                    'label'             => 'EFund Applications ' . $thisYear,
                    'data'              => $appThisYear,
                    'borderWidth'       => 0,
                    'backgroundColor'   => 
                        [
                            '#3fa7fc',
                            '#f04aac',
                            '#23a916',
                            '#a259cf',
                            '#f7d92d',
                            '#919191',
                            '#2e5282',
                            '#33c499',
                            '#f8ac25',
                            '#f65829',
                            '#76430c',
                            '#ca1617'
                        ],
                ];

                array_push($appDatasets, $prop);
                return $appDatasets;
            }
        } 
    }

    public function getMonthData($year)
    {

        $year = [
            DB::table('DashboardView')->whereBetween('approved_at', [$year . '-01-01 00:00:00', $year . '-01-' . $this->getDaysOfMonth($year, '01') . ' 23:59:59'])->count(),       
            DB::table('DashboardView')->whereBetween('approved_at', [$year . '-02-01 00:00:00', $year . '-02-' . $this->getDaysOfMonth($year, '02') . ' 23:59:59'])->count(),       
            DB::table('DashboardView')->whereBetween('approved_at', [$year . '-03-01 00:00:00', $year . '-03-' . $this->getDaysOfMonth($year, '03') . ' 23:59:59'])->count(),       
            DB::table('DashboardView')->whereBetween('approved_at', [$year . '-04-01 00:00:00', $year . '-04-' . $this->getDaysOfMonth($year, '04') . ' 23:59:59'])->count(),       
            DB::table('DashboardView')->whereBetween('approved_at', [$year . '-05-01 00:00:00', $year . '-05-' . $this->getDaysOfMonth($year, '05') . ' 23:59:59'])->count(),       
            DB::table('DashboardView')->whereBetween('approved_at', [$year . '-06-01 00:00:00', $year . '-06-' . $this->getDaysOfMonth($year, '06') . ' 23:59:59'])->count(),       
            DB::table('DashboardView')->whereBetween('approved_at', [$year . '-07-01 00:00:00', $year . '-07-' . $this->getDaysOfMonth($year, '07') . ' 23:59:59'])->count(),       
            DB::table('DashboardView')->whereBetween('approved_at', [$year . '-08-01 00:00:00', $year . '-08-' . $this->getDaysOfMonth($year, '08') . ' 23:59:59'])->count(),       
            DB::table('DashboardView')->whereBetween('approved_at', [$year . '-09-01 00:00:00', $year . '-09-' . $this->getDaysOfMonth($year, '09') . ' 23:59:59'])->count(),       
            DB::table('DashboardView')->whereBetween('approved_at', [$year . '-10-01 00:00:00', $year . '-10-' . $this->getDaysOfMonth($year, '10') . ' 23:59:59'])->count(),       
            DB::table('DashboardView')->whereBetween('approved_at', [$year . '-11-01 00:00:00', $year . '-11-' . $this->getDaysOfMonth($year, '11') . ' 23:59:59'])->count(),       
            DB::table('DashboardView')->whereBetween('approved_at', [$year . '-12-01 00:00:00', $year . '-12-' . $this->getDaysOfMonth($year, '12') . ' 23:59:59'])->count(),       
        ];

        return $year;
    }

    public function getDaysOfMonth($Y, $m)
    {
        $date = new \DateTime(date( $Y . '-'. $m .'-d'));
        $date->format($Y . '-'. $m .'-d');

        return date_format($date, 't');
    }

    /*=====  End of Yearly Application Statistics  ======*/

    /*==============================================================
    =            Yearly Applications by Rank Statistics            =
    ==============================================================*/
    
    public function rankDatasets()
    {
        $rankDatasets = (object)[];

        if(Entrust::can(['officer', 'custodian'])){
            $dbRanks = DB::table('viewRankCounts')->get();
            // Yearly
            $ranks = []; $counts = []; $totalCount = [];
            foreach ($dbRanks as $rank) {
                if($rank->Year == date('Y')){
                    array_push($ranks, $rank->RankDesc);
                    array_push($counts, $rank->RankCount);
                }
            }

            $bgColors = []; $hoverColors = [];
            foreach($ranks as $rank){
                array_push($bgColors, $this->colorPicker($bgColors));
                array_push($hoverColors, $this->colorPicker($hoverColors));
            }

            $prop = (object)[
                'labels'    => $ranks,
                'datasets'  => [
                    (object)[
                        'data'  => $counts,
                        'backgroundColor' => $bgColors,
                        'hoverBackgroundColor' => $hoverColors,
                    ],
                ],
            ];

            $rankDatasets->yearly = $prop;

            // Total of all years
            foreach($ranks as $rank){
                $ctr = 0;
                foreach ($dbRanks as $dbRank) {
                    if($rank == $dbRank->RankDesc){
                        $ctr += $dbRank->RankCount;
                    }
                }
                array_push($totalCount, $ctr);
            }

            

            $prop = (object)[
                'labels'    => $ranks,
                'datasets'  => [
                    (object)[
                        'data'  => $totalCount,
                        'backgroundColor' => $bgColors,
                        'hoverBackgroundColor' => $hoverColors,
                    ]
                ],
            ];

            $rankDatasets->total = $prop;
        }
        return $rankDatasets;
    }

    public function colorPicker($existingColors = null)
    {
        $colors = [
            '#034182',
            '#118df0',
            '#fbffa3',
            '#ff4b68',
            '#1a2634',
            '#203e5f',
            '#ffcc03',
            '#fee5b1',
            '#288fb4',
            '#1d556f',
            '#efddb2',
            '#fa360a',
            '#2f3c4f',
            '#506f86',
            '#fbb040',
            '#dd6b32',
            '#3d065a',
            '#b51a62',
            '#70d4b4',
            '#ddddc7',
            '#449187',
            '#91e4a6',
            '#5f64bf',
            '#5f64c0',
            '#4a2377',
            '#61529f',
            '#ba69de',
            '#f3e96d',
            '#61bbb6',
            '#a1dfff',
            '#ad56cd',
            '#da5c53',
            '#a8e4b1',
            '#4aa3ba',
            '#d869c0',
            '#fffc8c',
            '#ffbd59',
            '#f35f5f',
            '#cc435f',
            '#f0e865',
            '#36a3eb',
            '#f8aa27',
            '#fac55b',
            '#fff8b6',
            '#20655f',
        ];

        $rand = rand(0, count($colors) - 1);

        if(!empty($existingColors)){
            $unique = false;
            do {
                $rand = rand(0, count($colors));
                if(!in_array($colors[$rand], $existingColors)){
                    $unique = true;
                }
            } while (!$unique);
        }

        return $colors[$rand];
    }

    
    /*=====  End of Yearly Applications by Rank Statistics  ======*/

    public function download($filename)
    {
    	// $headers = array(
              //'Content-Type: application/pdf',
            //);

		//return response()->download(public_path().'/uploads//' . $filename, $filename);
		// return response()->download(public_path().'\uploads\\' . $filename, $filename, $headers);
    }

    public function file($filename)
    {
    	// $headers = array(
             // 'Content-Type: application/pdf',
           // );

		return response()->file(public_path().'/uploads/' . $filename);
    }

    public function test($id)
    {
        Log::info('firing event');
        Event::fire(new SendMail($id, 'kayag.global@megaworldcorp.com',$args = ['approver', 'refno', 'reflink']));
        Log::info('event fired');
        return redirect()->route('admin.dashboard');
    }

    public function fetchJobs()
    {
        if(!Auth::user()->can('Clearance'))
            abort(403);

        $jobs = DB::table('jobs')->get();

        return view('admin.jobMonitoring')->withJobs($jobs);
    }

    public function jobsEdit($id, $avail)
    {
        DB::update('update jobs set available_at = ? where id = ?', [$avail, $id]);
        return redirect('jobs');
    }

    public function jobsUpdate(Request $request, $id)
    {
        $job = job::findOrFail($id);
        $job->payload = $request->payload;
        $job->save();

        return redirect('jobs');
    }
}
