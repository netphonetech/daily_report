<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserContoller extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users =  User::all();
        return view('user.list', compact('users'));
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
            'name' => 'required|max:255|string',
            'email' => 'required|unique:users|email|max:255',
            'phone' => 'required|unique:users|numeric'
        ]);
        $user = new User();

        $user->name = $request->name;
        $user->phone = $request->phone;
        $user->email = $request->email;
        $user->admin = $request->role;
        $user->password = Hash::make($request->email);
        $user->remember_token = Str::random(10);
        if ($user->save()) {
            session()->flash('success', 'User added');
        } else {
            session()->flash('error', 'Error, user not added. Refresh and try again');
        }
        return redirect()->route('list-users');
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $user =  User::where('id', $request->id)->first();
        if (!$user) {
            session()->flash('error', 'Failed, user not exist. Refresh and try again');
            return redirect()->back();
        }
        $updated = User::where('id', $request->id)->update(['name' => $request->name, 'phone' => $request->phone, 'email' => $request->email, 'admin' => $request->role]);

        if ($updated) {
            session()->flash('success', 'User updated');
        } else {
            session()->flash('error', 'Failed, user not updated. Refresh and try again');
        }
        return redirect()->route('list-users');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $user =  User::where('id', $request->id)->first();
        if (!$user) {
            session()->flash('error', 'Failed, user not exist. Refresh and try again');
            return redirect()->back();
        }

        $user->status = false;
        if ($user->save()) {
            session()->flash('success', 'User disabled');
        } else {
            session()->flash('error', 'Failed, user not disabled. Refresh and try again');
        }
        return redirect()->route('list-users');
    }
}
