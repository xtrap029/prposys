<?php

namespace App\Http\Middleware;

use Closure;

class CheckConfidential
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        if (!$request->user()->is_smt && $request->route('transaction')->is_confidential) {
            return abort(401);
        } else {
            return $next($request);
        }       
    }
}
