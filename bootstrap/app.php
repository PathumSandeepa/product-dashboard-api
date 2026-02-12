<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Auth\AuthenticationException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (TokenExpiredException $e, Request $request) {
            if ($request->is('api/*')) {
                logger()->warning('JWT authentication failed', [
                    'exception' => $e::class,
                    'message' => $e->getMessage(),
                    'path' => $request->path(),
                    'ip' => $request->ip(),
                ]);
                return response()->json([
                    'message' => 'Token has expired. Please login again.',
                ], 401);
            }
            return null;
        });

        $exceptions->render(function (TokenInvalidException $e, Request $request) {
            if ($request->is('api/*')) {
                logger()->warning('JWT authentication failed', [
                    'exception' => $e::class,
                    'message' => $e->getMessage(),
                    'path' => $request->path(),
                    'ip' => $request->ip(),
                ]);
                return response()->json([
                    'message' => 'Token is invalid.',
                ], 401);
            }
            return null;
        });

        $exceptions->render(function (TokenBlacklistedException $e, Request $request) {
            if ($request->is('api/*')) {
                logger()->warning('JWT authentication failed', [
                    'exception' => $e::class,
                    'message' => $e->getMessage(),
                    'path' => $request->path(),
                    'ip' => $request->ip(),
                ]);
                return response()->json([
                    'message' => 'Token is invalid.',
                ], 401);
            }
            return null;
        });

        $exceptions->render(function (JWTException $e, Request $request) {
            if ($request->is('api/*')) {
                logger()->warning('JWT authentication failed', [
                    'exception' => $e::class,
                    'message' => $e->getMessage(),
                    'path' => $request->path(),
                    'ip' => $request->ip(),
                ]);
                return response()->json([
                    'message' => 'Token not provided.',
                ], 401);
            }
            return null;
        });

        $exceptions->render(function (AuthenticationException $e, Request $request) {
            if ($request->is('api/*')) {
                logger()->warning('JWT authentication failed', [
                    'exception' => $e::class,
                    'message' => $e->getMessage(),
                    'path' => $request->path(),
                    'ip' => $request->ip(),
                ]);
                return response()->json([
                    'message' => 'Unauthenticated. Please provide a valid Bearer token.',
                ], 401);
            }
            return null;
        });

        $exceptions->render(function (UnauthorizedHttpException $e, Request $request) {
            if ($request->is('api/*')) {
                logger()->warning('JWT authentication failed', [
                    'exception' => $e::class,
                    'message' => $e->getMessage(),
                    'path' => $request->path(),
                    'ip' => $request->ip(),
                ]);
                return response()->json([
                    'message' => 'Unauthenticated. Please provide a valid Bearer token.',
                ], 401);
            }
            return null;
        });

        $exceptions->render(function (RouteNotFoundException $e, Request $request) {
            if ($request->is('api/*')) {
                logger()->warning('JWT authentication failed', [
                    'exception' => $e::class,
                    'message' => $e->getMessage(),
                    'path' => $request->path(),
                    'ip' => $request->ip(),
                ]);
                return response()->json([
                    'message' => 'Unauthenticated. Please provide a valid Bearer token.',
                ], 401);
            }
            return null;
        });
    })->create();
