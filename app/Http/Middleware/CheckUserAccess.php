<?php

namespace App\Http\Middleware;

use Closure;
use App\UaRoute;
use App\UaLevelRoute;

class CheckUserAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $route = '') {
        $route_id = UaRoute::where('code', $route)->first()->id;
        $level_id = $request->user()->ua_level_id;
        $option_id = UaLevelRoute::where('ua_route_id', $route_id)->where('ua_level_id', $level_id)->first()->ua_route_option_id;
        if ($option_id == config('global.ua_none')) {
            return abort(401);
        } else {
            return $next($request);
        }
    }
}
