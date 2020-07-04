<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVatTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vat_types', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code', 10);
            $table->string('name', 50);
            $table->integer('vat');
            $table->integer('wht');
            $table->boolean('is_pr');
            $table->boolean('is_po');
            $table->boolean('is_pc');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vat_types');
    }
}
