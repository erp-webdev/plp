<?php

namespace eFund;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $dateFormat = 'Y-m-d H:i:s';
    protected $table = 'viewHREmpMaster';	
    protected $hidden = ['EPassword'];
}
