<?php

namespace App\Http\Middleware;

use Closure;

class CheckAppAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $app = '') {
        if (!in_array($app, explode(',', $request->user()->apps))) {
            return abort(401);
        } else {
            return $next($request);
        }
    }
}
