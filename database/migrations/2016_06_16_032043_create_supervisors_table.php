<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSupervisorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supervisores', function (Blueprint $table) {
          $table->increments('id');
          $table->integer('distribuidor_id')->unsigned();
          $table->string('dni')->unique();
          $table->string('name');
          $table->string('lastname')->nullable();
          $table->string('lastname2')->nullable();
          $table->string('address')->nullable();
          $table->string('distrito')->nullable();
          $table->string('provincia')->nullable();
          $table->string('departamento')->nullable();
          $table->string('cel_phone')->nullable();
          $table->string('phone')->nullable();
          $table->string('email')->nullable();

          $table->boolean('cargo')->default(0);
          $table->timestamps();
          $table->softDeletes();

          $table->foreign('distribuidor_id')->references('id')->on('distribuidoras');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('supervisores');
    }
}
