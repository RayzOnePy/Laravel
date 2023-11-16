<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
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
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Exception|Throwable $e): \Illuminate\Http\Response|\Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Symfony\Component\HttpFoundation\Response|null
    {
        if ($request->wantsJson()) {
            return $this->handleApiException($request, $e);
        }
        return parent::render($request, $e);
    }

    private function handleApiException($request, Exception|Throwable $exception)
    {
        if ($exception instanceof NotFoundHttpException) {
            return response()->json(['message' => 'Not found', 'code' => 404], 404);
        }

        if ($exception instanceof NotFoundHttpException) {
            return response()->json(['success' => false, 'code' => 401, 'message' => 'Authorization failed'], 401);
        }
    }
}
