<?php

namespace App\Http\Controllers\Web;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;


class LoginController extends Controller
{
    public function loginView() 
    {
        if (Auth::guard('web')->check()) {
            return redirect()->route('welcome');
        } else {
            return view('login/login');
        }
    }

    public function loginAttempt(Request $request)
    {
        $credentials = [
            'email' => $request->email ?? '', 
            'password' => $request->password ?? '',
        ];

        LoginRequest::validate($credentials);

        if (Auth::guard('web')->attempt([
            'email' => $credentials['email'], 
            'password' => $credentials['password'], 
        ])) {
            $request->session()->regenerate();
            return redirect()->route('welcome');

        } else {
            return view('login/login', ['error' => 'INVALID CREDENTIALS.']);
        }
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('welcome');
    }
}