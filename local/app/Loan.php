<?php

namespace eFund;

use Auth;
use eFund\Utilities\Utils;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    protected $table = 'viewLoan';
    protected $dateFormat = 'Y-m-d H:i:s';
    private $utils;

    function __construct()
    {
        $this->utils = new Utils();
    }

    public function scopeEmployee($query, $employee)
    {
        if($this->table == 'viewLoan')
    	    return $query->where('EmpID', $employee->EmpID)
                ->where('DBNAME', $employee->DBNAME);
        
        return $query->where('EmpID', $employee->employee_id)
            ->where('DBNAME', $employee->DBNAME);

    }

    public function scopeYearly($query)
    {
    	return $query->whereRaw('YEAR(created_at) = ' . date('Y'))
            ->where('status', '>', $this->utils->getStatusIndex('saved'));
    }

    public function scopeView($query, $id)
    {
        return $query->where('id', $id)->first();
    }

    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function getCreatedAtAttribute($value)
    {
        return date('j F Y', strtotime($value));
    }

    public function getFullNameAttribute($value)
    {
        return utf8_encode($value);
    }

    /**
     *
     * Get account balance
     * 
     *
     */
    public function scopeBalance($query, $EmpID, $DBNAME)
    {
        return $query->where('EmpID', $EmpID)
            ->where('DBNAME', $DBNAME)
            ->sum('balance');
    }

    public function getHireDateAttribute($value)
    {
        return date('j F Y', strtotime($value));
    }

    public function getPermanencyDateAttribute($value)
    {
        return date('j F Y', strtotime($value));
    }

    public function scopeNotDenied($query)
    {
        return $query->where('status', '<>', $this->utils->getStatusIndex('denied'));
    }

    public function scopeSearch($query, $keyword)
    {
        if(empty(trim($keyword))){
            return;
        }

        $status = array_search(ucwords($keyword), $this->utils->stats);

        return $query->where('ctrl_no', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('FullName', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('status',  $status)
                    ->orWhere('created_at', 'LIKE', '%' . date('Y-m-d',strtotime($keyword)) . '%')
                    ->orWhere('start_of_deductions', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('EmpID', 'LIKE', '%' . $keyword . '%');
    }

    public function setTable($table)
    {
        $this->table = $table;
    }

}
