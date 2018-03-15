<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAcoesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('acoes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('codigo_acao');
            $table->string('nome');
            $table->integer('plano_id')->unsigned()->nullable();
            $table->integer('programa_id')->unsigned()->nullable();
            $table->integer('especie_acao_id')->unsigned()->nullable();
            $table->integer('linguagem_acao_id')->unsigned()->nullable();
            $table->integer('funcao_acao_id')->unsigned()->nullable();
            $table->integer('status')->default(0);
            $table->string('regiao_acao');
            $table->timestamps();
            //TODAS DEVEM TER ESSAS
            $table->softDeletes();
            $table->integer('deleted_by');
            $table->integer('changed_by');
            $table->integer('created_by');

            $table->foreign('plano_id')->references('id')->on('planos');
            $table->foreign('programa_id')->references('id')->on('programas');
            $table->foreign('especie_acao_id')->references('id')->on('especie_acoes');
            $table->foreign('linguagem_acao_id')->references('id')->on('linguagem_acoes');
            $table->foreign('funcao_acao_id')->references('id')->on('funcao_acoes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('acoes');
    }
}
