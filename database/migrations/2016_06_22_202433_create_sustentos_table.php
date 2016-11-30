<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSustentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('sustentos', function (Blueprint $table) {
        $table->increments('id');
        $table->string('file');

        $table->integer('concurso_id')->unsigned();
        $table->integer('ejecutivo_id')->unsigned();
        $table->integer('representante_id')->unsigned();

        $table->timestamps();
        $table->softDeletes();

        $table->foreign('concurso_id')->references('id')->on('concursos');
        $table->foreign('ejecutivo_id')->references('id')->on('ejecutivos');
        $table->foreign('representante_id')->references('id')->on('representantes');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('sustentos');
    }
}
