<?php

namespace eFund;

use Illuminate\Database\Eloquent\Model;

class Deduction extends Model
{
    public $timestamps = false;
    protected $dateFormat = 'Y-m-d';


    public function getDateAttribute()
    {
    	$dt = \DateTime::createFromFormat('M j Y H:i:s:A', $this->attributes['date']);
    	if($dt)
    		return $dt->format('Y M j') ;

    	if(date('Y-m-d', strtotime($this->attributes['date'])) == $this->attributes['date'])
    		return date('Y-m-d', strtotime($this->attributes['date']));
    	elseif(date('Y-m-d H:i:s', strtotime($this->attributes['date'])) == $this->attributes['date'])
    		return date('Y-m-d', strtotime($this->attributes['date']));
    	else
    		return $this->attributes['date'];
    }
	
	
}
