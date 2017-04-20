<?php

namespace eFund;

use Illuminate\Database\Eloquent\Model;

class GLimits extends Model
{
    protected $table = 'guaranteedAmountLimit';
    public $timestamps = false;	

    public function scopeGetRankLimits($query, $rank)
    {
    	if(str_contains(strtolower($rank), 'supervisor')){
    		return $query->where('RankDesc', 'Assistant Supervisor – Senior Supervisor')->first();
    	}else if(str_contains(strtolower($rank), 'manager')){
    		return $query->where('RankDesc', 'Assistant Manager – Senior Manager')->first();
    	}else if(str_contains(strtolower($rank), 'president')){
    		return $query->where('RankDesc', 'Assistant Vice President – Executive Vice President')->first();
    	}
    }
}
