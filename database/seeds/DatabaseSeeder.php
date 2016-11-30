<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(Profile::class);
        $this->call(Users::class);
        $this->call(Representantes::class);
        //$this->call(Ejecutivos::class);
        //$this->call(Distribuidoras::class);
        //$this->call(Supervisores::class);
    }
}
