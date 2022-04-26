<?php

namespace eFund\Http\Controllers\admin;

use Mail;
use DB;
use Log;
use Auth;
use Event;
use Entrust;
use Session;
use Redirect;
use Storage;
use eFund\job;
use eFund\Employee;
use eFund\Loan;
use eFund\Endorser;
use eFund\Notification;
use eFund\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use eFund\Http\Controllers\Controller;
use eFund\Http\Controllers\admin\EmailController;
use eFund\Http\Controllers\admin\LoanController;
use eFund\Events\CheckReleased;


class DashboardController extends Controller
{
	function __construct()
	{
		Session::set('menu', 'dashboard');
	}

    public function index()
    {
        $data = (object)[];
        $notifications = Notification::notifications()->take(5)->orderBy('created_at', 'desc')->get();
        $notifCount = Notification::notifications()->count();

        if(Entrust::can(['officer', 'custodian'])){
            // Yearly Application Statistics
            $data->appDatasets = $this->appDatasets();
            // Rank Statistics
            $data->rankDatasets = $this->rankDatasets();
            $data->stats = DB::table('DashboardView')->first();
            // Yearly Income (5 years)
            $data->incomeDatasets = $this->incomeDatasets();
            
            // Monthly Income of the current year
            $data->IncomeMonthlyDatasets = $this->incomeMonthlyDatasets(date('Y'));
        }
    	return view('admin.index')
                ->with('data', $data)
                ->with('notifications', $notifications)
                ->with('notifCount', $notifCount);
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
                            'type'  => 'line',
                            'label' => 'EFund ' . $lastYear,
                            'data'  => $appLastYear,
                            ];

                array_push($appDatasets, $prop);
            }

            if($thisYearCount > 0){

                $prop = (object)[
                    'type'              => 'bar',
                    'label'             => 'EFund ' . $thisYear,
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
            }
        } 
        return $appDatasets;
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
        return date("t",mktime(0,0,0,$m,1,$Y));
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
            $ranks = []; $counts = []; 
            $bgColors = []; $hoverColors = [];
            foreach ($dbRanks as $rank) {
                if($rank->Year == date('Y')){
                    array_push($ranks, $rank->RankDesc);
                    array_push($counts, $rank->RankCount);

                    array_push($bgColors, $this->colorPicker($bgColors));
                    // array_push($hoverColors, $this->colorPicker($hoverColors));
                    $hoverColors = $bgColors;
                }
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
            $ranks = []; $counts = [];
            $bgColors = []; $hoverColors = [];
            foreach($dbRanks as $rank){
                if(!in_array($rank->RankDesc, $ranks)){
                    $ctr = 0;
                    array_push($ranks, $rank->RankDesc);
                    foreach ($dbRanks as $dbRank) {
                        if($rank->RankDesc == $dbRank->RankDesc){
                            $ctr += $dbRank->RankCount;
                        }
                    }
                    array_push($counts, $ctr);
                }
            }

            foreach ($ranks as $rank) {
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
                $rand = rand(0, count($colors) - 1);
                if(!in_array($colors[$rand], $existingColors)){
                    $unique = true;
                }
            } while (!$unique);
        }

        return $colors[$rand];
    }

    
    /*=====  End of Yearly Applications by Rank Statistics  ======*/

    public function incomeDatasets()
    {
        $curYear = date('Y');

        $yearlyIncome = (object)[];
        $labels = []; $data = [];
        for($i = 5; $i >= 0; $i--){
            $year = $curYear - $i;
            array_push($labels, $year);

            $total = Loan::whereIn('status', [7, 8])->whereRaw('YEAR("approved_at") = ' . $year)->sum('total');
            $loan = Loan::whereIn('status', [7, 8])->whereRaw('YEAR("approved_at") = ' . $year)->sum('loan_amount');

            array_push($data, $total - $loan);
        }

        $prop = (object)[
            'labels' => $labels,
            'datasets' => [(object)[
                'label' => "Yearly Income",
                'fill' => false,
                'lineTension' => 0.1,
                'backgroundColor' => "rgba(75,192,192,0.4)",
                'borderColor' => "rgba(75,192,192,1)",
                'borderCapStyle' => 'butt',
                'borderDash' => [],
                'borderDashOffset' => 0.0,
                'borderJoinStyle' => 'miter',
                'pointBorderColor' => "rgba(75,192,192,1)",
                'pointBackgroundColor' => "#fff",
                'pointBorderWidth' => 1,
                'pointHoverRadius' => 5,
                'pointHoverBackgroundColor' => "rgba(75,192,192,1)",
                'pointHoverBorderColor' => "rgba(220,220,220,1)",
                'pointHoverBorderWidth' => 2,
                'pointRadius' => 1,
                'pointHitRadius' => 10,
                'data' => $data,
                'spanGaps' => false
            ]],
        ];

        return $prop;
    }

    public function incomeMonthlyDatasets($year)
    {
        $monthly = DB::table('viewMonthlyIncome')->where('Year', $year)->get();

        $labels = []; $data = [];
        foreach($monthly as $month){
            $mos = date('F', strtotime('2017-' . $month->month . '-01'));
            array_push($labels, $mos);

            array_push($data, $month->income);
        }

        $prop = (object)[
            'labels' => $labels,
            'datasets' => [(object)[
                'label' => "Monthly Income " . $year,
                'fill' => false,
                'lineTension' => 0.1,
                'backgroundColor' => "rgba(75,192,192,0.4)",
                'borderColor' => "rgba(75,192,192,1)",
                'borderCapStyle' => 'butt',
                'borderDash' => [],
                'borderDashOffset' => 0.0,
                'borderJoinStyle' => 'miter',
                'pointBorderColor' => "rgba(75,192,192,1)",
                'pointBackgroundColor' => "#fff",
                'pointBorderWidth' => 1,
                'pointHoverRadius' => 5,
                'pointHoverBackgroundColor' => "rgba(75,192,192,1)",
                'pointHoverBorderColor' => "rgba(220,220,220,1)",
                'pointHoverBorderWidth' => 2,
                'pointRadius' => 1,
                'pointHitRadius' => 10,
                'data' => $data,
                'spanGaps' => false
            ]],
        ];

        return $prop;
    }

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

        $file = new LoanController();
        $filename = $file->printPDFForm($id);

        $content = Storage::disk('forms')->get($filename);
        
        return (new Response($content, 200))
            ->header('Content-Type', 'application/pdf');

        $employee = Employee::where('EmpID', '2016-06-0457')
            ->where('DBNAME', 'GL')
            ->first();

        $mail = new EmailController();
        $mail->send($employee, 'test12345', 'emails.template', []);

        // Mail::raw('test',  function($message){
        //     $message->to('kayag-global@megaworldcorp.com');
        //     $message->from('no-reply@alias.megaworldcorp.com');
        //     $message->subject('plp testing');
        //     // $message->cc($cc);
        // });

        // $to = 'kayag.global@megaworldcorp.com';
        // $name = 'kevin';
        // $from = 'noreply@alias.megaworldcorp.com';
        // $cc = [];

        // $subject ='testing';
        // $body = 'emails.template';

        // Mail::send($body, ['name' => $name], function($message) use ($to, $subject, $cc, $from){
        //     $message->to($to);
        //     $message->from($from);
        //     $message->subject($subject);
        //     if(count($cc) > 0)
        //         $message->cc($cc);
        // });

        // return redirect()->route('admin.dashboard');
        Log::info('test');
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

    public function loadNotification($ctr)
    {
        $notifs = Notification::notifications()->skip($ctr * 5)->take(5)->orderBy('created_at', 'desc')->get();
        $html = '';
        foreach($notifs as $notif){
            $style = '';
            if(empty($notif->seen)) 
                $style = 'style="font-weight: bold"';
            else
                $style = '';

            $html .= '<a onclick="seen(this, '. $notif->id . ')" data-toggle="collapse" href="#notif' .$notif->id . '" class="list-group-item list-group-item-' . $notif->type.'">
                    <h5 class="list-group-item-heading "' . $style . '>' . $notif->title . '</h5>
                    <div class="collapse" id="notif'. $notif->id . '">
                        <p class="list-group-item-text">'. $notif->message .'</p>
                    </div>
                  </a>';
        }

        return $html;
    }
}
