<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkerStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('worker_statuses', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->bigInteger('worker_id')->nullable(false)->unsigned();
            $table->integer('year')->nullable(false);
            $table->integer('month')->nullable(false);
            $table->enum('is_retiree', ['yes', 'no'])->nullable(false)->default('no');
            $table->enum('is_handicapped', ['yes', 'no'])->nullable(false)->default('no');
            $table->enum('handicapped_group', [1, 2, 3])->nullable(true)->default(null);
            $table->timestamps();
            $table->unique(['worker_id', 'year', 'month'], 'idx_unique_worker_status');
            $table->foreign('worker_id')->references('id')->on('workers')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('worker_statuses');
    }
}
