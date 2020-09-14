<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

use function GuzzleHttp\json_encode;

class CreateParametersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('parameters', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('value');
        });

        DB::update('alter table comments AUTO_INCREMENT = 10000');

        DB::insert('insert into parameters (name, value) values (?, ?)', 
            ['cities', json_encode(['Hồ Chí Minh'], JSON_UNESCAPED_UNICODE)]);

        DB::insert('insert into parameters (name, value) values (?, ?)', 
            ['facilities', json_encode(['wifi', 'tủ lạnh', 'máy giặt'], JSON_UNESCAPED_UNICODE)]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('parameters');
    }
}
