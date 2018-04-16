<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIndicadoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('indicadores', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nome_indicador');
            $table->string('justificativa');
            $table->integer('acao_id')->unsigned()->nullable();
            $table->integer('plano_id')->unsigned()->nullable();
            $table->integer('status')->default(0);
            $table->string('meta_1_tri');
            $table->string('meta_2_tri');
            $table->string('meta_3_tri');
            $table->string('meta_4_tri');
            $table->string('justificativa');
            $table->timestamps();
             //TODAS DEVEM TER ESSAS
            $table->softDeletes();
            $table->integer('deleted_by');
            $table->integer('changed_by');
            $table->integer('created_by');

            $table->foreign('acao_id')->references('id')->on('acoes');
            $table->foreign('plano_id')->references('id')->on('planos');
            $table->foreign('regra_id')->references('id')->on('regra');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('indicadores');
    }
}
