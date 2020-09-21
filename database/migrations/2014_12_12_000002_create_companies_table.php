<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('cpf_cnpj', 15)->unique();
            $table->string('ie', 20)->nullable()->unique();
            $table->boolean('pj')->default(true);

            $table->string('name');
            $table->string('fantasy_name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone', 11)->nullable();

            $table->string('representative_name', 50);
            $table->string('representative_role', 50);

            $table->boolean('active')->default(true);

            $table->bigInteger('address_id')->unsigned();
            $table->foreign('address_id')->references('id')->on('addresses')->onUpdate('cascade')->onDelete('cascade');

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
        Schema::dropIfExists('companies');
    }
}
