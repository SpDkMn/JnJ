<?php

use Illuminate\Database\Seeder;

class ProfileTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        App\Profile::create(['name' => 'Vendedor','weight'=>'1']);
        App\Profile::create(['name' => 'Supervisor','weight'=>'2']);
        App\Profile::create(['name' => 'AdminDis','weight'=>'3']);
        App\Profile::create(['name' => 'Representante','weight'=>'4']);
        App\Profile::create(['name' => 'AdminJ&J','weight'=>'5']);
        App\Profile::create(['name' => 'AdminG','weight'=>'6']);
    }
}
