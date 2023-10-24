<?php

namespace App\Exceptions;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
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
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $exception)
    {
        if ($exception instanceof BadRequestException) {
            return response()->json([
                'message' => $exception->getMessage(),
                'errors' =>  $exception->getCode(),
                'data' => []
            ], 400);
        }

        if ($exception instanceof ModelNotFoundException) {
            return response()->json([
                'message' => __('error.resource_not_found'),
                'errors' =>  $exception->getCode() ? $exception->getCode() : [],
                'data' => []
            ], 404);
        }

        return parent::render($request, $exception);
    }
}
