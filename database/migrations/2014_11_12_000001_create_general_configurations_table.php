<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGeneralConfigurationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('general_configurations', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->integer('max_years');

            $table->integer('min_year');
            $table->integer('min_semester');

            $table->integer('min_hours');
            $table->integer('min_months');
            $table->integer('min_months_ctps');

            $table->float('min_grade');

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
        Schema::dropIfExists('general_configurations');
    }
}
