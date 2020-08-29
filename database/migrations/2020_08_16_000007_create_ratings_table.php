<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateRatingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ratings', function (Blueprint $table) {
            $table->unsignedBigInteger('idApartment');
            $table->unsignedBigInteger('idUser');
            $table->float('rating');
            $table->primary(['idApartment', 'idUser' ]);
            $table->foreign('idApartment')->references('id')->on('apartments');
            $table->foreign('idUser')->references('id')->on('users');
        });
        DB::update('alter table ratings AUTO_INCREMENT= 1000');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ratings');
    }
}
