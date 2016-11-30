<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDistribuidorasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('distribuidoras', function (Blueprint $table) {
          $table->increments('id');
          $table->integer('representante_id')->unsigned();
          $table->integer('ejecutivo_id')->unsigned();
          $table->string('name')->default('Razón Social');
          $table->string('coddistribuidora');
          $table->string('ruc',11)->unique();
          $table->string('zona');
          $table->string('address')->default('Av. ...');
          $table->text('reference')->nullable();
          $table->string('phone',20)->default('(+51)00 00 00 0000');
          $table->string('email')->nullable();
          $table->timestamps();
          $table->softDeletes();

          $table->foreign('representante_id')->references('id')->on('representantes');
          $table->foreign('ejecutivo_id')->references('id')->on('ejecutivos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('distribuidoras');
    }
}
