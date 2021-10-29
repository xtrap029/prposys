<?php

namespace App\Helpers;
use App\User;

final class UAHelper {
    public static function get() {
        $routes = [];

        $user = User::find(auth()->id());

        foreach ($user->ualevel->ualevelroutes as $item) {
            if ($item->uaroute) {
                $routes[$item->uaroute->code] = $item->ua_route_option_id;
            }
        }

        return $routes;
    }
}

?>