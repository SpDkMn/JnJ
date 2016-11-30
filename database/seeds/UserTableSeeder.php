<?php

use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      // Super Admin
      App\User::create(['name' => 'usuario1','email'=> 'usuario1@correo.com','password'=>bcrypt('123456789'),'profile_id'=>'6']);

      // Admin de J&J
      App\User::create(['name' => 'usuario2','email'=> 'usuario2@correo.com','password'=>bcrypt('123456789'),'profile_id'=>'5']);

      // Admin Distribuidora
      App\User::create(['name' => 'usuario3','email'=> 'usuario3@correo.com','password'=>bcrypt('123456789'),'profile_id'=>'3']);
      // Supervisor
      App\User::create(['name' => 'usuario4','email'=> 'usuario4@correo.com','password'=>bcrypt('123456789'),'profile_id'=>'2']);
      App\User::create(['name' => 'usuario5','email'=> 'usuario5@correo.com','password'=>bcrypt('123456789'),'profile_id'=>'1']);
      App\User::create(['name' => 'usuario6','email'=> 'usuario6@correo.com','password'=>bcrypt('123456789'),'profile_id'=>'1']);

      // Supervisor
      App\User::create(['name' => 'usuario7','email'=> 'usuario7@correo.com','password'=>bcrypt('123456789'),'profile_id'=>'2']);
      App\User::create(['name' => 'usuario8','email'=> 'usuario8@correo.com','password'=>bcrypt('123456789'),'profile_id'=>'1']);
      App\User::create(['name' => 'usuario9','email'=> 'usuario9@correo.com','password'=>bcrypt('123456789'),'profile_id'=>'1']);


      // Admin Distribuidora
      App\User::create(['name' => 'usuario10','email'=> 'usuario10@correo.com','password'=>bcrypt('123456789'),'profile_id'=>'3']);
      // Supervisor
      App\User::create(['name' => 'usuari011','email'=> 'usuario11@correo.com','password'=>bcrypt('123456789'),'profile_id'=>'2']);
      App\User::create(['name' => 'usuario12','email'=> 'usuario12@correo.com','password'=>bcrypt('123456789'),'profile_id'=>'1']);
      App\User::create(['name' => 'usuario13','email'=> 'usuario13@correo.com','password'=>bcrypt('123456789'),'profile_id'=>'1']);

      // Supervisor
      App\User::create(['name' => 'usuario14','email'=> 'usuario14@correo.com','password'=>bcrypt('123456789'),'profile_id'=>'2']);
      App\User::create(['name' => 'usuario15','email'=> 'usuario15@correo.com','password'=>bcrypt('123456789'),'profile_id'=>'1']);
      App\User::create(['name' => 'usuario16','email'=> 'usuario16@correo.com','password'=>bcrypt('123456789'),'profile_id'=>'1']);

      // Representantes
      App\User::create(['name' => 'usuario17','email'=> 'usuario17@correo.com','password'=>bcrypt('123456789'),'profile_id'=>'4']); ##
      App\User::create(['name' => 'usuario18','email'=> 'usuario18@correo.com','password'=>bcrypt('123456789'),'profile_id'=>'4']); ##
    }
}
