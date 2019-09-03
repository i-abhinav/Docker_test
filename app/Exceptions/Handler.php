<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
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
        if ($exception instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) 
        {
            return response()->json(['error'=>$exception->getMessage()], 422);
        }
        if ($exception instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException) 
        {
            return response()->json(['error'=>$exception->getMessage()], 500);
            // return response()->json(['error'=>'Oops...Not found!'], 500);
        }
        if($exception instanceof \TokenMismatchException)
        {
            return response()->json(['error'=>'Token mismatch'], 500);
        }
        if ($exception instanceof \Illuminate\Database\QueryException) 
        {
            return response()->json(['error'=>$exception->getMessage()], 500);
        }
        if ($exception instanceof \Illuminate\Http\Exception\MethodNotAllowedHttpException) 
        {
            return response()->json(['error'=>$exception->getMessage()], 500);
        }
        if($exception)
        {
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
                $response['error'] = 'Unauthorized';
                break;
            case 403:
                $response['error'] = 'Forbidden';
                break;
            case 404:
                $response['error'] = 'Not Found';
                break;
            case 405:
                $response['error'] = 'Method Not Allowed';
                break;
            case 422:
                $response['error'] = $exception->original['message'];
                $response['errors'] = $exception->original['errors'];
                break;
            case 301:
                $response['error'] = "Invalid API Reuquest";
            default:
            dd($exception);
                $response['error'] = ($statusCode == 500) ? 'Whoops, looks like something went wrong' : $exception->getMessage();
                break;
        }

        /*      
        if (config('app.debug')) {
            $response['trace'] = $exception->getTrace();
            $response['code'] = $exception->getCode();
        }

        $response['status'] = $statusCode;
         */

        return response()->json($response, $statusCode);
    }



}
