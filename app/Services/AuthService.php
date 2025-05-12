<?php

namespace App\Services;

use Tymon\JWTAuth\Facades\JWTAuth;

class AuthService
{
    public function attemptLogin(array $credentials): ?string
    {
        return JWTAuth::attempt($credentials);
    }

    public function generateRefreshToken($user): string
    {
        return JWTAuth::claims(['refresh' => true])->fromUser($user);
    }

    public function invalidateToken(string $token): void
    {
        JWTAuth::setToken($token)->invalidate();
    }
}
