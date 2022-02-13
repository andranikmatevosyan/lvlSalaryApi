<?php

namespace App\Exceptions;

use App\traits\ApiResponse;
use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Foundation\Http\Exceptions\MaintenanceModeException;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Session\TokenMismatchException;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;
use Symfony\Component\CssSelector\Exception\SyntaxErrorException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Http\Response;
use Throwable;

class Handler extends ExceptionHandler
{
    use ApiResponse;

    /**
     * A list of the exception types that are not reported.
     *
     * @var string[]
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var string[]
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });

        $this->renderable(function (NotFoundHttpException $exception, $request) {
            if ($request->wantsJson() || $request->is('api/*')):
                return $this->responseException($exception, Response::HTTP_NOT_FOUND);
            endif;
        });

        $this->renderable(function (TokenMismatchException $exception, $request) {
            if ($request->wantsJson() || $request->is('api/*')):
                return $this->responseException($exception, Response::HTTP_FORBIDDEN);
            endif;
        });

        $this->renderable(function (ThrottleRequestsException $exception, $request) {
            if ($request->wantsJson() || $request->is('api/*')):
                return $this->responseException($exception, Response::HTTP_TOO_MANY_REQUESTS);
            endif;
        });

        $this->renderable(function (MethodNotAllowedHttpException $exception, $request) {
            if ($request->wantsJson() || $request->is('api/*')):
                return $this->responseException($exception, Response::HTTP_TOO_MANY_REQUESTS);
            endif;
        });

        $this->renderable(function (MaintenanceModeException $exception, $request) {
            if ($request->wantsJson() || $request->is('api/*')):
                return $this->responseException($exception, Response::HTTP_TOO_MANY_REQUESTS);
            endif;
        });

        $this->renderable(function (SyntaxErrorException $exception, $request) {
            if ($request->wantsJson() || $request->is('api/*')):
                return $this->responseException($exception, Response::HTTP_NOT_IMPLEMENTED);
            endif;
        });

        $this->renderable(function (UnknownProperties $exception, $request) {
            if ($request->wantsJson() || $request->is('api/*')):
                return $this->responseException($exception, Response::HTTP_NOT_ACCEPTABLE);
            endif;
        });

        $this->renderable(function (Exception $exception, $request) {
            if ($request->wantsJson() || $request->is('api/*')):
                return $this->responseException($exception, Response::HTTP_INTERNAL_SERVER_ERROR);
            endif;
        });

        $this->renderable(function (Throwable $exception, $request) {
            if ($request->wantsJson() || $request->is('api/*')):
                return $this->responseException($exception, Response::HTTP_INTERNAL_SERVER_ERROR);
            endif;
        });

        parent::register();
    }
}
