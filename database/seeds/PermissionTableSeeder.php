<?php

use Illuminate\Database\Seeder;
use eFund\Permission;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permission = [
        	[
        		'name' => 'roles',
        		'display_name' => 'Roles', 
        		'description' => 'Manage roles'
        	],
        	[
        		'name' => 'users',
        		'display_name' => 'Users',
        		'description' => 'Manage users'
        	],
            [
                'name' => 'Preferences',
                'display_name' => 'Preferences',
                'description' => 'Manage Preferences'
            ],
        ];

        foreach ($permission as $key => $value) {
        	Permission::create($value);
        }
    }
}
