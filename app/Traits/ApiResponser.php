<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponser 
{
    public function successResponse($data,$statusCode = 200) : JsonResponse {
        return response()->json(
            [
                'data' => $data,
            ],
            $statusCode
        );
    }

    public function errorResponse($message,$statusCode) : JsonResponse {
        return response()->json(
            [
                'error' => $message,
            ],
            $statusCode
        );
    }
}