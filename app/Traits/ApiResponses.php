<?php

namespace App\Traits;

trait ApiResponses {

    public function ok($message) {
        return  $this->success($message, 200);
    }
    public function success($message, $statusCode) {
        return response()->json([
            "message" => $message,
            "status" => $statusCode,
        ], $statusCode);
    }
}