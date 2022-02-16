<?php

namespace eFund;

use Auth;
use Illuminate\Database\Eloquent\Model;

class Guarantor extends Model
{
    protected $table = 'viewGuarantor';
    public $timestamps = false;
    protected $fillable = ['eFundData_id'];


    public function scopeGuarantors($query)
    {
    	return $query->whereRaw('eFundData_id in (select id from eFundData where status > 1)')
    			->where('EmpID', Auth::user()->employee_id)
                ->where('DBNAME', Auth::user()->DBNAME);
    }

    public function scopeForApproval($query)
    {
    	return $query->whereRaw('eFundData_id in (select id from eFundData where status > 1)')
    			->whereNull('signed_at')->whereNull('status');
    }

    public function scopeApproved($query)
    {
        return $query->whereNotNull('signed_at')->where('status', 1);
    }

    public function scopeDenied($query)
    {
        return $query->whereNotNull('signed_at')->where('status', 0);
    }

    public function getSignedAtAttribute($value)
    {
        if($value == null)
            return '--';

        return date('j F y', strtotime($value));
    }

    public function getHireDateAttribute($value)
    {
        return date('j F Y', strtotime($value));
    }

    public function getPermanencyDateAttribute($value)
    {
        return date('j F Y', strtotime($value));
    }

    public function scopeSearch($query, $keyword)
    {
        if(empty(trim($keyword))){
            return;
        }

        return $query->where('refno', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('ctrl_no', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('FullName',  'LIKE', '%' . $keyword . '%')
                    ->orWhere('EmpID', 'LIKE', '%' . $keyword . '%');
    }

    // Get Total Guaranted Amount of Guarantors on Active Loan Applications
    public function scopeGuaranteedAmountLimit($scope, $EmpID, $DB)
    {
        return $scope->where('guarantor_status', 1)
            ->where('EmpID', $EmpID)
            ->where('DBNAME', $DB)
            ->where('status', '>', 2)->where('status', '<', 8);
    }
}
