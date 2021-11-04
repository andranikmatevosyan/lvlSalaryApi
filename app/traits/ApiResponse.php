<?php

namespace App\traits;


use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

trait ApiResponse
{
    /**
     * success response method.
     *
     * @param $result
     * @param $message
     * @param int $code
     * @return JsonResponse
     */
    public function sendResponse($message, $result, int $code = Response::HTTP_NOT_FOUND): JsonResponse
    {
        $response = [
            'success' => true,
            'message' => $message,
            'data'    => $result
        ];

        return response()->json($response, $code);
    }


    /**
     * return error response.
     *
     * @param $error
     * @param $errorMessages
     * @param int $code
     * @return JsonResponse
     */
    public function sendError($error, $errorMessages = [], int $code = Response::HTTP_NOT_FOUND): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $error,
        ];

        if(!empty($errorMessages)){
            $response['data'] = $errorMessages;
        }

        return response()->json($response, $code);
    }
}
