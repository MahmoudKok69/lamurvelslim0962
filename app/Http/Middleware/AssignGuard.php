<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Traits\GeneralTrait;

class AssignGuard
{
    use GeneralTrait;

    public function handle(Request $request, Closure $next, $guard)
    {
        //echo $guard;
        if(Auth::guard($guard)->user())
            return $next($request);
        return $this->returnError('please sign in first', 403);
    }
}
