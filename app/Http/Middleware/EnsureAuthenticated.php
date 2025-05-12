<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;

class EnsureAuthenticated
{
    public function handle(Request $request, Closure $next)
    {
        try {
            $token = $request->cookie('access_token');
            if (!$token) {
                return redirect()->route('login');
            }

            // Try to authenticate user with access token
            $user = JWTAuth::setToken($token)->authenticate();

            if (!$user) {
                return redirect()->route('login');
            }

            return $next($request);

        } catch (TokenExpiredException $e) {
            // Token expired – attempt to refresh using refresh token
            $refreshToken = $request->cookie('refresh_token');
            if (!$refreshToken) {
                return redirect()->route('login');
            }

            try {
                $newAccessToken = JWTAuth::setToken($refreshToken)->refresh();
                $user = JWTAuth::setToken($newAccessToken)->authenticate();

                // Attach new access token as cookie
                return $next($request)->withCookie(
                    cookie('access_token', $newAccessToken, 15, null, null, true, true, false, 'Strict')
                );
            } catch (JWTException $e) {
                return redirect()->route('login');
            }

        } catch (JWTException $e) {
            return redirect()->route('login');
        }
    }
}