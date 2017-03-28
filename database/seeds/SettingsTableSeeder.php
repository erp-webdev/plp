<?php

use Illuminate\Database\Seeder;
use eFund\Preference;
use eFund\Terms;

class SettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $settings = [
        	[
        		'name' => 'interest',
        		'value' => '1', 
        		'data_type' => 'number',
        		'description' => 'Loan Amount Interest (%)',
        		'helper' => ''
        	],
        	[
        		'name' => 'emp_notif',
        		'value' => '1', 
        		'data_type' => 'boolean',
        		'description' => 'Automatic Employee Notification',
        		'helper' => ''
        	],
        	[
        		'name' => 'cust_notif',
        		'value' => '1', 
        		'data_type' => 'boolean',
        		'description' => 'Automatic eFund Custodian Notification',
        		'helper' => ''
        	],
        	[
        		'name' => 'payment_term',
        		'value' => '12', 
        		'data_type' => 'number',
        		'description' => 'Maximum Payment Term (mos)',
        		'helper' => ''
        	],
        	[
        		'name' => 'max_availment',
        		'value' => '2', 
        		'data_type' => 'number',
        		'description' => 'Maximum # of Availment',
        		'helper' => ''
        	],
        	[
        		'name' => 'payroll_notif',
        		'value' => '1', 
        		'data_type' => 'boolean',
        		'description' => 'Automatic Payroll Notification',
        		'helper' => ''
        	],
        	[
        		'name' => 'cutoff',
        		'value' => '1, 20', 
        		'data_type' => 'text',
        		'description' => 'Payroll cut-off',
        		'helper' => 'Separate each value by comma ","'
        	],
        	[
        		'name' => 'guarantor_notif',
        		'value' => '1', 
        		'data_type' => 'boolean',
        		'description' => 'Automatic Guarantor Notification',
        		'helper' => ''
        	],
            [
                'name' => 'allow_over_max',
                'value' => '1', 
                'data_type' => 'boolean',
                'description' => 'Allow loan above maximum amount',
                'helper' => ''
            ],
            
        ];


        foreach ($settings as $key => $value) {
        	Preference::create($value);
        }

        $terms = [
        	[
        		'keyword' => 'rank,file', 
        		'rank_position' => 'Rank & File',
        		'min_amount' => '6000.00',
        		'max_amount' => '20000.00',
        		'allow_over_max' => true
        	],
        	[
        		'keyword' => 'supervisor', 
        		'rank_position' => 'Asst. Supervisor to Sr. Supervisor',
        		'min_amount' => '11000.00',
        		'max_amount' => '33000.00',
        		'allow_over_max' => true
        	],
        	[
        		'keyword' => 'manager', 
        		'rank_position' => 'Asst. Manager and Up',
        		'min_amount' => '15000.00',
        		'max_amount' => '50000.00',
        		'allow_over_max' => true
        	]
        ];

        foreach ($terms as $key => $value) {
        	Terms::create($value);
        }

    }
}
