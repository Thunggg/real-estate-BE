<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

trait ApiResponse
{
    /**
     * Format success response
     */
    public function successResponse(
        mixed $data = null,
        string $message = null,
        int $statusCode = Response::HTTP_OK
    ): JsonResponse {
        return response()->json([
            'success' => true,
            'status_code' => 200,
            'message' => $message,
            'data' => $data
        ], $statusCode);
    }

    /**
     * Format error response
     */
    public function errorResponse(
        string $message = null,
        int $statusCode = Response::HTTP_BAD_REQUEST,
    ): JsonResponse {
        return response()->json([
            'success' => false,
            'status_code' => $statusCode,
            'message' => $message,
        ], $statusCode);
    }
}