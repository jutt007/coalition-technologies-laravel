<?php

namespace App\Http\Traits;

use Illuminate\Http\JsonResponse;

trait ResponseHelper
{
    /**
     * @param array $data
     * @param string $message
     * @return JsonResponse
     */
    public function success(array $data = [], string $message = 'Success'): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data
        ]);
    }

    /**
     * @param string $message
     * @param array $data
     * @return JsonResponse
     */
    public function error(string $message = 'Something went wrong', array $data = []): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'data' => $data
        ]);
    }
}
