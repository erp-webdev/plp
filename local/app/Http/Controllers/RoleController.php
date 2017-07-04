<?php

namespace eFund\Http\Controllers;

use Illuminate\Http\Request;
use eFund\Http\Controllers\Controller;
use eFund\Role;
use eFund\Permission;
use DB;
use eFund\Log;
use Auth;
use Session;

class RoleController extends Controller
{
    function __construct()
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
        $roles = Role::orderBy('id','DESC')->paginate(100);
        return view('admin.roles.index',compact('roles'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permission = Permission::get();
        return view('admin.roles.create',compact('permission'));
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
            'name' => 'required|unique:roles,name',
            'display_name' => 'required',
            'description' => 'required',
            'permission' => 'required',
        ]);

        $role = new Role();
        $role->name = $request->input('name');
        $role->display_name = $request->input('display_name');
        $role->description = $request->input('description');
        $role->save();
        
        foreach ($request->input('permission') as $key => $value) {
            $role->attachPermission($value);

            $log = new Log();
            $model = ['role' => $role->id, 'permission' => $value];
            $log->writeOnly('Insert', 'permission_role', $model);
        }

        return redirect()->route('roles.index')
                        ->with('success','Role created successfully');
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $role = Role::find($id);
        $rolePermissions = Permission::join("permission_role","permission_role.permission_id","=","permissions.id")
            ->where("permission_role.role_id",$id)
            ->get();

        return view('admin.roles.show',compact('role','rolePermissions'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $role = Role::find($id);
        $permission = Permission::get();
        $rolePermissions = DB::table("permission_role")->where("permission_role.role_id",$id)
            ->lists('permission_role.permission_id','permission_role.permission_id');

        return view('admin.roles.edit',compact('role','permission','rolePermissions'));
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
        $this->validate($request, [
            'display_name' => 'required',
            'description' => 'required',
            'permission' => 'required',
        ]);

        $log = new Log();

        $role = Role::find($id);
        $role->display_name = $request->input('display_name');
        $role->description = $request->input('description');
        $role->save();

        $perm = DB::table("permission_role")->where("permission_role.role_id",$id)->get();
        DB::table("permission_role")->where("permission_role.role_id",$id)->delete();

        $log->writeOnly('Delete', 'permission_role', $perm);

        foreach ($request->input('permission') as $key => $value) {
            $role->attachPermission($value);

            $perm_ = ['role' => $role->id, 'permission' => $value];
            $log->writeOnly('Insert', 'permission_role', $perm_);
        }

        return redirect()->route('roles.index')
                        ->with('success','Role updated successfully');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $role = Role::find($id); //->first();
        
        $log = new Log();
        $log->write('Delete', $role->getTable(), $role);

        $role = Role::where('id', $id)->delete();

        return redirect()->route('roles.index')
                        ->with('success','Role deleted successfully');
    }
}
