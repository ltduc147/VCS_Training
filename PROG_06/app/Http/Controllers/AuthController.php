<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class AuthController extends Controller
{
    //
    public function login(){
        if (session()->has('user')){
            return redirect()->route('profile', ['id' => session('user')['id']]);
        }
        return view('auth.login_page');
    }

    public function do_login(Request $request){

        $user = User::where([
            'username' => $request->input('username'),
            'password' => $request->input('password')
        ])->get();

        if (count($user)){
            session(['user' => $user[0]]);
            return redirect()->route('profile', ['id' => session('user')['id']]);
        }

        return redirect()->route('login');
    }

    public function logout(){
        session()->forget('user');
        return redirect()->route('login');
    }
}
