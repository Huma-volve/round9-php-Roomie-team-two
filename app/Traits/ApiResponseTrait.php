<?php

namespace App\Traits;

trait ApiResponseTrait
{
    public function successResponse($message = null, $code = 200, $data = null)
    {
        return response()->json([
            'message' => $message ?? 'success',
            'data' => $data,
        ], $code);
    }

    public function errorResponse($message = null, $code = 400, $data = null)
    {
        return response()->json([
            'message' => $message ?? 'error',
            'data' => $data,
        ], $code);
    }
}
