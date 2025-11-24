<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;


class SessionAuth
{
    public function handle(Request $request, Closure $next)
    {
        // Check if user is logged in using session token
        if (!session()->has('token') || empty(session('token'))) {
            return redirect()->route('signin')->with('error', 'Please login to continue.');
        }

        return $next($request);
    }
}
