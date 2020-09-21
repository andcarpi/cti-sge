<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInternshipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('internships', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('ra', 7);

            $table->bigInteger('company_id')->unsigned();
            $table->foreign('company_id')->references('id')->on('companies')->onUpdate('cascade')->onDelete('cascade');

            $table->bigInteger('sector_id')->unsigned();
            $table->foreign('sector_id')->references('id')->on('sectors')->onUpdate('cascade')->onDelete('cascade');

            $table->bigInteger('coordinator_id')->unsigned();
            $table->foreign('coordinator_id')->references('id')->on('coordinators')->onDelete('cascade');

            $table->bigInteger('schedule_id')->unsigned();
            $table->foreign('schedule_id')->references('id')->on('schedules')->onUpdate('cascade')->onDelete('cascade');

            $table->bigInteger('schedule_2_id')->nullable()->unsigned();
            $table->foreign('schedule_2_id')->references('id')->on('schedules')->onUpdate('cascade')->onDelete('cascade');

            $table->bigInteger('state_id')->unsigned();
            $table->foreign('state_id')->references('id')->on('states')->onUpdate('cascade')->onDelete('cascade');

            $table->bigInteger('supervisor_id')->unsigned();
            $table->foreign('supervisor_id')->references('id')->on('supervisors')->onUpdate('cascade')->onDelete('cascade');

            $table->date('start_date');
            $table->date('end_date');

            $table->string('protocol', 7);

            $table->text('activities');
            $table->text('observation')->nullable();
            $table->text('reason_to_cancel')->nullable();
            $table->date('canceled_at')->nullable();

            $table->boolean('active')->default(true);

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
        Schema::dropIfExists('internships');
    }
}
