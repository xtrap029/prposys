<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->enum('trans_type', ['pr', 'po', 'pc']);
            $table->year('trans_year');
            $table->integer('trans_seq');
            $table->unsignedBigInteger('particulars_id');
            $table->enum('currency', ['PHP', 'USD']);
            $table->decimal('amount', 10, 2);
            $table->text('purpose');
            $table->string('payee');
            $table->unsignedBigInteger('project_id');
            $table->date('due_at');
            $table->unsignedBigInteger('requested_id');

            $table->enum('control_type', ['CN', 'PC'])->nullable();
            $table->string('control_no')->nullable();
            $table->date('released_at')->nullable();
            $table->decimal('amount_issued', 10, 2)->nullable();

            $table->text('cancellation_reason')->nullable();
            $table->unsignedBigInteger('status_id')->default('1');
            $table->tinyInteger('edit_count')->default('0');
            
            $table->unsignedBigInteger('owner_id');
            $table->unsignedBigInteger('updated_id');
            $table->timestamps();
            $table->softDeletes();
            
            $table->foreign('particulars_id')->references('id')->on('particulars')->onDelete('cascade');
            $table->foreign('project_id')->references('id')->on('company_projects')->onDelete('cascade');
            $table->foreign('requested_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('status_id')->references('id')->on('transaction_status')->onDelete('cascade');
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
        Schema::dropIfExists('transactions');
    }
}
