<?php

namespace eFund;

use eFund\User;
use Auth;
use eFund\Utilities\Utils;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $dateFormat = 'Y-m-d H:i:s';
    protected $table = 'viewHREmpMaster';	
    protected $hidden = ['EPassword'];
    function __construct()
    {
        $this->utils = new Utils();
    }

    public function scopeCurrent($scope)
    {
        $user = User::find(Auth::user()->id);
        
        return $scope->where('EmpID', $user->employee_id)
                ->where('DBNAME', $user->DBNAME);
    }

    public function scopeRegular($query)
    {
        return $query->where('EmpStatus', 'RG');
    }

    public function scopeActive($query)
    {
    	return $query->where('Active', 1);
    }

    public function getFullNameAttribute($value)
    {
        return utf8_encode($value);
    }

    public function getFNameAttribute($value)
    {
        return utf8_decode($value);
    }

    public function getLNameAttribute($value)
    {
        return utf8_encode($value);
    }

    public function getMNameAttribute($value)
    {
        return utf8_encode($value);
    }

    public function getHireDateAttribute($value)
    {
        return date('j F Y', strtotime($value));
    }

    public function scopeSearch($query, $keyword)
    {
        if(empty(trim($keyword))){
            return;
        }
        
        return $query->where('EmpID', 'LIKE', '%' . $keyword . '%')
                ->orWhere('FullName', 'LIKE', '%' . $keyword . '%')
                ->orWhere('PositionDesc', 'LIKE', '%' . $keyword . '%')
                ->orWhere('RankDesc', 'LIKE', '%' . $keyword . '%');
    }

    public function getTenureAttribute($value)
    {
        $date1 = $this->attributes['HireDate'];
        $date2 = date('Y-m-d');

        $ts1 = strtotime($date1);
        $ts2 = strtotime($date2);

        $year1 = date('Y', $ts1);
        $year2 = date('Y', $ts2);

        $month1 = date('m', $ts1);
        $month2 = date('m', $ts2);

        // months since hire date
        return (($year2 - $year1) * 12) + ($month2 - $month1);
    }
}
