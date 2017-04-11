<?php

namespace eFund;

use Auth;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    public $timestamps = false;

   	public function scopeNotifications($query)
   	{
   	 	return $query->where('receiver', Auth::user()->employee_id);
   	}


}
