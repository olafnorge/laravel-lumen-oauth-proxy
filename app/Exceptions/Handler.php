<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class Handler extends ExceptionHandler {
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];


    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param Throwable $e
     * @return void
     * @throws Exception
     */
    public function report(Throwable $e) {
        parent::report($e);
    }


    /**
     * Render an exception into an HTTP response.
     *
     * @param \Illuminate\Http\Request $request
     * @param Throwable $e
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     * @throws Throwable
     */
    public function render($request, Throwable $e) {
        $rendered = parent::render($request, $e);

        if (config('app.debug') && class_exists(\Whoops\Run::class)) {
            $whoops = new \Whoops\Run();

            if ($request->wantsJson()) {
                $whoops->pushHandler(new \Whoops\Handler\JsonResponseHandler());
            } else {
                $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler());
            }

            return response(
                $whoops->handleException($e),
                $rendered->getStatusCode(),
                $rendered->headers->all()
            );
        }

        return $request->wantsJson()
            ? response()->json(
                ['error' => ['code' => $rendered->getStatusCode(), 'message' => $e->getMessage()]],
                $rendered->getStatusCode(),
                $rendered->headers->all(),
                JSON_PRETTY_PRINT
            )
            : $rendered;
    }
}
