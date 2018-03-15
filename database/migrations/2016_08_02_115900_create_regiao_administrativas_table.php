<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRegiaoAdministrativasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('regiao_administrativas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nome');
            $table->timestamps();
             //TODAS DEVEM TER ESSAS
            $table->softDeletes();
            $table->integer('deleted_by');
            $table->integer('changed_by');
            $table->integer('created_by');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('regiao_administrativas');
    }
}
