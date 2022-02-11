<?php

namespace eFund;

use Illuminate\Database\Eloquent\Model;
use eFund\Log;
use eFund\Employee;

class Terms extends Model
{
    protected $table = 'loanable_amount';
    public $timestamps = false;	

    public function scopeGetRankLimits($query, Employee $employee)
    {
		$rank = $employee->RankDesc;
		$hire = $employee->HireDate;
		/*
    	if(str_contains(strtolower($rank), 'rank')){
    		if(str_contains(strtolower($rank), 'file')){
    			return $query->where('rank_position', 'Rank & File')->first();
    		}
    	}else if(str_contains(strtolower($rank), 'supervisor')){
    		return $query->where('rank_position', 'Asst. Supervisor to Sr. Supervisor')->first();
    	}else{
    		return $query->where('rank_position', 'Asst. Manager and Up')->first();
    	}*/

		// computes the number of months difference
		$year1 = date('Y', strtotime($hire));
		$year2 = date('Y');

		$month1 = date('m', strtotime($hire));
		$month2 = date('m');

		$tenure_months = (($year2 - $year1) * 12) + ($month2 - $month1);

		// check for rank and validity
		if(str_contains(strtolower($rank), 'rank')){
    		if(str_contains(strtolower($rank), 'file')){
    			return $query->where('tag', 'RF')
						->whereRaw($tenure_months . ' between [min_tenure_months] and [max_tenure_months]')
						->where('company', 'LIKE', '%'.$employee->COMPANY.'%')
						->first();
    		}	
    	}else if(str_contains(strtolower($rank), 'supervisor')){
    		return $query
				->where('tag', 'SC')
				->whereRaw($tenure_months . ' between [min_tenure_months] and [max_tenure_months]')
				->where('company', 'LIKE', '%'.$employee->COMPANY.'%')
				->first();
    	}else{
    		return $query
				->where('tag', 'MA')
				->whereRaw($tenure_months . ' between [min_tenure_months] and [max_tenure_months]')
				->where('company', 'LIKE', '%'.$employee->COMPANY.'%')
				->first();
    	}
    }

    public function serializeKeyword($keyword)
    {
    	return explode(',', $keyword);
    }
}
