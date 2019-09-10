<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Handler extends ExceptionHandler
{
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
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function render($request, Exception $exception)
    {
        if ($exception instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
            return response()->json(['error' => $exception->getMessage()], 404);
        }
        if ($exception instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException) {
            return response()->json(['error' => $exception->getMessage() ?: 'NOT_FOUND'], 404);
        }
        if ($exception instanceof \Illuminate\Database\QueryException) {
            return response()->json(['error' => $exception->getMessage()], 500);
        }
        if ($exception instanceof \Illuminate\Http\Exception\MethodNotAllowedHttpException) {
            return response()->json(['error' => $exception->getMessage()], 500);
        }
        if ($exception instanceof \App\Exceptions\GoogleMapAPIException) {
            return response()->json(['error' => $exception->getMessage()], 503);
        }
        if ($exception) {
            return $this->_customApiResponse($exception);
        }
        return parent::render($request, $exception);

    }

    private function _customApiResponse($exception)
    {
        if (method_exists($exception, 'getStatusCode')) {
            $statusCode = $exception->getStatusCode();
        } else {
            $statusCode = 500;
        }

        $response = [];

        switch ($statusCode) {
            case 401:
                $response['error'] = 'UNAUTHORIZED';
                break;
            case 403:
                $response['error'] = 'FORBIDDEN';
                break;
            case 404:
                $response['error'] = 'NOT_FOUND';
                break;
            case 405:
                $response['error'] = 'METHOD_NOT_ALLOWED';
                break;
            case 422:
                $response['error'] = $exception->original['message'];
                $response['errors'] = $exception->original['errors'];
                break;
            case 301:
                $response['error'] = "INVALID_API_REUQUEST";
            default:
                $response['error'] = ($statusCode == 500) ? 'SOMETHING_WENT_WRONG' : $exception->getMessage();
                break;
        }

        return response()->json($response, $statusCode);
    }

}
