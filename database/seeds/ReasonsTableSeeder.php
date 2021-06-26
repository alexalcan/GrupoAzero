<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReasonsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('reasons')->insert([
            'reason' => 'Equivocación en captura'
        ]);
        DB::table('reasons')->insert([
            'reason' => 'Razones del cliente'
        ]);
        DB::table('reasons')->insert([
            'reason' => 'No existe material'
        ]);
    }
}
