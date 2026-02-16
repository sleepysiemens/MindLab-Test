<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

abstract class Controller
{
    public function errorHandle(string $message, string|null $error = null, int $code = 500): JsonResponse
    {
        $res = [
            'failed'  => true,
            'message' => $message,
        ];

        if ($error) {
            $res['error'] = $error;
        }

        return response()->json($res, $code);
    }
}
