<?php

namespace App\Exceptions;


use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Validation\UnauthorizedException;
use Illuminate\Validation\ValidationException as ValidationValidationException;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
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
            return $this->renderError($exception, 400);
        }

        if ($exception instanceof NotFoundHttpException) {
            return $this->renderError($exception, 404, __('error.url_not_found'));
        }

        if ($exception instanceof ModelNotFoundException) {
            return $this->renderError($exception, 404, __('error.resource_not_found'));
        }

        if ($exception instanceof UnauthorizedException) {
            return $this->renderError($exception, 401, __('auth.errors.an_authentication_error_occurred'));
        }

        if ($exception instanceof MethodNotAllowedHttpException) {
            return $this->renderError($exception, 403, __('errors.method_not_allowed'));
        }

        if ($exception instanceof ValidationValidationException) {
            return $this->renderError($exception, 422, __('errors.the_sent_parameters_are_invalid'));
        }

        if ($exception instanceof ThrottleRequestsException) {
            return $this->renderError($exception, 429, __('errors.too_many_request'));
        }

        return $this->renderError($exception, 500, __('errors.server_error'));
    }

    private function renderError(Throwable $exception, int $errorCode, ?string $message = null)
    {
        return response()->json([
            'message' => ($message ? $message : $exception->getMessage()),
            'errors' => (isset($exception->validator) ? $exception->validator->getMessageBag() : []),
            'data' => []
        ], $errorCode);
    }
}
