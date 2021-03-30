<?php

namespace Pitangent\Workflow\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Pitangent\Workflow\Traits\ResponseTrait;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

abstract class RestController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, ResponseTrait;
    
    /** @var \Illuminate\Support\Facades\Auth */
    protected $user;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            return $next($request);
        });
    }

    /**
     * Get all record on the controller.
     * @param  Illuminate\Http\Request $request
     * @return Illuminate\Http\JsonResponse
     */
    abstract public function getList( Request $request ) : JsonResponse;

    /**
     * Get grecord on the controller.
     * @param  int  $id
     * @param  Illuminate\Http\Request $request
     * @return Illuminate\Http\JsonResponse
     */
    abstract public function getById( int $id, Request $request ) : JsonResponse;

    /**
     * create new record on the controller.
     * @param  Illuminate\Http\Request $request
     * @return Illuminate\Http\JsonResponse
     */
    abstract public function create( Request $request ) : JsonResponse;

    /**
     * Update record on the controller.
     * @param  int  $id
     * @param  Illuminate\Http\Request $request
     * @return Illuminate\Http\JsonResponse
     */
    abstract public function updateById( int $id, Request $request ) : JsonResponse;

    /**
     * Delete record on the controller.
     * @param  int  $id
     * @param  Illuminate\Http\Request $request
     * @return Illuminate\Http\JsonResponse
     */
    abstract public function deleteById( int $id, Request $request ) : JsonResponse;

}
