<?php

if (!function_exists('apiResponse')) {
    /**
     * Return standardized JSON response
     *
     * @param bool $success
     * @param string $message
     * @param mixed $data
     * @param int $status
     * @return \Illuminate\Http\JsonResponse
     */
    function apiResponse($data = null, $message = null, $success = true, $status = 200)
    {
        return response()->json([
            'success' => $success,
            'message' => $message,
            'data' => $data
        ], $status);
    }
}
