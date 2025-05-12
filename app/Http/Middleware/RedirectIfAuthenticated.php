<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->cookie('access_token');

        if ($token) {
            try {
                if (JWTAuth::setToken($token)->authenticate()) {
                    return redirect()->route('dashboard');
                }
            } catch (\Exception $e) {
                return redirect()->route('login');
            }
        }

        return $next($request);
    }
}
