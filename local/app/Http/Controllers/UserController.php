<?php

namespace eFund\Http\Controllers;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use eFund\Http\Controllers\Controller;
use eFund\Employee;
use eFund\User;
use eFund\Role;
use DB;
use Hash;
use Auth;
use Session;
use eFund\Log;

class UserController extends Controller
{
    public function __construct()
    {
        Session::set('menu', 'users');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = User::withoutGlobalScope('active')->orderBy('id','asc')->paginate(10);
        return view('admin.users.index',compact('data'));
            // ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
		$databases = Employee::select('DBNAME')->distinct()->get();
        $roles = Role::All('display_name','id');
        return view('admin.users.create',compact('roles'))->withDatabase($databases);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'employee_id' => 'required|unique:users',
            // 'password' => 'required|same:confirm-password',
            'roles' => 'required'
        ]);

        $employee = Employee::select('EmailAdd')->where('EmpID', $request->employee_id)->first();

        if(!empty($employee))
        {
            $input = $request->all();

            $user = new User;
            $user->setTable('users');
            $user->name  = $request->name;
            $user->email = $employee->EmailAdd;
            $user->employee_id = $request->employee_id;
            $user->password = Hash::make($request->employee_id);;
            $user->save();

            if(!empty($request->input('roles'))) {
                foreach($request->input('roles') as $role) {
                    $user->attachRole($role);

                    $log = new Log();
                    $model = ['user' => $user->id, 'role' => $role];
                    $log->writeOnly('Insert', 'role_user', $model);
                }
            }

            return redirect()->route('users.index')
                            ->with('success','User created successfully');
        }  else{
            return redirect()->route('users.create')
                            ->with('error','Employee was not found!');
        }


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::withoutGlobalScope('active')->find($id);
        return view('admin.users.show')->with('user', $user);
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::withoutGlobalScope('active')->find($id);
        $roles = Role::All('display_name','id');
        $userRole = DB::table('role_user')->where('user_id',$id)->get();
            return view('admin.users.edit',compact('user','roles','userRole'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if(empty($input['password'])){
             $this->validate($request, [
            'name' => 'required',
            'roles' => 'required'
        ]);
        }else{
             $this->validate($request, [
                'name' => 'required',
                'email' => 'required|email|unique:users,email,'.$id,
                'password' => 'same:confirm-password',
                'roles' => 'required'
            ]);
        }

        $input = $request->all();

        if(!empty($input['password'])){
            $input['password'] = Hash::make($input['password']);
        }else{
            $input = array_except($input,array('password'));
        }

        if(!empty($request->input('active')))
            $input['active'] = '1';
        else
            $input['active'] = '0';

        $user = User::withoutGlobalScope('active')->find($id);
        $user->setTable('users');
        $user->update($input);

        $model = DB::table('role_user')->where('user_id',$id)->get();
        DB::table('role_user')->where('user_id',$id)->delete();
        $log = new Log();
        $log->writeOnly('Delete', 'role_user', $model);

        if(!empty($request->input('roles'))) {
            foreach($request->input('roles') as $role) {
                $user->attachRole($role);

                $log = new Log();
                $model = ['user' => $user->id, 'role' => $role];
                $log->writeOnly('Insert', 'role_user', $model);
            }
        }

        return redirect()->route('users.index')
                        ->with('success','User updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::withoutGlobalScope('active')->findOrFail($id);
        $user->setTable('users');
        $user->delete();

        return redirect()->route('users.index')
                        ->with('success','User deleted successfully');
    }
}
