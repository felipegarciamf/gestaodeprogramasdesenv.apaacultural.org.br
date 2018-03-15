<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlanosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('planos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nome');
            $table->integer('uge_id')->unsigned()->nullable();
            $table->integer('os_id')->unsigned()->nullable();
            $table->integer('cg_id')->unsigned()->nullable();
            $table->integer('objeto_id')->unsigned()->nullable();
            $table->integer('tipoobjeto_id')->unsigned()->nullable();
            $table->integer('status')->default(0);
            $table->datetime('data_limite');
            $table->timestamps();
            //TODAS DEVEM TER ESSAS
            $table->softDeletes();
            $table->integer('deleted_by');
            $table->integer('changed_by');
            $table->integer('created_by');

            $table->foreign('uge_id')->references('id')->on('uges');
            $table->foreign('os_id')->references('id')->on('oss');
            $table->foreign('cg_id')->references('id')->on('cgs');
            $table->foreign('objeto_id')->references('id')->on('objetos');
            $table->foreign('tipoobjeto_id')->references('id')->on('tipo_objetos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('planos');
    }
}
