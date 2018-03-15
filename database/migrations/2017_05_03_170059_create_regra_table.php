<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRegraTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
            Schema::create('regra', function(Blueprint $table){

            $table->increments('id');
            $table->string('codigo')->unique();
            $table->string('descricao');
            $table->integer('plano_id')->unsigned()->nullable();
            $table->timestamps();

            //TODAS DEVEM TER ESSAS 
            $table->softDeletes();
            $table->integer('deleted_by');
            $table->integer('changed_by');
            $table->integer('created_by');

            $table->foreign('plano_id')->references('id')->on('planos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropifExists('regra');
    }
}
