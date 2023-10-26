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
        		"name" => "roles",
        		"display_name" => "Roles", 
        		"description" => "Manage roles"
        	],
        	[
        		"name" => "users",
        		"display_name" => "Users",
        		"description" => "Manage users"
        	],
            [
                "name" => "Preferences",
                "display_name" => "Preferences",
                "description" => "Manage Preferences"
            ],
            [
                "name" => "loan_list",
                "display_name" => "List Loan Applications",
                "description" => "List all loan applications"
            ],
            [
                "name" => "loan_view",
                "display_name" => "View Loan",
                "description" => "View loan applications"
            ],
            // [
            //     "name" => "loan_create",
            //     "display_name" => "Create Loans",
            //     "description" => "Create a loan application"
            // ],
            [
                "name" => "loan_edit",
                "display_name" => "Update Loan",
                "description" => "Update a loan application"
            ],
            [
                "name" => "loan_delete",
                "display_name" => "Delete Loan Application",
                "description" => "Delete a loan application"
            ],
            [
                "name" => "application_list",
                "display_name" => "List Owned Loan Applications",
                "description" => "List all owned Loan Applications"
            ],
            [
                "name" => "application_view",
                "display_name" => "View Owned Loans",
                "description" => "View all owned loan applications"
            ],
            [
                "name" => "application_create",
                "display_name" => "Create Loans",
                "description" => "Create a loan application"
            ],
            [
                "name" => "application_edit",
                "display_name" => "Update Owned Loan",
                "description" => "Update owned loan application"
            ],
            [
                "name" => "application_delete",
                "display_name" => "Delete Owned Loan Application",
                "description" => "Delete owned loan application"
            ],
            [
                "name" => "approver",
                "display_name" => "Loan Approver",
                "description" => "Co-borrower, Immediate Head, Department Head approvers"
            ],
            [
                "name" => "officer",
                "display_name" => "eFund Officer (Approver)",
                "description" => "officer who is an approver"
            ],
            [
                "name" => "custodian",
                "display_name" => "eFund Custodian (Approver)",
                "description" => "Custodian who is an approver"
            ],
            [
                "name" => "treasurer",
                "display_name" => "Treasurer",
                "description" => "Prepare and relaese check"
            ],
        ];

        foreach ($permission as $key => $value) {
        	Permission::create($value);
        }
    }
}
