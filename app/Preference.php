<?php

namespace eFund;

use Illuminate\Database\Eloquent\Model;

class Preference extends Model
{
    protected $table = 'general_settings';
    public $timestamps = false;

    public function scopeName($query, $name)
    {
    	return $query->where('name', $name)->first();
    }
}
