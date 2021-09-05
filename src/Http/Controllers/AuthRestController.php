<?php

namespace Pitangent\Workflow\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Pitangent\Workflow\Traits\ResponseTrait;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Pitangent\Workflow\Contracts\AuthRestController as RestContract;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

abstract class AuthRestController extends Controller implements RestContract
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, ResponseTrait;

    /** @var user \Illuminate\Support\Facades\Auth */
    protected $user;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            return $next($request);
        });
    }

    /**
     * Authenticate a user to access the system.
     * @param  Illuminate\Http\Request $request
     * @return Illuminate\Http\JsonResponse
     */
    abstract public function authenticate( Request $request ) : JsonResponse;

    /**
     * Authenticate a user .
     * @param  Illuminate\Http\Request $request
     * @return Illuminate\Http\JsonResponse
     */
    abstract public function register( Request $request ) : JsonResponse;

    /**
     * get authennticated user details
     * @param  Illuminate\Http\Request $request
     * @return Illuminate\Http\JsonResponse
     */
    abstract public function me( Request $request ) : JsonResponse;

    /**
     * Request for new password .
     * @param  Illuminate\Http\Request $request
     * @return Illuminate\Http\JsonResponse
     */
    abstract public function requestPassword( Request $request ) : JsonResponse;

    /**
     * Reset password.
     * @param  Illuminate\Http\Request $request
     * @return Illuminate\Http\JsonResponse
     */
    abstract public function resetPassword( Request $request ) : JsonResponse;

    /**
     * Change password.
     * @param  Illuminate\Http\Request $request
     * @return Illuminate\Http\JsonResponse
     */
    abstract public function changePassword( Request $request ) : JsonResponse;
}
