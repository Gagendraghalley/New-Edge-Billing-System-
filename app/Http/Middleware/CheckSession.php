<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CheckSession
{
    public function handle($request, Closure $next)
    {
        if (Auth::check() && !Session::has('lastActivity')) {
            Session::put('lastActivity', time());
        }

        if (Auth::check() && time() - Session::get('lastActivity') > config('session.lifetime') * 2) {
            Auth::logout();
            Session::flush();
            return redirect()->route('userlogin')->with('session_expired', 'Your session has expired. Please log in again.');
        }

        Session::put('lastActivity', time());

        return $next($request);
    }
}

