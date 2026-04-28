<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function loginView(){
        if(Auth::check()){
            return back();
        }
        return view('pages.auth.login');
    }

    public function login(Request $request)
    {
        if(Auth::check()){
            return back();
        }

        // Validasi input
        $request->validate([
            'name' => ['required'],
            'password' => ['required'],
        ]);

        // Ambil user berdasarkan name
        $user = User::where('name', $request->name)->first();

        $errors = [];

        // Cek apakah user ada
        if (!$user) {
            $errors['name'] = 'Name is not registered.';
        } else {
            if (!$user->password || !Hash::check($request->password, (string) $user->password)) {
                $errors['password'] = 'Wrong Password';
            }
        }

        // Kalau ada error
        if (!empty($errors)) {
            return back()->withErrors($errors)->withInput();
        }

        // Login user
        Auth::login($user);
        $request->session()->regenerate();

        return redirect('/');
    }

    public function logout(Request $request){
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
