<?php

use Illuminate\Database\Seeder;

class Users extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Loyalty
        App\User::create(['name' => 'usuario1','lastname'=>'usuario1', 'dni'=>'986532', 'email'=> 'usuario1@correo.com','password'=>bcrypt('123456789'),'profile_id'=>'3']);

        // Representante
        App\User::create(['name' => 'usuario2','lastname'=>'usuario2', 'dni'=>'986533','email'=> 'usuario2@correo.com','password'=>bcrypt('123456789'),'profile_id'=>'2']);
        App\User::create(['name' => 'usuario3','lastname'=>'usuario3', 'dni'=>'986534','email'=> 'usuario3@correo.com','password'=>bcrypt('123456789'),'profile_id'=>'2']);
    }
}
