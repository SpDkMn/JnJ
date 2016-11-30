<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConcursoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('concursos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->default('Nombre');
            $table->string('namefile')->default('archivo');
            $table->string('codconcurso')->default('Código');
            $table->string('periodo')->default('MES AÑO');
            $table->integer('representante_id')->unsigned()->nullable();

            /** */
            $table->decimal('volumen', 5, 2);

            $table->decimal('cobertura', 5, 2);

            $table->string('key_condition')->nullable();
            $table->decimal('value_condition', 5, 2)->nullable();
            /** */

            $table->date('f_inicio');
            $table->date('f_fin');

            $table->timestamps();
            $table->softDeletes();

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
        Schema::drop('concursos');
    }
}
