<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
    public function report(Exception $exception)
    {
        if ($exception instanceof ModelNotFoundException) {
            return response()->view('errors.404', [], 404);
        }
        if ($exception instanceof CourseAcceptException) {
            return response()->json(['error' => $exception->getMessage()], $exception->getCode());
        }

        parent::report($exception);
    }

    public function render($request, Exception $exception)
    {
        if ($exception instanceof NotConfirmedUser) {
            return response()->json(['error' => $exception->getMessage()], $exception->getCode());
        }

        return parent::render($request, $exception);
    }
}
