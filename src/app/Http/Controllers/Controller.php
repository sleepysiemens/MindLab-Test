<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

abstract class Controller
{
    public function errorHandle(string $message, string $error): JsonResponse
    {
        return response()->json([
            'failed'  => true,
            'message' => $message,
            'error'   => $error,
        ]);
    }
}
