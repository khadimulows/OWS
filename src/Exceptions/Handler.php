<?php

namespace Pitangent\Workflow\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Response;
use Throwable;

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
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        if ( $request->expectsJson() || $request->is('api/*') ) {
            return $this->handleApiException($request, $exception);
        }

        return parent::render($request, $exception);
    }

    private function handleApiException($request, $exception)
    {
        $exception = $this->prepareException($exception);
        return $this->customApiResponse($exception);
    }

    private function customApiResponse($exception)
    {
        if (method_exists($exception, 'getStatusCode'))
            $statusCode = $exception->getStatusCode();
        else
            $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR;

        $response = [
            'data' => null,
            'message' => '',
            'status' => 500,
            'success' => false,
        ];

        switch ($statusCode) {
            case Response::HTTP_UNAUTHORIZED :
                $response['message'] = 'Unauthorized';
                break;
            case Response::HTTP_FORBIDDEN :
                $response['message'] = 'Forbidden';
                break;
            case Response::HTTP_NOT_FOUND :
                $response['message'] = 'Not Found';
                break;
            case Response::HTTP_METHOD_NOT_ALLOWED :
                $response['message'] = 'Method Not Allowed';
                break;
            case Response::HTTP_UNPROCESSABLE_ENTITY :
                $response['message'] = $exception->original['message'];
                $response['errors'] = $exception->original['errors'];
                break;
            default:
                $response['message'] = $exception->getMessage();
                break;
        }

        if (config('pitangent.API_DEBUG')) {
            $response['trace'] = $exception->getTrace();
        }

        $response['status'] = $statusCode;
        return response()->json($response, $statusCode);
    }
}
