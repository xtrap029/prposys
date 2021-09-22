<?php

namespace App\Http\Middleware;

use Closure;

class CheckReadOnly
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        if ($request->user()->is_read_only) {
            return abort(401);
        } else {
            return $next($request);
        } 
    }
}