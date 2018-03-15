<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRealizadoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('realizadores', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nome');
            $table->integer('municipio_id')->unsigned()->nullable();
            $table->integer('num_total_pessoas');
            $table->integer('num_apresentacoes');
            $table->timestamps();
            //TODAS DEVEM TER ESSAS
            $table->softDeletes();
            $table->integer('deleted_by');
            $table->integer('changed_by');
            $table->integer('created_by');

            $table->foreign('municipio_id')->references('id')->on('municipios');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('realizadores');
    }
}
