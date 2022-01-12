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
        dd($user); 
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
}
