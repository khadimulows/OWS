<?php
namespace Pitangent\Workflow\Http\Middlewares;

use Closure;
use Exception;
use Illuminate\Http\Request;
use Pitangent\Workflow\Traits\ResponseTrait;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;

class JWTMiddleware extends BaseMiddleware
{
    use ResponseTrait;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle (Request $request, Closure $next)
    {
        if(!$request->header("Authorization")) {
            $this->message = 'Token not found';
            return $this->response(null, false, 400);
        }
        try {
            $request->user = $this->auth->parseToken()->authenticate();
        } catch (Exception $e) {
            if ($e instanceof TokenInvalidException) {
                $this->message = 'Token is Invalid';
                return $this->response(null, false, 401);
            } else if ($e instanceof TokenExpiredException) {
                $this->message = 'Token is Expired';
                return $this->response(null, false, 401);
            } else {
                $this->message = 'Something is wrong';
                return $this->response(null, false, 401);
            }
        }
        return $next($request);
    }
}
