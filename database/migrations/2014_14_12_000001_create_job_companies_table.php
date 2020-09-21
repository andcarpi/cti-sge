<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_companies', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('cpf_cnpj', 15)->unique();
            $table->string('ie', 20)->nullable()->unique();
            $table->boolean('pj')->default(true);
            $table->string('name');
            $table->string('fantasy_name')->nullable();

            $table->string('representative_name', 50);
            $table->string('representative_role', 50);

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
        Schema::dropIfExists('job_companies');
    }
}
