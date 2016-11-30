<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
          $table->increments('id');
          $table->integer('profile_id')->unsigned();
          $table->string('name')->default('nombre');
          $table->string('lastname')->default('apellido');
          $table->string('dni',8)->unique();
          $table->string('email')->unique();
          $table->string('username')->unique();
          $table->string('password');
          $table->rememberToken();
          $table->timestamps();
          $table->softDeletes();

          $table->foreign('profile_id')->references('id')->on('profiles');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('users');
    }
}
