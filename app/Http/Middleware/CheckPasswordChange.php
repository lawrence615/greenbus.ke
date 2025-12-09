<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPasswordChange
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->mustChangePassword()) {
            // Allow logout
            if ($request->routeIs('logout')) {
                return $next($request);
            }

            // Redirect to password change page
            if (!$request->routeIs('password.change', 'password.change.update')) {
                return redirect()->route('password.change')
                    ->with('warning', 'Please change your password before continuing.');
            }
        }

        return $next($request);
    }
}
