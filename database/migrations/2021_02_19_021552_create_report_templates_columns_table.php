<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportTemplatesColumnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('report_templates_columns', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('report_template_id');
            $table->unsignedBigInteger('report_column_id');
            $table->string('label');
            
            $table->unsignedBigInteger('owner_id');
            $table->timestamps();

            $table->foreign('report_template_id')->references('id')->on('report_templates')->onDelete('cascade');
            $table->foreign('report_column_id')->references('id')->on('report_columns')->onDelete('cascade');
            $table->foreign('owner_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('report_templates_columns');
    }
}
