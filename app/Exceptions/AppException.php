<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class AppException extends Exception
{
    public function __construct(
        protected $message    = "Something went wrong",
        protected $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR,
    ) {
        parent::__construct($this->message, $this->statusCode);
    }

    public function render(): JsonResponse
    {
        return response()->json([
            'status'  => 'error',
            'message' => $this->message,
        ], $this->statusCode);
    }
}
