<?php

namespace Pitangent\Workflow\Contracts;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

interface AuthRestController {
    /**
     * Authenticate a user to access the system.
     * @param  Illuminate\Http\Request $request
     * @return Illuminate\Http\JsonResponse
     */
    public function authenticate( Request $request ) : JsonResponse;

    /**
     * Authenticate a user .
     * @param  Illuminate\Http\Request $request
     * @return Illuminate\Http\JsonResponse
     */
    public function register( Request $request ) : JsonResponse;

    /**
     * get authennticated user details
     * @param  Illuminate\Http\Request $request
     * @return Illuminate\Http\JsonResponse
     */
    public function me( Request $request ) : JsonResponse;

    /**
     * Request for new password .
     * @param  Illuminate\Http\Request $request
     * @return Illuminate\Http\JsonResponse
     */
    public function requestPassword( Request $request ) : JsonResponse;

    /**
     * Reset password.
     * @param  Illuminate\Http\Request $request
     * @return Illuminate\Http\JsonResponse
     */
    public function resetPassword( Request $request ) : JsonResponse;

    /**
     * Change password.
     * @param  Illuminate\Http\Request $request
     * @return Illuminate\Http\JsonResponse
     */
    public function changePassword( Request $request ) : JsonResponse;
}
