<?php

namespace eFund\Http\Controllers\admin;

use Illuminate\Http\Request;

use eFund\Http\Controllers\Controller;
use eFund\Http\Requests;
use eFund\Preference;
use eFund\GLimits;
use eFund\Terms;
use eFund\SpecialTerm;
use eFund\AllowedAboveMaxLoan;
use Session;
use Auth;
use DB;

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
        $special = SpecialTerm::All();

    	return view('admin.preferences')
    	->withSettings($settings)
        ->withLimits($limits)
    	->withTerms($terms)
        ->withSpecial($special);
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

    public function updateSpecialTerms(Request $request)
    {
    	for ($i=0; $i < count($request->id); $i++) { 
    		$term = SpecialTerm::find($request->id[$i]);
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

	public function showAboveMaxLoan(Request $request)
	{
		$list = AllowedAboveMaxLoan::orderBy('CreatedAt', 'desc')->get();

		return view('admin.overmaxloan')
			->withEmployees($list);
	}

	public function updateAboveMaxLoan(Request $request)
	{
		if(isset($request->save)){

			$this->validate($request, [
				'required'=>'EmpID|DBName|FullName|ExpiredAt'
			]);

			$item = new AllowedAboveMaxLoan();
			$item->EmpID = $request->EmpID;
			$item->DBName = $request->DBName;
			$item->FullName = $request->FullName;
			$item->ExpiredAt = $request->ExpiredAt;
			$item->CreatedAt = date('Y-m-d H:i:s');
			$item->CreatedBy = Auth::user()->id;
			$item->save();

			return redirect()->back()
			->withSuccess('Employee has been added successfully!');
		}else{

			$D = DB::table('allowed_above_max_loan')->where('ID', $request->id)
				->first();

			dd($D);
			return redirect()->back()
				->withSuccess("Employee has been deleted successfully!");

		}

		
	}
}
