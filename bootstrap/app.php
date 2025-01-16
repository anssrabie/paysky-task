<?php

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        using: function () {
            Route::middleware('web')->group(base_path('routes/web.php'));

            // Api Routes
            Route::middleware('api')->prefix('api/v1/auth')->group(base_path('routes/api/v1/user/auth.php'));
            Route::middleware(['api','auth:sanctum'])->prefix('api/v1/user')->group(base_path('routes/api/v1/user/user.php'));
        },
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {

        function formatErrorResponse($exception, $statusCode) {
            return response()->json([
                'message' => $exception->getMessage(),
                'status' => false,
                'code' => $statusCode,
                'data' => [],
            ], $statusCode);
        }

        $exceptions->render(function (NotFoundHttpException $exception) {
            return formatErrorResponse($exception, 404);
        });

        $exceptions->render(function (AuthorizationException $exception) {
            return formatErrorResponse($exception, 403);
        });

        $exceptions->render(function (AccessDeniedHttpException $exception) {
            return formatErrorResponse($exception, 403);
        });

        $exceptions->render(function (TooManyRequestsHttpException $exception) {
            return formatErrorResponse($exception, 429);
        });

        $exceptions->render(function (QueryException $exception) {
            return formatErrorResponse($exception, 500);
        });

        $exceptions->render(function (AuthenticationException $exception) {
            return formatErrorResponse($exception, 401);
        });

         //Handling any other exceptions
        $exceptions->render(function (Exception $exception) {
            return formatErrorResponse($exception, 500);
        });

    })->create();
