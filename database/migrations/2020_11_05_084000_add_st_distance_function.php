<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddStDistanceFunction extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('
            DROP FUNCTION IF EXISTS st_distance_sphere;
            CREATE FUNCTION st_distance_sphere(pt1 POINT, pt2 POINT)
            RETURNS double(10,2)
            
            RETURN 6371000 * 2 * ASIN(
                SQRT(
                    POWER(SIN((ST_Y(pt2) - ST_Y(pt1)) * pi()/180 / 2), 2) +
                    COS(ST_Y(pt1) * pi()/180 ) *
                    COS(ST_Y(pt2) * pi()/180) *
                    POWER(SIN((ST_X(pt2) - ST_X(pt1)) * pi()/180 / 2), 2)
                )
            );
        ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
