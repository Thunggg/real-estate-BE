<?php

namespace App\Http\Middleware;

use App\Traits\ApiResponse;
use Closure;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

class Jwt
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */

    use ApiResponse;

    public function handle(Request $request, Closure $next): Response
    {


        try {
            if ($request->hasCookie("access_token")) {
                $token = $request->cookie("access_token");
                $request->headers->set('Authorization', "Bearer " . $token);
            }
            $user = JWTAuth::parseToken()->authenticate();
        } catch (TokenInvalidException $e) {
            // return response()->json(['error' => 'Token is Invalid'], 401);
            return $this->errorResponse(
                "Sai token",
                401,
            );
        } catch (TokenExpiredException $e) {
            return $this->errorResponse(
                "Token đã hết hạn",
                401,
            );
        } catch (Exception $e) {
            return $this->errorResponse(
                "Không thể tìm thấy token",
                401,
            );
        }
        return $next($request);
    }
}
