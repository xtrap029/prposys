<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateUaLevelConfidentialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * Maps an owner's UA level to the UA levels allowed to view its confidential transactions.
     * An owner level without rows means its confidential transactions are visible to all levels.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ua_level_confidentials', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('ua_level_id');
            $table->unsignedBigInteger('viewer_ua_level_id');
            $table->unsignedBigInteger('owner_id')->nullable();
            $table->unsignedBigInteger('updated_id')->nullable();
            $table->timestamps();

            $table->unique(['ua_level_id', 'viewer_ua_level_id']);
            $table->foreign('ua_level_id')->references('id')->on('ua_levels')->onDelete('cascade');
            $table->foreign('viewer_ua_level_id')->references('id')->on('ua_levels')->onDelete('cascade');
        });

        // Seed to reproduce the previous rule: confidential transactions were visible to
        // higher levels, plus levels with "Transaction View Confidential" (trans_view_conf) = Yes
        $inactive_id = config('global.ua_inactive');
        $levels = DB::table('ua_levels')->whereNull('deleted_at')->where('id', '!=', $inactive_id)->get(['id', 'code']);

        $view_conf_route = DB::table('ua_routes')->where('code', 'trans_view_conf')->first();
        $view_conf_levels = $view_conf_route
            ? DB::table('ua_level_routes')
                ->where('ua_route_id', $view_conf_route->id)
                ->where('ua_route_option_id', config('global.is_yesno_id')[0])
                ->pluck('ua_level_id')
                ->all()
            : [];

        $now = now();
        $rows = [];

        foreach ($levels as $owner_level) {
            foreach ($levels as $viewer_level) {
                if ((int) $viewer_level->code > (int) $owner_level->code || in_array($viewer_level->id, $view_conf_levels)) {
                    $rows[] = [
                        'ua_level_id' => $owner_level->id,
                        'viewer_ua_level_id' => $viewer_level->id,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }
            }
        }

        DB::table('ua_level_confidentials')->insert($rows);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ua_level_confidentials');
    }
}
