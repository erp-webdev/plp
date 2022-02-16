<?php

namespace eFund;

use eFund\Utilities\Utils;
use Auth;
use Illuminate\Database\Eloquent\Model;

class Endorser extends Model
{
    protected $table = 'viewEndorser';
    public $timestamps = false;
    protected $fillable = ['eFundData_id'];
    private $utils;

    function __construct()
    {
        $this->utils = new Utils();
    }

    /**
     *
     * Select all endorsements that are for 
     * approval, not denied by guarantors, 
     * not for company nurse validation,
     *  for the logged in employee
     *
     */
    public function scopeEndorsements($query)
    {
    	return $query->whereRaw('eFundData_id in (select id from eFundData where status > '. $this->utils->getStatusIndex('saved') .')')
                // ->Where(function($query){
                //     $query->whereRaw('eFundData_id in (SELECT eFundData_id FROM guarantors WHERE status > 0)')
                //             ->orWhereRaw('(select count(*) from guarantors where guarantors.eFundData_id = viewEndorser.eFundData_id) > 0'); 
                // })
                ->where(function($query){
                    return $query->where('special', 1)
                    ->whereNotNull('company_nurse')
                    ->where('EmpID', Auth::user()->employee_id)
                    ->where('DBNAME', Auth::user()->DBNAME);
                })->orWhere(function($query){
                    return $query->where('special', 1)
                    ->where('EmpID', Auth::user()->employee_id)
                    ->where('DBNAME', Auth::user()->DBNAME);
                })->where('status', '<>', 10);
    }

    public function scopeForApproval($query)
    {
    	return $query->whereRaw('eFundData_id in (select id from eFundData where status > ' . $this->utils->getStatusIndex('saved') .')')
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
}
