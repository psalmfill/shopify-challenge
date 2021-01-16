<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        // dd($request->getRequestFormat());
        if ($request->expectsJson()) {
            if ($exception instanceof ValidationException) {

                return response()->json(
                    [
                        'errors' => $exception->errors()
                    ]
                )->setStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY);
            } elseif ($exception instanceof ModelNotFoundException || $exception instanceof NotFoundHttpException) {

                return response()->json(
                    [
                        'errors' => $exception->getMessage()
                    ]
                )->setStatusCode(Response::HTTP_NOT_FOUND);
            } else {
                return response()->json(
                    [
                        'errors' => $exception->getMessage()
                    ]
                )->setStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY);
            }
        }
        return parent::render($request, $exception);
    }
}
