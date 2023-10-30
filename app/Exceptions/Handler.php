<?php

namespace App\Exceptions;


use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Validation\UnauthorizedException;
use Illuminate\Validation\ValidationException as ValidationValidationException;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
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
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            if (app()->bound('sentry')) {
                app('sentry')->captureException($e);
            }
        });
    }

    public function render($request, Throwable $exception)
    {
        if ($exception instanceof BadRequestException) {
            return response()->json([
                'message' => $exception->getMessage(),
                'errors' => (isset($exception->validator) ? $exception->validator->getMessageBag() : []),
                'data' => []
            ], 400);
        }

        if ($exception instanceof ModelNotFoundException) {

            return response()->json([
                'message' => __('error.resource_not_found'),
                'errors' => (isset($exception->validator) ? $exception->validator->getMessageBag() : []),
                'data' => []
            ], 404);
        }

        if ($exception instanceof UnauthorizedException) {
            return response()->json([
                'message' => __('auth.errors.an_authentication_error_occurred'),
                'errors' => (isset($exception->validator) ? $exception->validator->getMessageBag() : []),
                'data' => []
            ], 401);
        }

        if ($exception instanceof MethodNotAllowedHttpException) {
            return response()->json([
                'message' => __('error.method_not_allowed'),
                'errors' => (isset($exception->validator) ? $exception->validator->getMessageBag() : []),
                'data' => []
            ], 403);
        }

        if ($exception instanceof ValidationValidationException) {
            return response()->json([
                'message' => __('error.the_sent_parameters_are_invalid'),
                'errors' => (isset($exception->validator) ? $exception->validator->getMessageBag() : []),
                'data' => []
            ], 422);
        }

        if($exception instanceof ThrottleRequestsException){
            return response()->json([
                'message' => __('error.too_many_request'),
                'errors' => (isset($exception->validator) ? $exception->validator->getMessageBag() : []),
                'data' => []
            ], 429);
        }

        return response()->json([
            'message' => __('error.server_error'),
            'errors' =>  $exception->getCode() ? $exception->getCode() : [],
            'data' => []
        ], 500);

        return parent::render($request, $exception);
    }
}
