<?php

use Illuminate\Database\Seeder;
use eFund\Role;
use eFund\Permission;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
        	[
        		'name' => 'Admin',
        		'display_name' => 'Administrator', 
        		'description' => 'Default Administrator'
        	],
        	[
        		'name' => 'User',
        		'display_name' => 'User',
        		'description' => 'Default Standard User'
        	],
           
        ];


        foreach ($roles as $key => $value) {
        	$role = Role::create($value);

	        if($role->name=='Admin')
	        {
	        	//Admin Default Permissions
	        	$permissions = Permission::All();
	        	foreach($permissions as $permission){
	        		$role->attachPermission($permission);
	        	}
	        }else if($role->name=='User')
	        {
	        	//User Default Permissions
	        	$permissions = Permission::whereNotIn('name', array('users','roles'))->get();
	        	foreach($permissions as $permission){
	        		$role->attachPermission($permission);
	        	}
	        }

        }
    }
}
