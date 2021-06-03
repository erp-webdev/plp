<?php

namespace eFund;

use eFund\Utilities\Utils;
use Illuminate\Database\Eloquent\Model;

class Ledger extends Model
{
    protected $table = 'viewEmpLedger';
    public $timestamps = false;
    private $utils;

    function __construct()
    {
    	$this->utils = new Utils();
    }

    public function getDateAttribute($value)
    {
        $dt = \DateTime::createFromFormat('M j Y H:i:s:A', $this->attributes['date']);
        return $dt->format('d-M-y') ;

    	return date('d-M-y', strtotime($value));
    }

    public function getCheckReleasedAttribute($value)
    {
        $dt = \DateTime::createFromFormat('M j Y H:i:s:A', $value);
        return $dt->format('d-M-y') ;

    	return date('d-M-y', strtotime($value));
    }

    public function getCvDateAttribute($value)
    {
        $dt = \DateTime::createFromFormat('M j Y H:i:s:A', $value);
        return $dt->format('d-M-y') ;
        
        return date('d-M-y', strtotime($value));
    }

    public function getLoanAmountAttribute($value)
    {
    	return $this->utils->formatNumber($value);
    }

    public function getLoanAmountInterestAttribute($value)
    {
        return $this->utils->formatNumber($value);
    }

    // public function getTotalAttribute($value)
    // {
    //     return $this->utils->formatNumber($value);
    // }

    // public function getDeductionsAttribute($value)
    // {
    // 	return $this->utils->formatNumber($value);
    // }

    public function getCreatedAtAttribute($value)
    {
    	return date('d-M-y', strtotime($value));
    }

    // public function getAmountAttribute($value)
    // {
    // 	return $this->utils->formatNumber($value);
    // }

    // public function getBalanceAttribute($value)
    // {
    // 	return $this->utils->formatNumber($value);
    // }

   public function scopeDeductionList($query, $date)
   {
       return $query->where('date', date('Y-m-d', strtotime($date)));
   }
}
