<?php

namespace eFund\Http\Controllers\Auth;

use eFund\User;
use Validator;
use eFund\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Auth;
use eFund\Employee;
use eFund\Role;
use DB;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */
    protected $username = 'employee_id';

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware($this->guestMiddleware(), ['except' => 'logout']);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'employee_id' => 'required|unique:users',
            // 'employee_id' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        $email = Employee::select('EmailAdd')->where('EmpID', $data['employee_id'])->get();

        if(count($email)==0){
            return redirect()->back()->withInput()->with('errors', 'Employee ID does not exists!');
        }

        return User::create([
            'name' => $data['name'],
            'employee_id' => $data['employee_id'],
            'email' => $email[0]->EmailAdd,
            'password' => bcrypt($data['employee_id']),
        ]);
    }

    public function showRegistrationForm()
    {
        abort(403);
    }

    public function verifyEmployee()
    {
        $employee = Employee::where('EmpID', $_GET['employee_id'])->active()->regular()->first();
        if(empty($employee)){
            $employee = DB::table('viewHREmpMasterGL')->where('EmpID', $_GET['employee_id'])
                ->where('Active', 1)
                ->where('EmpStatus', 'RG')
                ->first();

            // Employee not found or deactivated
            if(empty($employee))
                return 0;
        }

        $user = User::where('employee_id', $employee->EmpID)->get();

        if(count($user) == 0){
            // Create user for first time use
            $user = new User();
            $user->setTable('users');   
            $user->name  = $employee->FName;
            $user->email = $employee->EmailAdd;
            $user->employee_id = $employee->EmpID;
            $user->password = bcrypt($employee->EmpID);
            $user->active = 1;
            $user->save();

            $role = Role::where('name','User')->get()->first();    
            $user->attachRole($role);
        }

        return 1;
    }


}
