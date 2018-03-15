<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAtividadesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('atividades', function (Blueprint $table) {
            $table->increments('id');
            $table->datetime('data');
            $table->string('horario');
            
            $table->datetime('data_fim');
            $table->string('nome');
            $table->string('observacoes');
            $table->string('local');
            $table->integer('capacidade');
            $table->integer('num_total_pessoas');
            $table->integer('num_total_tecnicos');
            $table->integer('num_total_artistas');
            $table->integer('inteira');
            $table->integer('meia');
            $table->integer('morador_entorno');
            $table->integer('prom');
            $table->integer('total_pagantes');
            $table->integer('convite_prod');
            $table->integer('convite_apaa');
            $table->integer('educativo_producao');
            $table->integer('educativo_apaa');
            $table->integer('atend_social_producao');
            $table->integer('atend_social_apaa');
            $table->boolean('sessao_acessivel');
            $table->integer('acessibilidade_acompanhante');
            $table->decimal('bilheteria',10,2);
            $table->decimal('porcent_bilheteria_apaa',10,2);
            $table->string('artista');
            $table->integer('plano_id')->unsigned()->nullable();
            $table->integer('programa_id')->unsigned()->nullable();
            $table->integer('tipo_publico_id')->unsigned()->nullable();
            $table->integer('realizador_id')->unsigned()->nullable();
            $table->integer('linguagem_programa_id')->unsigned()->nullable();
            $table->integer('municipio_id')->unsigned()->nullable();
            $table->integer('tipo_evento_id')->unsigned()->nullable();
            $table->timestamps();
            //TODAS DEVEM TER ESSAS
            $table->softDeletes();
            $table->integer('deleted_by');
            $table->integer('changed_by');
            $table->integer('created_by');


            $table->foreign('plano_id')->references('id')->on('planos');
            $table->foreign('programa_id')->references('id')->on('programas');
            $table->foreign('tipo_publico_id')->references('id')->on('tipo_publicos');
            $table->foreign('realizador_id')->references('id')->on('realizadores');
            $table->foreign('linguagem_programa_id')->references('id')->on('linguagem_programas');
            $table->foreign('municipio_id')->references('id')->on('municipios');
            $table->foreign('tipo_evento_id')->references('id')->on('tipo_eventos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('atividades');
    }
}
