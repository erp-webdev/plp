<?php

namespace eFund\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use eFund\Http\Requests;
use eFund\User;
use Log;
use Session;

class AccountController extends Controller
{
    function __construct()
    {
        Session::set('menu', 'myaccount');
    }
  	 /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {	
    	if(Auth::user()->id==$id)
    	{
       	 	$user = User::find($id);
        	return view('admin.account.edit',compact('user'));
    	}else{
    		return view('errors.403');
    	}
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
        ]);
        }else{
             $this->validate($request, [
                'name' => 'required',
                'email' => 'required|email|unique:users,email,'.$id,
                'password' => 'same:confirm-password',
            ]);
        }

        $input = $request->all();

        if(!empty($input['password'])){ 
            $input['password'] = Hash::make($input['password']);
        }else{
            $input = array_except($input,array('password'));    
        }

        $user = User::find($id);
        $user->update($input);
        // Log::info('Account updated successfully.', ['method'=>'update','Account_id'=>$user->id,'User' => Auth::user()->id]);

        return redirect()->route('account.edit', compact('user'))
                        ->with('success','Account updated successfully');
    }
}
