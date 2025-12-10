<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Admin;

class AuthController extends Controller
{
    public function showLoginPage(Request $request)
    {
        $form = $request->query('form', 'login');
        $error = $request->query('error', '');

        return view('auth.login', compact('form', 'error'));
    }

    public function ownerLogin(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('username', 'password');

        // Try to find user by username or email
        $user = User::where('username', $credentials['username'])
            ->orWhere('email', $credentials['username'])
            ->first();

        if ($user && Hash::check($credentials['password'], $user->password)) {
            // Check if user is archived
            if ($user->is_archived) {
                return redirect()->route('login', ['form' => 'login', 'error' => 'Account is archived']);
            }

            Auth::login($user, $request->filled('remember'));
            $request->session()->regenerate();

            return redirect()->intended(route('user.home'));
        }

        return redirect()->route('login', ['form' => 'login', 'error' => 'Invalid username or password']);
    }

    public function adminLogin(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('username', 'password');

        // Find user with admin role
        $user = User::where(function ($query) use ($credentials) {
            $query->where('username', $credentials['username'])
                ->orWhere('email', $credentials['username']);
        })
            ->where('role', 'admin')
            ->first();

        if ($user && Hash::check($credentials['password'], $user->password)) {
            // Check if user is archived
            if ($user->is_archived) {
                return redirect()->route('login', ['form' => 'admin', 'error' => 'Account is archived']);
            }

            Auth::login($user, $request->filled('remember'));
            $request->session()->regenerate();

            return redirect()->route('admin.dashboard.index');
        }

        return redirect()->route('login', ['form' => 'admin', 'error' => 'Invalid admin credentials']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
