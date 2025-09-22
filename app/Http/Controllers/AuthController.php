<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (session('logged_in')) {
            return redirect()->route('home');
        }

        return view('login');
    }

    public function loginSubmit(Request $request)
    {
        $username = $request->input('username');
        $password = $request->input('password');

        $user = User::where('username', $username)->first();

        if ($user && Hash::check($password, $user->password)) {
            if ($user->role !== 'admin' && $user->expires_at && now()->greaterThan($user->expires_at)) {
                return redirect()->route('login')->with('error', 'Your account has expired.');
            }

            session([
                'logged_in' => true,
                'user' => [
                    'id'       => $user->id,
                    'username' => $user->username,
                    'role'     => $user->role,
                ]
            ]);

            if($user->role == 'admin') {
                return redirect()->route('admin.users');
            }
            return redirect()->route('home');
        }

        return redirect()->route('login')->with('error', 'Invalid credentials.');
    }

    public function logout()
    {
        session()->forget('logged_in');
        return redirect()->route('login');
    }
}
