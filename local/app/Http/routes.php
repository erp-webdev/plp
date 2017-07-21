<?php

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/wrapper', function(){
	if(Session::get('wrapper')=='active')
		Session::set('wrapper', '');
	else
		Session::set('wrapper', 'active');
});


Route::get('/verify/employee', ['uses' => 'Auth\AuthController@verifyEmployee']);

Route::auth();

Route::group(['prefix' => '/', 'middleware' => ['auth']], function(){
	// Mail
	Route::get('/testmail/{id}', 'admin\DashboardController@test');

	// Dashboard
	Route::get('/', ['as' => 'admin.dashboard', 'uses' => 'admin\DashboardController@index']);
	Route::get('/notification/seen/{id}', ['as' => 'notif.seen', 'uses' => 'admin\NotificationController@seen']);
	Route::get('/notifications/{ctr}', ['as' => 'notifs', 'uses' => 'admin\DashboardController@loadNotification']);
	Route::get('/notifications/{ctr}', ['as' => 'notifs', 'uses' => 'admin\DashboardController@loadNotification']);

	// Loans
	Route::get('loans', ['as' => 'admin.loan', 'uses' => 'admin\LoanController@index', 'middleware' => ['permission:loan_list']]);
	Route::post('loans', ['as' => 'loan.approve', 'uses' => 'admin\LoanController@approve', 'middleware' => ['permission:officer']]);
	Route::get('loans/show/{id}', ['as' => 'loan.show', 'uses' => 'admin\LoanController@show', 'middleware' => ['permission:loan_view']]);
	Route::post('loans/deductions', ['as' => 'loan.deduction', 'uses' => 'admin\LoanController@saveDeduction', 'middleware' => ['permission:custodian']]);
	Route::get('loans/complete/{id}', ['as' => 'loan.complete', 'uses' => 'admin\LoanController@complete', 'middleware' => ['permission:custodian']]);
	Route::get('loans/print/{id}', ['as' => 'loan.print', 'uses' => 'admin\LoanController@printForm', 'middleware' => ['permission:officer|custodian']]);
	Route::get('loans/deductions/list', ['as' => 'loan.deduction.list', 'uses' => 'admin\LoanController@getDeductions', 'middleware' => ['permission:custodian|payroll']]);
	Route::post('loans/deductions/batch', ['as' => 'loan.deduction.batch', 'uses' => 'admin\LoanController@applyBatchDeductions', 'middleware' => ['permission:custodian']]);
	Route::post('loans/payroll/notifs', ['as' => 'loan.email.notif', 'uses' => 'admin\LoanController@sendPayrollNotif', 'middleware' => ['permission:custodian']]);
	Route::get('loans/payroll/list', ['as' => 'loan.email.list', 'uses' => 'admin\LoanController@getFormattedPayrollList', 'middleware' => ['permission:custodian']]);
	Route::get('loans/deductions/recal/{id}', ['as' => 'deductions.recal', 'uses' => 'admin\LoanController@recalDeductions', 'middleware' => ['permission:custodian']]);

	// Upload
	Route::get('loans/upload', ['as' => 'upload.show', 'uses' => 'admin\LoanController@showUpload', 'middleware' => ['permission:custodian']]);
	Route::post('loans/upload', ['as' => 'loan.upload', 'uses' => 'admin\LoanController@upload', 'middleware' => ['permission:custodian']]);

	// Reports
	Route::get('reports', ['as' => 'report.index', 'uses' => 'admin\ReportController@index', 'middleware' => ['permission:custodian']]);
	Route::get('reports/{type}', ['as' => 'report.show', 'uses' => 'admin\ReportController@show', 'middleware' => ['permission:custodian']]);
	Route::get('reports/generate/{type}', ['as' => 'report.print', 'uses' => 'admin\ReportController@generate', 'middleware' => ['permission:custodian']]);
	//Dashboard Charts
	Route::get('charts', ['as' => 'charts', 'uses' => 'admin\ReportController@printChart', 'middleware' => ['permission:custodian']]);

	// Ledger
	Route::get('ledger', ['as' => 'ledger.index', 'uses' => 'admin\LedgerController@index', 'middleware' => ['permission:officer|custodian']]);
	Route::get('ledger/show/{EmpID}', ['as' => 'ledger.show', 'uses' => 'admin\LedgerController@show', 'middleware' => ['permission:officer|custodian']]);
	Route::get('ledger/print/{EmpID}', ['as' => 'ledger.print', 'uses' => 'admin\LedgerController@printLedger', 'middleware' => ['permission:officer|custodian']]);
	
	// Applications
	Route::get('applications', ['as' => 'applications.index', 'uses' => 'admin\ApplicationController@index','middleware' => ['permission:application_list']]);
	Route::get('applications/show/{id}', ['as' => 'applications.show', 'uses' => 'admin\ApplicationController@show','middleware' => ['permission:application_view']]);
	Route::get('applications/create', ['as' => 'applications.create', 'uses' => 'admin\ApplicationController@create','middleware' => ['permission:application_create']]);
	Route::post('applications', ['as' => 'applications.store', 'uses' => 'admin\ApplicationController@store','middleware' => ['permission:application_create']]);
	Route::get('applications/destroy/{id}',['as'=>'applications.destroy','uses'=>'admin\ApplicationController@destroy','middleware' => ['permission:application_delete']]);

	// Endorsements
	Route::get('endorsements', ['as' => 'endorsements.index', 'uses' => 'admin\EndorsementController@index']);
	Route::post('endorsements/approve', ['as' => 'endorsements.approve', 'uses' => 'admin\EndorsementController@approve']);
	Route::get('endorsements/show/{id}', ['as' => 'endorsements.show', 'uses' => 'admin\EndorsementController@show']);

	// Guarantors
	Route::get('guarantors', ['as' => 'guarantors.index', 'uses' => 'admin\GuarantorController@index']);
	Route::post('guarantors/approve', ['as' => 'guarantors.approve', 'uses' => 'admin\GuarantorController@approve']);
	Route::get('guarantors/show/{id}', ['as' => 'guarantors.show', 'uses' => 'admin\GuarantorController@show']);

	// Treasury
	Route::get('treasury', ['as' => 'treasury.index', 'uses' => 'admin\TreasuryController@index', 'middleware' => ['permission:treasurer']]);
	Route::get('treasury/print', ['as' => 'treasury.print', 'uses' => 'admin\TreasuryController@printReport', 'middleware' => ['permission:treasurer']]);
	Route::post('treasury/approve', ['as' => 'treasury.approve', 'uses' => 'admin\TreasuryController@approve', 'middleware' => ['permission:treasurer']]);
	Route::get('treasury/show/{id}', ['as' => 'treasury.show', 'uses' => 'admin\TreasuryController@show', 'middleware' => ['permission:treasurer']]);
	Route::get('treasury/generate/voucher/{id}', ['as' => 'treasury.voucher', 'uses' => 'admin\TreasuryController@generateCheckVoucher', 'middleware' => ['permission:treasurer']]);
	Route::get('treasury/print/voucher/{id}', ['as' => 'treasury.voucher.print', 'uses' => 'admin\TreasuryController@printCheckVoucher', 'middleware' => ['permission:treasurer']]);

	// Payroll
	Route::get('payroll', ['as' => 'payroll.index', 'uses' => 'admin\PayrollController@index', 'middleware' => ['permission:payroll']]);
	Route::post('payroll/verify', ['as' => 'payroll.verify', 'uses' => 'admin\PayrollController@verify', 'middleware' => ['permission:payroll']]);
	Route::get('payroll/show/{id}', ['as' => 'payroll.show', 'uses' => 'admin\PayrollController@show', 'middleware' => ['permission:payroll']]);
	
	Route::get('getEmployee', ['uses' => 'admin\ApplicationController@getEmployee']);
});

Route::group(['middleware' => ['auth']], function() {
	//Home Route
	//Route::get('/home', 'HomeController@index');

	//Users Route Resources
	//Route::resource('users','UserController');
	Route::get('users',['as'=>'users.index','uses'=>'UserController@index','middleware' => ['permission:users', 'role:Admin']]);
	Route::post('users',['as'=>'users.store','uses'=>'UserController@store','middleware' => ['permission:users']]);
	Route::get('users/create',['as'=>'users.create','uses'=>'UserController@create','middleware' => ['permission:users']]);
	Route::get('users/{id}',['as'=>'users.show','uses'=>'UserController@show','middleware' => ['permission:users']]);
	Route::get('users/{id}/edit',['as'=>'users.edit','uses'=>'UserController@edit','middleware' => ['permission:users']]);
	Route::patch('users/{id}',['as'=>'users.update','uses'=>'UserController@update','middleware' => ['permission:users']]);
	Route::delete('users/{id}',['as'=>'users.destroy','uses'=>'UserController@destroy','middleware' => ['permission:users']]);

	//Roles Route
	Route::get('roles',['as'=>'roles.index','uses'=>'RoleController@index', 'middleware' => ['permission:roles']]);
		//,'middleware' => ['permission:role-list|role-create|role-edit|role-delete']]);
	Route::get('roles/create',['as'=>'roles.create','uses'=>'RoleController@create', 'middleware' => ['permission:roles']]);
	Route::post('roles/create',['as'=>'roles.store','uses'=>'RoleController@store', 'middleware' => ['permission:roles']]);
	Route::get('roles/{id}',['as'=>'roles.show','uses'=>'RoleController@show', 'middleware' => ['permission:roles']]);
	Route::get('roles/{id}/edit',['as'=>'roles.edit','uses'=>'RoleController@edit', 'middleware' => ['permission:roles']]);
	Route::patch('roles/{id}',['as'=>'roles.update','uses'=>'RoleController@update', 'middleware' => ['permission:roles']]);
	Route::delete('roles/{id}',['as'=>'roles.destroy','uses'=>'RoleController@destroy', 'middleware' => ['permission:roles']]);
	
	//User Account Route
	Route::get('account/{id}/edit', ['as'=>'account.edit', 'uses'=>'AccountController@edit']);
	Route::patch('account/{id}',['as'=>'account.update','uses'=>'AccountController@update']);

	//Preferences
	Route::get('preferences',['as'=>'preferences.index','uses'=>'admin\PreferenceController@index', 'middleware' => ['permission:Preferences']]);
	Route::post('preferences',['as'=>'preferences.update','uses'=>'admin\PreferenceController@update', 'middleware' => ['permission:Preferences']]);
	Route::post('preferences/terms',['as'=>'preferences.terms','uses'=>'admin\PreferenceController@updateTerms', 'middleware' => ['permission:Preferences']]);
	Route::post('preferences/limits',['as'=>'preferences.limits','uses'=>'admin\PreferenceController@updateGLimits', 'middleware' => ['permission:Preferences']]);

	// FAQ
	Route::get('admin/documentation', function () {
		Session::set('menu', 'faq');
	    return view('admin/documentation');
	});

	Route::get('logs',['as'=>'logs','uses'=>'admin\LogController@index']);
	Route::get('logs/filter',['as'=>'logs.filter','uses'=>'admin\LogController@filters']);
});
