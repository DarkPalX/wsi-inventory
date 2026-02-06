<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Admin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if the user is authenticated
        if (Auth::check()) {
            // Check if the user is an admin (role_id is not 6)
            if (Auth::user()->role_id != 6) {
                return $next($request); // Allow the request to proceed
            } else {
                // If the user is not an admin, return a 403 error
                abort(403, 'Unauthorized page access');
            }
        } else {
            // If the user is not authenticated, redirect to login page
            return redirect()->route('login');
        }
    }
}
