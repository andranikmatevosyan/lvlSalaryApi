<?php

namespace App\traits;


use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Throwable;

trait ApiResponse
{
    /**
     * @param $data
     * @return JsonResponse
     */
    public function response200($data): JsonResponse
    {
        return response()->json(compact('data'), Response::HTTP_OK);
    }

    /**
     * @param $data
     * @return JsonResponse
     */
    public function response201($data): JsonResponse
    {
        return response()->json(compact('data'), Response::HTTP_CREATED);
    }

    /**
     * @param $data
     * @return JsonResponse
     */
    public function response202($data): JsonResponse
    {
        return response()->json(compact('data'), Response::HTTP_ACCEPTED);
    }

    /**
     * @return JsonResponse
     */
    public function response204(): JsonResponse
    {
        return response()->json([], Response::HTTP_ACCEPTED);
    }

    /**
     * @param $message
     * @return JsonResponse
     */
    public function response401($message): JsonResponse
    {
        return response()->json(compact('message'), Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @param $message
     * @return JsonResponse
     */
    public function response404($message): JsonResponse
    {
        return response()->json(compact('message'), Response::HTTP_NOT_FOUND);
    }

    /**
     * @param $errors
     * @return JsonResponse
     */
    public function response422($errors): JsonResponse
    {
        return response()->json(compact('errors'), Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @param $errors
     * @return JsonResponse
     */
    public function response500($errors): JsonResponse
    {
        return response()->json(compact('errors'), Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * @param Throwable $exception
     * @param int $code
     * @return JsonResponse
     */
    public function responseException(Throwable $exception, int $code): JsonResponse
    {
        return response()->json([
            'message' => $exception->getMessage(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine()
        ], $code);
    }
}
