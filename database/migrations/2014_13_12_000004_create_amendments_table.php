<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAmendmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('amendments', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->bigInteger('internship_id')->unsigned();
            $table->foreign('internship_id')->references('id')->on('internships')->onUpdate('cascade')->onDelete('cascade');

            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->date('new_end_date')->nullable();

            $table->bigInteger('schedule_id')->nullable()->unsigned();
            $table->foreign('schedule_id')->references('id')->on('schedules')->onUpdate('cascade')->onDelete('cascade');

            $table->bigInteger('schedule_2_id')->nullable()->unsigned();
            $table->foreign('schedule_2_id')->references('id')->on('schedules')->onUpdate('cascade')->onDelete('cascade');

            $table->string('protocol', 7);
            $table->text('observation')->nullable();

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
        Schema::dropIfExists('amendments');
    }
}
