<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusInTravelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('travels', function (Blueprint $table) {
            $table->string('cancellation_number')->after('traveling_users_static')->nullable();
            $table->text('cancellation_reason')->after('cancellation_number')->nullable();
            $table->unsignedBigInteger('status_id')->after('cancellation_reason')->default('1');

            $table->foreign('status_id')->references('id')->on('travel_status')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('travels', function (Blueprint $table) {
            $table->dropColumn('cancellation_number');
            $table->dropColumn('cancellation_reason');
            $table->dropColumn('status_id');
        });
    }
}
