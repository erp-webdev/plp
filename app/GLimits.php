<?php

namespace eFund;

use eFund\Employee;
use Illuminate\Database\Eloquent\Model;

class GLimits extends Model
{
    protected $table = 'guaranteedAmountLimit';
    public $timestamps = false;	

    public function scopeLimit($scope, $EmpID, $DB)
    {
    	$guarantor = Employee::where('EmpID', $EmpID)
            ->where('DBNAME', $DB)
            ->first();

    	if(str_contains(strtolower($guarantor->RankDesc), 'rank')){
            // Rank and File cannot be co-borrower
            $d = new $this;
            $d->Amount = -1;

            return $d;
        }else if(str_contains(strtolower($guarantor->RankDesc), 'supervisor'))
            return $this->where('RankDesc','like', '%supervisor%')->first();
        else if(str_contains(strtolower($guarantor->RankDesc), 'manager'))
            return $this->where('RankDesc','like', '%manager%')->first();
        else if(str_contains(strtolower($guarantor->RankDesc), 'Assistant Vice President') ||
        		str_contains(strtolower($guarantor->RankDesc), 'Senior Assistant Vice President'))
            return $this->where('RankDesc','like', '%Assistant Vice President – Senior Assistant Vice President%')->first();
        else
            return $this->where('RankDesc','like', '%Vice President and Up%')->first();
    }
   
}
