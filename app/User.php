<?php

namespace eFund;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Zizaco\Entrust\Traits\EntrustUserTrait;
use Hash;
use App;

class User extends Authenticatable
{
    use EntrustUserTrait;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $table = 'viewUsers';

    protected $fillable = [
        'name', 'email', 'password', 'active', 'employee_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $dateFormat = 'Y-m-d H:i:s';
    protected $primaryKey = 'id';

    function __construct()
    {
        if (App::environment('production')) 
            $this->table = 'viewUsers';
        else
            $this->table = 'users';
    }

     public function getPasswordAttribute($value) {
        if (App::environment('production')) 
            return  Hash::make($value);
        else
           return $value;
    }
}
