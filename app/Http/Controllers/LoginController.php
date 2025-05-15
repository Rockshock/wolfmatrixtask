<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AuthService;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\RateLimiter;

class LoginController extends Controller
{
    protected $authService;
    
    public function index(){
        return view('auth.login');
    }

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function login(LoginRequest $request)
    {
        $key = 'login:' . $request->ip();

        if (RateLimiter::tooManyAttempts($key, 3)) {
            $seconds = RateLimiter::availableIn($key);
            return back()->withErrors(['error' => "Too many attempts. Try again in {$seconds} seconds."]);
        }

        $credentials = $request->only('email', 'password');

        $token = $this->authService->attemptLogin($credentials);

        if (!$token) {
            RateLimiter::hit($key, 60);
            return back()->withErrors(['email' => 'Invalid credentials'])->withInput();
        }

        RateLimiter::clear($key);
        $user = JWTAuth::user();
        $accessTokenCookie = Cookie::make('access_token', $token, 60, null, null, false, true, false, 'Strict');
        $refreshToken = $this->authService->generateRefreshToken($user);
        $refreshTokenCookie = Cookie::make('refresh_token', $refreshToken, 1440, null, null, false, true, false, 'Strict');

        activity()
        ->causedBy($user)
        ->withProperties([
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ])
        ->log('User logged in');
        
        return redirect()->route('dashboard')->withCookies([$accessTokenCookie, $refreshTokenCookie]);
    }

    public function logout(Request $request)
    {
        try {
            if ($access = $request->cookie('access_token')) {
                $this->authService->invalidateToken($access);
            }

            if ($refresh = $request->cookie('refresh_token')) {
                $this->authService->invalidateToken($refresh);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error on logout'], 500);
        }

        return redirect()->route('login')
            ->withCookie(Cookie::forget('access_token'))
            ->withCookie(Cookie::forget('refresh_token'));
    }
}