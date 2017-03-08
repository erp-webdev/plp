<?php

namespace eFund;

use Illuminate\Database\Eloquent\Model;
use Zizaco\Entrust\EntrustRole;
class Role extends EntrustRole
{
    protected $dateFormat = 'Y-m-d H:i:s';
    protected $primaryKey = 'id';
}
