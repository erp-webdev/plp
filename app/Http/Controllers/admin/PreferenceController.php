<?php

namespace eFund\Http\Controllers\admin;

use Illuminate\Http\Request;

use eFund\Http\Requests;
use eFund\Http\Controllers\Controller;
use eFund\Preference;
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

    	return view('admin.preferences')
    	->withSettings($settings)
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
    			$term->min_amount = $request->min_amount[$i];
    			$term->max_amount = $request->max_amount[$i];
    			$term->save();
    		}
    	}

    	return redirect()->route('preferences.index');
    }
}
