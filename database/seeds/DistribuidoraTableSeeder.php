<?php

use Illuminate\Database\Seeder;

class DistribuidoraTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        App\Representante::create(['user_id'=>'17','canal'=>'STS','codrepresentante'=>'RP001']);
        App\Representante::create(['user_id'=>'18','canal'=>'DP','codrepresentante'=>'RP002']);

        App\Distribuidora::create(['name' => 'Distribuidora 1','representante_id'=>'1','user_id'=>'3','coddistribuidora'=>'D001','ruc' => '12345678901','trading' => 'wst','address' => 'Av. ......','reference' => 'Lorem Ipsum es simplemente el texto de relleno de las imprentas y archivos de texto. Lorem Ipsum ha sido el texto de relleno estándar de las industrias desde el año 1500, cuando un impresor (N. del T. persona que se dedica a la imprenta) desconocido usó una galería de textos y los mezcló de tal manera que logró hacer un libro de textos especimen. No sólo sobrevivió 500 años, sino que tambien ingresó como texto de relleno en documentos electrónicos, quedando esencialmente igual al original. Fue popularizado en los 60s con la creación de las hojas "Letraset", las cuales contenian pasajes de Lorem Ipsum, y más recientemente con software de autoedición, como por ejemplo Aldus PageMaker, el cual incluye versiones de Lorem Ipsum.','phone' => '(+52) 12 345 6789','email' => 'distribuidora1@correo.com']);
        App\Distribuidora::create(['name' => 'Distribuidora 2','representante_id'=>'2','user_id'=>'10','coddistribuidora'=>'D002','ruc' => '12345678902','trading' => 'wst','address' => 'Jr. ......','reference' => 'Lorem Ipsum es simplemente el texto de relleno de las imprentas y archivos de texto. Lorem Ipsum ha sido el texto de relleno estándar de las industrias desde el año 1500, cuando un impresor (N. del T. persona que se dedica a la imprenta) desconocido usó una galería de textos y los mezcló de tal manera que logró hacer un libro de textos especimen. No sólo sobrevivió 500 años, sino que tambien ingresó como texto de relleno en documentos electrónicos, quedando esencialmente igual al original. Fue popularizado en los 60s con la creación de las hojas "Letraset", las cuales contenian pasajes de Lorem Ipsum, y más recientemente con software de autoedición, como por ejemplo Aldus PageMaker, el cual incluye versiones de Lorem Ipsum.','phone' => '(+51) 78 945 6123','email' => 'distribuidora2@correo.com']);

        App\Supervisor::create(['user_id'=>4,'canal'=>'STS','codvendedor'=>'V001','distribuidor_id'=>1]);
        App\Supervisor::create(['user_id'=>7,'canal'=>'STS','codvendedor'=>'V002','distribuidor_id'=>1]);
        App\Supervisor::create(['user_id'=>11,'canal'=>'DP','codvendedor'=>'V003','distribuidor_id'=>2]);
        App\Supervisor::create(['user_id'=>14,'canal'=>'DP','codvendedor'=>'V004','distribuidor_id'=>2]);

        App\Vendedor::create(['user_id'=>5,'canal'=>'STS','codvendedor'=>'V005','supervisor_id'=>1]);
        App\Vendedor::create(['user_id'=>6,'canal'=>'STS','codvendedor'=>'V006','supervisor_id'=>1]);
        App\Vendedor::create(['user_id'=>8,'canal'=>'STS','codvendedor'=>'V007','supervisor_id'=>2]);
        App\Vendedor::create(['user_id'=>9,'canal'=>'STS','codvendedor'=>'V008','supervisor_id'=>2]);
        App\Vendedor::create(['user_id'=>12,'canal'=>'DP','codvendedor'=>'V009','supervisor_id'=>3]);
        App\Vendedor::create(['user_id'=>13,'canal'=>'DP','codvendedor'=>'V010','supervisor_id'=>3]);
        App\Vendedor::create(['user_id'=>15,'canal'=>'DP','codvendedor'=>'V011','supervisor_id'=>4]);
        App\Vendedor::create(['user_id'=>16,'canal'=>'DP','codvendedor'=>'V012','supervisor_id'=>4]);

    }
}
