<?php

namespace eFund\Http\Controllers\admin;

use Illuminate\Http\Request;

use eFund\Http\Controllers\Controller;
use eFund\Http\Requests;
use eFund\Preference;
use eFund\GLimits;
use eFund\Terms;
use Session;

class PreferenceController extends Controller
{
	function __construct()
    {
        Session::set('menu', 'preferences');
    }

    public function index()
    {
    	$settings = Preference::orderBy('data_type', 'desc')->get();
    	$terms = Terms::All();
        $limits = GLimits::All();
    	return view('admin.preferences')
    	->withSettings($settings)
        ->withLimits($limits)
    	->withTerms($terms);
    }

    public function update(Request $request)
    {
    	$inputs = $request->all();
    	foreach ($inputs as $key => $value) {
    		$setting = Preference::where('name', $key)->first();

    		if(!empty($setting)){
	    		$setting->value = $value;
	    		$setting->save();
	    	}
    	} 
    	return redirect()->route('preferences.index');
    }

    public function updateTerms(Request $request)
    {
    	for ($i=0; $i < count($request->id); $i++) { 
    		$term = Terms::find($request->id[$i]);
    		if(!empty($term)){
    			$term->company = $request->company;
    			$term->min_tenure_months = $request->min_tenure[$i];
    			$term->max_tenure_months = $request->max_tenure[$i];
    			$term->min_loan_amount = $request->min_amount[$i];
    			$term->max_loan_amount = $request->max_amount[$i];
    			$term->save();
    		}
    	}

    	return redirect()->route('preferences.index');
    }

    public function updateGLimits(Request $request)
    {
        for($i = 0; $i < count($request->id); $i++){
            $limit = GLimits::find($request->id[$i]);
            if(!empty($limit)){
                $limit->Amount = $request->amount[$i];
                $limit->save();
            }
        }

        return redirect()->route('preferences.index');
    }
}
