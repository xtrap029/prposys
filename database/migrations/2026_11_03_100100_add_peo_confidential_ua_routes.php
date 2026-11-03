<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddPeoConfidentialUaRoutes extends Migration
{
    private $routes = [
        'peo_confidential' => 'People Confidential',
        'peo_confidential_manage' => 'People Confidential Manage',
    ];

    /**
     * Run the migrations.
     *
     * Adds UA routes for the Confidential module (access copied from People UA Level Route)
     * and retires "Transaction View Confidential", now replaced by the confidential mapping.
     *
     * @return void
     */
    public function up()
    {
        $now = now();
        $no_id = config('global.is_yesno_id')[1];

        $base_route = DB::table('ua_routes')->where('code', 'peo_ua_level_route')->first();
        $base_options = $base_route
            ? DB::table('ua_level_routes')->where('ua_route_id', $base_route->id)->get()->keyBy('ua_level_id')
            : collect();
        $levels = DB::table('ua_levels')->whereNull('deleted_at')->pluck('id');

        foreach ($this->routes as $code => $name) {
            // reuse a route that was already added manually
            $route_id = DB::table('ua_routes')->where('code', $code)->value('id') ?: DB::table('ua_routes')->insertGetId([
                'order' => $base_route ? $base_route->order : 0,
                'code' => $code,
                'name' => $name,
                'is_yesno' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            $existing_levels = DB::table('ua_level_routes')->where('ua_route_id', $route_id)->pluck('ua_level_id')->all();

            foreach ($levels as $level_id) {
                if (in_array($level_id, $existing_levels)) {
                    continue;
                }

                $base = $base_options->get($level_id);

                DB::table('ua_level_routes')->insert([
                    'ua_route_id' => $route_id,
                    'ua_level_id' => $level_id,
                    'ua_route_option_id' => $base ? $base->ua_route_option_id : $no_id,
                    'owner_id' => $base ? $base->owner_id : 1,
                    'updated_id' => $base ? $base->updated_id : 1,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }

        DB::table('ua_routes')->where('code', 'trans_view_conf')->update(['deleted_at' => $now]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $route_ids = DB::table('ua_routes')->whereIn('code', array_keys($this->routes))->pluck('id');

        DB::table('ua_level_routes')->whereIn('ua_route_id', $route_ids)->delete();
        DB::table('ua_routes')->whereIn('id', $route_ids)->delete();

        DB::table('ua_routes')->where('code', 'trans_view_conf')->update(['deleted_at' => null]);
    }
}
