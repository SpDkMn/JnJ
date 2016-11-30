<?php

use Illuminate\Database\Seeder;

class Profile extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        App\Profile::create(['name' => 'Ejecutivo','weight'=>'4']);
        App\Profile::create(['name' => 'Representante','weight'=>'7']);
        App\Profile::create(['name' => 'Loyalty','weight'=>'10']);
    }
}
