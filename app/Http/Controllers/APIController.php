<?php

namespace App\Http\Controllers;
use Illuminate\Http\JsonResponse;

class APIController extends Controller
{
    public function success(int $statusCode = 200, ?string $message = null, $data = null, array $customData = []): JsonResponse
    {
        return response()->json(array_merge($customData, [
            'success' => true,
            'message' => $message,
            'data' => $data,
        ]), $statusCode);
    }

    public function error(?string $message = null, int $status = 500, array $customData = []): JsonResponse
    {
        return response()->json(array_merge($customData, [
            'status' => false,
            'status_code' => $status,
            'message' => $message,
        ]), $status);
    }

    public function badRequest(string $message = 'Bad Request!', int $status = 400, array $customData = []): JsonResponse
    {
        return $this->error($message, $status, $customData);
    }

    public function notFound(string $message = 'Not Found!', int $status = 404, array $customData = []): JsonResponse
    {
        return $this->error($message, $status, $customData);
    }
}
