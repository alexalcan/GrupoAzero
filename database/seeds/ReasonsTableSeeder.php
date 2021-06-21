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
            'reason' => 'Razóon 1'
        ]);
        DB::table('reasons')->insert([
            'reason' => 'Razóon 2'
        ]);
        DB::table('reasons')->insert([
            'reason' => 'Razóon 3'
        ]);
    }
}
