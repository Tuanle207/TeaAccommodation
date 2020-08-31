<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FacilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('facilities')->insert([
            ['name' => 'wifi'],
            ['name' => 'máy giặt'],
            ['name' => 'tủ lạnh']
        ]);
    }
}
