<?php

namespace eFund;

use Auth;
use Illuminate\Database\Eloquent\Model;

class Endorser extends Model
{
    protected $table = 'viewEndorser';
    public $timestamps = false;
    protected $fillable = ['eFundData_id'];

    /**
     *
     * Select all endorsements that are for 
     * approval, not denied by guarantors, and for the logged in employee
     *
     */
    public function scopeEndorsements($query)
    {
    	return $query->whereRaw('eFundData_id in (select id from eFundData where status > 1)')
                // ->Where(function($query){
                //     $query->whereRaw('eFundData_id in (SELECT eFundData_id FROM guarantors WHERE status > 0)')
                //             ->orWhereRaw('(select count(*) from guarantors where guarantors.eFundData_id = viewEndorser.eFundData_id) > 0'); 
                // })
                ->where('EmpID', Auth::user()->employee_id);
    }

    public function scopeForApproval($query)
    {
    	return $query->whereRaw('eFundData_id in (select id from eFundData where status > 0)')
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

    public function getGuarantorFullNameAttribute($value)
    {
        return $value;
    }

    public function getHireDateAttribute($value)
    {
        return date('j F Y', strtotime($value));
    }

    public function getPermanencyDateAttribute($value)
    {
        return date('j F Y', strtotime($value));
    }
}
