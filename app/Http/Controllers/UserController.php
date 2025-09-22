<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderByRaw("CASE WHEN role = 'admin' THEN 0 ELSE 1 END")
                    ->orderBy('created_at', 'desc')
                    ->paginate(10);

        return view('admin.users', compact('users'));
    }

    public function create()
    {
        return view('admin.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'username'   => 'required|unique:users',
            'password'   => 'required|min:6',
            'role'       => 'required|in:user,admin',
            'expires_at' => 'nullable|date',
        ]);

        User::create([
            'username'   => $request->username,
            'password'   => bcrypt($request->password),
            'role'       => $request->role,
            'expires_at' => $request->expires_at,
        ]);

        return redirect()->route('admin.users')->with('success', 'User created successfully.');
    }

    public function expire(User $user)
    {
        $user->expires_at = now();
        $user->save();

        return redirect()->route('admin.users')->with('success', "User {$user->username} expired successfully.");
    }
}
