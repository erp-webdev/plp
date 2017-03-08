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
        $user->employee_id = '2016-06-0457';
    	$user->password = Hash::make('admin123');
    	$user->save();

    	$role = Role::where('name','Admin')->get()->first();
    	$user->attachRole($role);

    }
}
       