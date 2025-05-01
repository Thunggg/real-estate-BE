<?php

namespace App\Services;

use App\Exceptions\ApiException;
use App\Http\Resources\UserResource;
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

        $ttl = auth()->factory()->getTTL(); // Lấy thời gian sống của token (phút)

        $cookie = cookie(
            'access_token',
            $token,
            $ttl, // Sử dụng TTL theo phút (không cần nhân với 60 * 24)
            '/',
            null,
            true,     // Không dùng HTTPS
            true,      // httpOnly
            false,     // raw
            'none'     // SameSite=None để cho phép cross-site
        );

        $user = auth()->user(); // trả về thông tin của người dùng

        return [
            'token' => $token,
            'cookie' => $cookie,
            'expires_in' => $ttl * 60, // chuyển thành giây
            'user' => new UserResource($user),
        ];
    }
}
