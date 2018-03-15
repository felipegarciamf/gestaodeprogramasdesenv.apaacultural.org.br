<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGenerosLinguagemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('generos_linguagem', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nome');
            $table->integer('linguagem_programa_id')->unsigned()->nullable();
            $table->timestamps();
            //TODAS DEVEM TER ESSAS
            $table->softDeletes();
            $table->integer('deleted_by');
            $table->integer('changed_by');
            $table->integer('created_by');

            $table->foreign('linguagem_programa_id')->references('id')->on('linguagem_programas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('generos_linguagem');
    }
}
