<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCierresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cierres', function (Blueprint $table) {
          $table->increments('id');
          $table->decimal('volumen', 5, 2)->nullable();
          $table->decimal('cobertura', 5, 2)->nullable();
          $table->decimal('condicion', 5, 2)->nullable();
          $table->decimal('monto',5,2)->nullable();
          $table->boolean('confirmed')->nullable();

          $table->integer('supervisor_id')->unsigned();
          $table->integer('distribuidor_id')->unsigned();
          $table->integer('concurso_id')->unsigned();
          $table->integer('ejecutivo_id')->unsigned();
          $table->integer('representante_id')->unsigned();
          $table->integer('cuota_id')->unsigned();

          $table->timestamps();
          $table->softDeletes();

          $table->foreign('supervisor_id')->references('id')->on('supervisores');
          $table->foreign('distribuidor_id')->references('id')->on('distribuidoras');
          $table->foreign('concurso_id')->references('id')->on('concursos');
          $table->foreign('ejecutivo_id')->references('id')->on('ejecutivos');
          $table->foreign('representante_id')->references('id')->on('representantes');
          $table->foreign('cuota_id')->references('id')->on('cuotas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('cierres');
    }
}
