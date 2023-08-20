<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class SessionProtection
{
    public function handle($request, Closure $next)
    {
        // Check if the user is authenticated and the session is active
        if (Auth::check() && !Session::has('lastActivity')) {
            Session::put('lastActivity', time());
        }

        if (Auth::check() && time() - Session::get('lastActivity') > config('session.lifetime') * 60) {
            Auth::logout();
            Session::flush();
            return redirect()->route('userlogin')->with('session_expired', 'Your session has expired. Please log in again.');
        }

        Session::put('lastActivity', time());

        return $next($request);
    }
}
