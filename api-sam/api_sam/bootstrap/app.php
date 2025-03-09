<?php

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api:  __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {

        $exceptions->render(function (Throwable $e, Request $request) {
            
            report($e);

            if ($request->is('api/*'))
            {
                if ($e instanceof ValidationException)
                {
                    return response()->json([
                        'error'   => true,
                        'message' => $e->errors(),
                        'context' => 'app.error',
                        'code'    => 422
                    ], status: 422);

                } else if ($e instanceof AuthorizationException) {

                    return response()->json([
                        'error'     => true,
                        'message'   => 'Você não tem permissão para acessar este recurso.',
                        'context'   => 'app.error',
                        'code'      => 403
                    ], 403);
                
                } else if ($e instanceof NotFoundHttpException) {

                    return response()->json([
                        'error'     => true,
                        'message'   => 'Recurso não encontrado.',
                        'context'   => 'app.error',
                        'code'      => 404
                    ], 404);
                
                } 
                else if ($e instanceof ModelNotFoundException) {

                    return response()->json([
                        'error'     => true,
                        'message'   => 'A entidade solicitada não existe ou não foi encontrada.',
                        'context'   => 'app.error',
                        'code'      => 404
                    ], 404);
                
                } else {

                    return response()->json([
                        'error'     => true,
                        'message'   => 'Erro interno: '.$e->getMessage(),
                        'context'   => 'app.error',
                        'code'      => 500
                    ], 500);
                }
            }
        });   
    })->create();
