<?php

namespace App\Http\Controllers\Api\V1;

use App\Exceptions\ApiException;
use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRequest;
use App\Http\Resources\UserResource;
use App\Services\AuthService;
use App\Traits\ApiResponse;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Cookie;


class AuthController extends Controller
{
    use ApiResponse;

    private $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function login(AuthRequest $request)
    {
        try {
            $res = $this->authService->login($request->validated());
            return $this->successResponse(
                [
                    'access_token' => $res['token'],
                    'token_type' => 'bearer',
                    'expires_in' => $res['expires_in'],
                    'user' => $res['user']
                ],
                'Đăng nhập thành công!',
                Response::HTTP_OK
            )->withCookie($res['cookie']);
        } catch (ApiException $e) {
            return $this->errorResponse(
                $e->getMessage(),
                $e->getCode(),
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                $e->getMessage(),
                $e->getCode()
            );
        }
    }

    public function me()
    {
        return $this->successResponse(
            new UserResource(auth()->user()),
            'Đăng nhập thành công!',
            Response::HTTP_OK
        );
    }
}