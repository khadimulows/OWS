<?php

namespace Pitangent\Workflow\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Pitangent\Workflow\Traits\ResponseTrait;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Pitangent\Workflow\Contracts\AuthRestController as RestContract;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Pitangent\Workflow\Traits\AuthenticatableTrait;

abstract class AuthRestController extends Controller implements RestContract
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, ResponseTrait, AuthenticatableTrait;

    /** @var user \Illuminate\Support\Facades\Auth */
    protected $user;
    protected $User;

    public function __construct()
    {
        $this->User = config('auth.providers.users.model');
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            return $next($request);
        });
    }
}
