<?php

namespace eFund\Http\Controllers\admin;

use Illuminate\Http\Request;

use eFund\Http\Requests;
use eFund\Http\Controllers\Controller;
use eFund\Preference;

class PreferenceController extends Controller
{
    public function index()
    {
    	$settings = Preference::orderBy('data_type', 'desc')->get();

    	return view('admin.preferences')->withSettings($settings);
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
}
