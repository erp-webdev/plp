<?php

use Illuminate\Database\Seeder;
use eFund\User;
use eFund\Role;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	//Default Administrator
    	$user = new User();
    	$user->name = 'Administrator';
    	$user->email = 'kayag.global@megaworldcorp.com';
        $user->employee_id = '2014-05-N791';
    	$user->password = Hash::make('2014-05-N791');
    	$user->save();

    	$role = Role::where('name','Admin')->get()->first();
    	$user->attachRole($role);

    }
}
       