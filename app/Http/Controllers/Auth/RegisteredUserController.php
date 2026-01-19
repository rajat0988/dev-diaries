<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisteredUserController extends Controller
{
    public function create()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:users',
            ],
            'password' => 'required|string|min:8|confirmed',
        ]);

        $isJimsUrl = str_ends_with($request->email, '@jimsindia.org');

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_approved' => $isJimsUrl,
        ]);

        event(new Registered($user));

        if ($isJimsUrl) {
            $message = 'Registration successful! Please sign in with your credentials.';
            return redirect()->route('register')->with('status', $message);
        } else {
            $message = 'Registration successful! Your account is pending approval by an administrator.';
            return redirect()->route('register')->with('success', $message);
        }
    }
}
