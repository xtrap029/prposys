<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLevelRoutesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ua_level_routes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('ua_route_id');
            $table->unsignedBigInteger('ua_level_id');
            $table->unsignedBigInteger('ua_route_option_id');

            $table->unsignedBigInteger('owner_id');
            $table->unsignedBigInteger('updated_id');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('ua_route_id')->references('id')->on('ua_routes')->onDelete('cascade');
            $table->foreign('ua_level_id')->references('id')->on('ua_levels')->onDelete('cascade');
            $table->foreign('ua_route_option_id')->references('id')->on('ua_route_options')->onDelete('cascade');
            $table->foreign('owner_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('updated_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('level_routes');
    }
}
