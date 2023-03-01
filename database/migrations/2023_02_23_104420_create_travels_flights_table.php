<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTravelsFlightsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('travels_flights', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('travel_id');

            $table->string('name');
            $table->text('remarks')->nullable();
            $table->dateTime('time_in');
            $table->dateTime('time_out');
            $table->decimal('fee', 10, 2)->default(0);
            $table->decimal('fee_baggage', 10, 2)->default(0);
            $table->decimal('fee_car', 10, 2)->default(0);
            $table->decimal('fee_land', 10, 2)->default(0);

            $table->unsignedBigInteger('owner_id');
            $table->unsignedBigInteger('updated_id');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('travel_id')->references('id')->on('travels')->onDelete('cascade');
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
        Schema::dropIfExists('travels_flights');
    }
}
