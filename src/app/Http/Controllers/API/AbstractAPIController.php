<?php
declare(strict_types=1);

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;

class AbstractAPIController extends Controller
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

    public function getResponse(
        bool $failed = false,
        string|null $message = null,
        array|JsonResource|null $data = null,
        int $status = 200,
    ): JsonResponse
    {
        $res = [
            'failed' => $failed,
        ];

        if ($message) {
            $res['message'] = $message;
        }

        if ($data) {
            $res['data'] = $data;
        }

        return response()->json($res, $status);
    }
}
