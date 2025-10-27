<?php

namespace App\Traits;

trait ApiResponses
{

    public function ok($message, $data = [])
    {
        return $this->success($message, $data, 200);
    }
    public function success($message, $data, $statusCode)
    {
        return response()->json([
            "message" => $message,
            'data' => $data,
            "status" => $statusCode,
        ], $statusCode);
    }

    public function error($message, $statusCode)
    {
        return response()->json([
            "message" => $message,
            "status" => $statusCode,
        ], $statusCode);
    }
}