<?php

namespace App\Http\Middleware;

use Closure;

class CheckRole {
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $role = '') {
        $userRole = $request->user();

        if ($userRole && $userRole->count() > 0) {
            if (in_array($userRole->role_id, explode('|', $role))) {
                return $next($request);
            } else {
                return abort(401);
            }            
        } else {
            return redirect('login');
        }

    }
}
