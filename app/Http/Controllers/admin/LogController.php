<?php

namespace eFund\Http\Controllers\admin;

use Illuminate\Http\Request;

use eFund\Http\Requests;
use eFund\Http\Controllers\Controller;
use eFund\Log;
use Auth;

class LogController extends Controller
{
	function __construct()
	{
		// if(!Auth::user()->can('Clearance'))
		// 	abort(403);
	}

	public $show = 20;

    public function index()
    {
    	$logs = Log::orderBy('id', 'desc')->paginate($this->show);
    	return view('admin.logs')->withLogs($logs);
    }

    public function filters(Request $request)
    {
		$logs = Log::where(function($query) use ($request){
		            if (!empty($request->fromDate) && !empty($request->toDate)) {
		                $query->date($request->fromDate, $request->toDate);
		            }

		            if(!empty($request->type)){
		            	if($request->type != 'All'){
		            		$query->type($request->type);
		            	}
		            }

		            if(!empty($request->userId)){
		            	$query->user($request->userId);
		            }

		            if(!empty($request->table)){
		            	$query->table($request->table);
		            }

		            if(!empty($request->content)){
		            	$query->content($request->content);
		            }

		         })->orderBy('created_at', 'desc')
				->paginate($this->show);

    	return view('admin.logs')->withLogs($logs);
    }

}
