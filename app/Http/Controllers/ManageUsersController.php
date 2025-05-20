<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ManageUsersController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('pages.manage.users.index', compact('users'));
    }

    public function create()
    {
        return view('pages.manage.users.create');
    }

    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'password' => 'required'
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        if($request->has('canRead')){
            $user->canRead = true;
        }else{
            $user->canRead = false;
        }
        if($request->has('canWrite')){
            $user->canWrite = true;
        }else{
            $user->canWrite = false;
        }
        if($request->has('canDownload')){
            $user->canDownload = true;
        }else{
            $user->canDownload = false;
        }

        $user->save();
        return redirect('/manage/users')->with('success', 'User created successfully');
    }

    public function show(User $user)
    {
    }

    public function edit(User $user)
    {
        return view('pages.manage.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {

        $request->validate([
            'name' => 'required',
            'email' => 'required',
        ]);

        $user->update($request->all());
        if($request->has('canRead')){
            $user->canRead = true;
        }else{
            $user->canRead = false;
        }
        if($request->has('canWrite')){
            $user->canWrite = true;
        }else{
            $user->canWrite = false;
        }
        if($request->has('canDownload')){
            $user->canDownload = true;
        }else{
            $user->canDownload = false;
        }

        $user->save();

        return redirect('/manage/users')->with('success', 'User updated successfully');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect('/manage/users')->with('success', 'User deleted successfully');
    }
}
