<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTravelsRequestTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('travels_request_types', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('name');

            $table->unsignedBigInteger('owner_id');
            $table->unsignedBigInteger('updated_id');
            $table->timestamps();
            $table->softDeletes();

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
        Schema::dropIfExists('travels_request_types');
    }
}
