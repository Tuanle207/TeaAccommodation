<?php

use Illuminate\Database\Seeder;
use FacilitySeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(FacilitySeeder::class);
    }
}
