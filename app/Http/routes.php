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

	// FAQ
	Route::get('admin/documentation', function () {
		Session::set('menu', 'faq');
	    return view('admin/documentation');
	});

	Route::get('logs',['as'=>'logs','uses'=>'admin\LogController@index']);
	Route::get('logs/filter',['as'=>'logs.filter','uses'=>'admin\LogController@filters']);
});
