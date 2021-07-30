<?php

namespace Pitangent\Workflow\Contracts;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

interface RestController {
    /**
     * Get all record on the controller.
     * @param  Illuminate\Http\Request $request
     * @return Illuminate\Http\JsonResponse
     */
    public function getList( Request $request ) : JsonResponse;

    /**
     * Get grecord on the controller.
     * @param  int  $id
     * @param  Illuminate\Http\Request $request
     * @return Illuminate\Http\JsonResponse
     */
    public function getById( int $id, Request $request ) : JsonResponse;

    /**
     * create new record on the controller.
     * @param  Illuminate\Http\Request $request
     * @return Illuminate\Http\JsonResponse
     */
    public function create( Request $request ) : JsonResponse;

    /**
     * Update record on the controller.
     * @param  int  $id
     * @param  Illuminate\Http\Request $request
     * @return Illuminate\Http\JsonResponse
     */
    public function updateById( int $id, Request $request ) : JsonResponse;

    /**
     * Delete record on the controller.
     * @param  int  $id
     * @param  Illuminate\Http\Request $request
     * @return Illuminate\Http\JsonResponse
     */
    public function deleteById( int $id, Request $request ) : JsonResponse;

}
