<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jobs', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('ra', 7);

            $table->string('ctps', 13)->nullable();

            $table->bigInteger('company_id')->unsigned();
            $table->foreign('company_id')->references('id')->on('job_companies')->onUpdate('cascade')->onDelete('cascade');

            $table->bigInteger('sector_id')->unsigned();
            $table->foreign('sector_id')->references('id')->on('sectors')->onUpdate('cascade')->onDelete('cascade');

            $table->bigInteger('coordinator_id')->unsigned();
            $table->foreign('coordinator_id')->references('id')->on('coordinators')->onUpdate('cascade')->onDelete('cascade');

            $table->bigInteger('state_id')->unsigned();
            $table->foreign('state_id')->references('id')->on('states')->onUpdate('cascade')->onDelete('cascade');

            $table->date('start_date');
            $table->date('end_date');

            $table->string('protocol', 7);
            $table->string('approval_number', '8');

            $table->text('activities')->nullable();
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
        Schema::dropIfExists('jobs');
    }
}
