<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login() {
        return view('pages/login');
    }

    public function logout(){
        session()->forget('user');
        return redirect()->route('login');
    }

    public function authenticate(Request $request){

        $request->validate([
            "email" => "required",
            "password" => "required"
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $request->get('remember'))) {
            return redirect('/');
        }else{
            return redirect()->back()->withErrors(["error" => "The provided login details are incorrect."]);
        }
    }
}
