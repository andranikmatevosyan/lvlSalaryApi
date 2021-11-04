<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('salaries', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->bigInteger('worker_id')->nullable(false)->unsigned();
            $table->integer('year')->nullable(false);
            $table->integer('month')->nullable(false);
            $table->integer('month_days_norm')->nullable(false);
            $table->integer('month_days_work')->nullable(false);
            $table->enum('has_mzp', ['yes', 'no'])->nullable(false);
            $table->float('salary')->nullable(false);
            $table->float('norm_salary')->nullable(false);
            $table->float('net_salary')->nullable(false);
            $table->float('tax_ipn')->nullable(true)->default(null);
            $table->float('tax_opv')->nullable(true)->default(null);
            $table->float('tax_so')->nullable(true)->default(null);
            $table->float('insurance_osms')->nullable(true)->default(null);
            $table->float('insurance_vosms')->nullable(true)->default(null);
            $table->string('currency')->nullable(false)->default('KZT');
            $table->timestamps();
            $table->unique(['worker_id', 'year', 'month'], 'idx_unique_salary');
            $table->foreign('worker_id')->references('id')->on('workers')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('salaries');
    }
}
