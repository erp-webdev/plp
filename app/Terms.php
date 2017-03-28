<?php

namespace eFund;

use Illuminate\Database\Eloquent\Model;
use eFund\Log;

class Terms extends Model
{
    protected $table = 'loan_limits';
    public $timestamps = false;	

    public function scopeGetRankLimits($query, $rank)
    {
    	if(str_contains(strtolower($rank), 'rank')){
    		if(str_contains(strtolower($rank), 'file')){
    			return $query->where('rank_position', 'Rank & File')->first();
    		}
    	}else if(str_contains(strtolower($rank), 'supervisor')){
    		return $query->where('rank_position', 'Asst. Supervisor to Sr. Supervisor')->first();
    	}else{
    		return $query->where('rank_position', 'Asst. Manager and Up')->first();
    	}
    }

    public function serializeKeyword($keyword)
    {
    	return explode(',', $keyword);
    }
}
