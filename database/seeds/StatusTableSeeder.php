<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Ventas genera
        DB::table('statuses')->insert([
            'name' => 'Pedido generado',
            'description' => 'Pedido generado de forma inicial'
        ]);
        // Embarques puede cambiar a recibido
        DB::table('statuses')->insert([
            'name' => 'Recibido por embarques',
            'description' => 'El pedido pasa al departamento de embarques'
        ]);
        // Fabricación y embarques son los únicos que pueden cambiar estatus a "En fabricación"
        DB::table('statuses')->insert([
            'name' => 'En fabricación',
            'description' => 'Cuando se tiene que fabricar el pedido'
        ]);
        // Fabricación y embarques son los únicos que pueden cambiar estatus a "En fabricación"
        DB::table('statuses')->insert([
            'name' => 'Fabricado',
            'description' => 'Estatus que se asigna cuando se termina de fabricar'
        ]);
        // Sólo Embarques puede cambiar a en ruta
        DB::table('statuses')->insert([
            'name' => 'En ruta',
            'description' => 'El pedido se encuentra en ruta'
        ]);
        // Flotilla y embarques puede cambiar a entregado
        DB::table('statuses')->insert([
            'name' => 'Entregado',
            'description' => 'El pedido se ha entregado'
        ]);
        // cancelacion
        DB::table('statuses')->insert([
            'name' => 'Cancelado',
            'description' => 'El pedido se cancela'
        ]);
    }
}
