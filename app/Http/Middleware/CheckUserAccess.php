<?php

namespace App\Http\Middleware;

use Closure;
use App\UaRoute;
use App\UaLevelRoute;
use Illuminate\Support\Facades\Auth;

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
        if ($route == 'active') {
            $level_id = $request->user()->ua_level_id;
            if ($level_id == config('global.ua_inactive')) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return redirect('/');
            } else {
                return $next($request);
            }
        } else {
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
}
