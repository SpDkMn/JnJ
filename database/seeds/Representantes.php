<?php

use Illuminate\Database\Seeder;

class Representantes extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        App\Representante::create(['codrepresentante' => 'R001','codcanal'=> 'TRADITIONAL TRADE STS','user_id'=>'2']);
        App\Representante::create(['codrepresentante' => 'R002','codcanal'=> 'TRADITIONAL TRADE D&P','user_id'=>'3']);
    }
}
