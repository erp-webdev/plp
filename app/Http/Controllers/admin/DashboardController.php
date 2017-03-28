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

class DashboardController extends Controller
{
	function __construct()
	{
		Session::set('menu', 'dashboard');
	}

    public function index()
    {
			$error = '';
            $data =[]; // DB::select('EXEC spGetDashboardStat ?', [Auth::user()->employee_id]);
    	return view('admin.index')
        // ->with('data', $data[0])
        ->with('error', $error);
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
