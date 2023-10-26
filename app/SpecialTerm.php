<?php

namespace eFund;

use Illuminate\Database\Eloquent\Model;

class SpecialTerm extends Model
{
    protected $table = 'special_loanable_amount';
    public $timestamps = false;	

    public function scopeGetRankLimits($query, $employee)
    {
		$rank = $employee->RankDesc;
		$hire = $employee->HireDate;

		// computes the number of months difference
		$year1 = date('Y', strtotime($hire));
		$year2 = date('Y');

		$month1 = date('m', strtotime($hire));
		$month2 = date('m');

		$tenure_months = (($year2 - $year1) * 12) + ($month2 - $month1);

        return $query->whereRaw($tenure_months . ' between [min_tenure_months] and [max_tenure_months]')
                ->where('company', 'LIKE', '%'.$employee->COMPANY.'%')
                ->first();
    }

    public function serializeKeyword($keyword)
    {
    	return explode(',', $keyword);
    }
}
