<?php

namespace App\Http\Controllers;

use App\Department;
use App\Role;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $users = User::all();

        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::all();
        $departments = Department::all();

        return view('users.create', compact('roles', 'departments'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'department_id' => $request->department,
            'role_id' => $request->role,
            'office' => $request->office,
            'created_at' => now(),
        ]);

        $users = User::all();
        return view('users.index', compact('users'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);

        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find($id);
        $roles = Role::all();
        $departments = Department::all();

        return view('users.edit', compact('user', 'roles', 'departments'));
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
        $user = User::find($id);

        if( $request->type == "changepassword" ){
            $user::whereId($id)->update([
                'password' => Hash::make($request->password),
                'updated_at' => now()
            ]);
        }
        else{
            if( $request->role == "3" ){
                $user::whereId($id)->update([
                    'name' => $request->name,
                    'email' => $request->email,
                    'department_id' => '1',
                    'role_id' => $request->role,
                    'office' => $request->office,
                    'updated_at' => now()
                ]);
            }else{
                $user::whereId($id)->update([
                    'name' => $request->name,
                    'email' => $request->email,
                    'department_id' => $request->department,
                    'role_id' => $request->role,
                    'office' => $request->office,
                    'updated_at' => now()
                ]);
            }

        }

        $users = User::all();
        return view('users.index', compact('users'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id);
        $user->delete();

        return redirect()->route('users.index');
    }
}
