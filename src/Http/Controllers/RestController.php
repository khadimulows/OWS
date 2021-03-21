<?php

namespace Pitangent\Workflow\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

abstract class RestController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    
    /** @var \Illuminate\Support\Facades\Auth */
    protected $user;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            return $next($request);
        });
    }
}
