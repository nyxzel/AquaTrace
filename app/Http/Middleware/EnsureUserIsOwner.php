<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class EnsureUserIsOwner
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login first');
        }

        $user = Auth::user();

        // Debug log
        Log::info('Owner middleware check', [
            'user_id' => $user->id,
            'role' => $user->role ?? 'NO ROLE FIELD',
            'email' => $user->email
        ]);

        // Check if user has owner role
        if (!isset($user->role) || $user->role !== 'user') {
            Auth::logout();
            return redirect()->route('login')
                ->with('error', 'Unauthorized access. User privileges required.');
        }

        return $next($request);
    }
}
