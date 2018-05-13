<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

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
     * @param  \Exception  $e
     * @return void
     */
    public function report(Exception $e)
    {
        parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
      if($exception instanceof HttpException && $exception->getStatusCode() == 429) 
        {
            return response()->json([
                   'status_code' => 429,
                   'description' => 'Too Many Requests – You’re requesting too many kittens! Slow down!',
                   'request_data' => []
            ], 429);

        }

       if($exception instanceof HttpException && $exception->getStatusCode() == 500) 
        {
            return response()->json([
                   'status_code' => 500,
                   'description' => 'Internal Server Error – We had a problem with our server. Try again later.',
                   'request_data' => []
            ], 500);

        }


        if($exception instanceof HttpException && $exception->getStatusCode() == 503) 
        {
            return response()->json([
                   'status_code' => 503,
                   'description' => 'Service Unavailable – We’re temporarily offline for maintenance. Please try again later',
                   'request_data' => []
            ], 503);

        }

        if ($exception instanceof NotFoundHttpException) 
        {
            return response()->json( [
                   'status_code' => 404,
                   'description' => 'Route Not Found!',
                   'request_data' => []
            ], 404 );
        }

        if ($exception instanceof MethodNotAllowedHttpException) 
        {
            return response()->json( [
                   'status_code' => 405,
                   'description' => 'Method is not allowed for the requested route',
                   'request_data' => []
            ], 405 );
        }
        return parent::render($request, $e);
    }
}
