<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Response;

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
     * @param \Exception $exception
     * @return void
     */
    public function report(Exception $exception)
    {

        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Exception $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {

        if ($request->wantsJson()) {

            if ($exception instanceof \League\OAuth2\Server\Exception\OAuthServerException || $exception instanceof \Illuminate\Auth\AuthenticationException) {

                //AuthenticationException
                if (method_exists($exception, 'guards') && isset($exception->guards()[0]) && $exception->guards()[0] === 'api') {

                    return $this->returnJson('1 - Unauthorized', $exception);
                } else if ($exception instanceof \League\OAuth2\Server\Exception\OAuthServerException) {

                    return $this->returnJson('2 - Unauthorized', $exception);
                } else if ($exception instanceof \Illuminate\Auth\AuthenticationException) {

                    return $this->returnJson('3 - Unauthorized', $exception);
                }
            }
        }

        return parent::render($request, $exception);
    }

    private function returnJson($message, $exception)
    {

        $response['code'] = $exception->getCode();
        //$response['hint'] = $exception->getHint();
        $response['message'] = $message;
        if ($exception instanceof \League\OAuth2\Server\Exception\OAuthServerException)
            return response()->json($response, $exception->getHttpStatusCode(), $exception->getHttpHeaders());
        else
            return response()->json($response, Response::HTTP_UNAUTHORIZED);
    }
}
