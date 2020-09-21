<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->time('mon_s')->nullable();
            $table->time('mon_e')->nullable();

            $table->time('tue_s')->nullable();
            $table->time('tue_e')->nullable();

            $table->time('wed_s')->nullable();
            $table->time('wed_e')->nullable();

            $table->time('thu_s')->nullable();
            $table->time('thu_e')->nullable();

            $table->time('fri_s')->nullable();
            $table->time('fri_e')->nullable();

            $table->time('sat_s')->nullable();
            $table->time('sat_e')->nullable();

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
        Schema::dropIfExists('schedules');
    }
}
