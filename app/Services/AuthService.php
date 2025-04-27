<?php

namespace App\Services;

use App\Exceptions\ApiException;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Response;

class AuthService
{
    public function login(array $credentials)
    {
        if (!$token = auth()->attempt($credentials)) {
            throw new ApiException('Sai tài khoản hoặc mật khẩu!', Response::HTTP_UNAUTHORIZED);
        }

        $cookie = cookie(
            'access_token',
            $token,
            auth()->factory()->getTTL() * 1,
            '/', // path
            null, // domain
            app()->environment('production'), // nếu production thì secure=true
            true, // httpOnly
            false, // raw
            'Strict' // sameSite policy
        );

        return [
            'token' => $token,
            'cookie' => $cookie,
            'expires_in' => Auth::factory()->getTTL() * 1
        ];
    }
}
