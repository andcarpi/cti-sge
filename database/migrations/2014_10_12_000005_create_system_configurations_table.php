<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSystemConfigurationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('system_configurations', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('name', 60);
            $table->string('cep', 9);
            $table->string('uf', 2);
            $table->string('city', 30);
            $table->string('street', 50);
            $table->string('number', 6);
            $table->string('district', 50);
            $table->string('phone', 11);
            $table->string('extension', 5)->nullable();
            $table->string('fax', 10)->nullable();
            $table->string('email', 50);
            $table->integer('agreement_expiration');

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
        Schema::dropIfExists('system_configurations');
    }
}
