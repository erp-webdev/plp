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

    public function scopeEmployee($query)
    {
    	return $query->where('EmpID', Auth::user()->employee_id);
    }

    public function scopeYearly($query)
    {
    	return $query->whereRaw('YEAR(created_at) = ' . date('Y'))->where('status', '>', $this->utils->getStatusIndex('saved'));
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
    public function scopeBalance($query, $EmpID)
    {
        return $query->where('EmpID', $EmpID)->sum('balance');
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

}
