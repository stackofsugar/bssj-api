<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Throwable;

class Handler extends ExceptionHandler {
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
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
    public function register() {
        $this->reportable(function (Throwable $e) {
            //
        });

        $this->renderable(function (NotFoundHttpException $e, Request $request) {
            if ($request->wantsJson()) {
                return response()->json([
                    "status" => [
                        "code" => 404,
                        "message" => "Page not found",
                    ],
                ], 404);
            }
        });

        $this->renderable(function (UnprocessableEntityHttpException $e, Request $request) {
            if ($request->wantsJson()) {
                return response()->json([
                    "status" => [
                        "code" => 404,
                        "message" => "Unprocessable entity",
                    ],
                ], 422);
            }
        });

        $this->renderable(function (TooManyRequestsHttpException $e, Request $request) {
            if ($request->wantsJson()) {
                return response()->json([
                    "status" => [
                        "code" => 429,
                        "message" => "Too many requests",
                    ],
                ], 429);
            }
        });

        $this->renderable(function (AuthenticationException $e, Request $request) {
            if ($request->wantsJson()) {
                return response()->json([
                    "status" => [
                        "code" => 401,
                        "message" => "Access denied",
                    ],
                ], 401);
            }
        });

        $this->renderable(function (UnauthorizedHttpException $e, Request $request) {
            if ($request->wantsJson()) {
                return response()->json([
                    "status" => [
                        "code" => 401,
                        "message" => "Access denied",
                    ],
                ], 401);
            }
        });
    }
}
