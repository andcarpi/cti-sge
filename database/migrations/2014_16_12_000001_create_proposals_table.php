<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProposalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('proposals', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->bigInteger('company_id')->unsigned();
            $table->foreign('company_id')->references('id')->on('companies');

            $table->date('deadline');

            $table->bigInteger('schedule_id')->nullable()->unsigned();
            $table->foreign('schedule_id')->references('id')->on('schedules');

            $table->bigInteger('schedule_2_id')->nullable()->unsigned();
            $table->foreign('schedule_2_id')->references('id')->on('schedules');

            $table->float('remuneration')->default(0);
            $table->text('description');
            $table->text('requirements');
            $table->text('benefits')->nullable();
            $table->bigInteger('type')->default(1);
            $table->text('observation')->nullable();

            $table->string('email');
            $table->string('subject');
            $table->string('phone', 11)->nullable();
            $table->text('other')->nullable();

            $table->timestamp('approved_at')->nullable();
            $table->text('reason_to_reject')->nullable();

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
        Schema::dropIfExists('proposals');
    }
}
