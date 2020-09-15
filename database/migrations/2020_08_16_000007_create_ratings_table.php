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
            $table->unsignedBigInteger('ratedBy');
            $table->integer('rating');
            
            $table->index('idApartment');

            $table->primary(['idApartment', 'ratedBy' ]);
            $table->foreign('idApartment')->references('id')->on('apartments');
            $table->foreign('ratedBy')->references('id')->on('users');
        });
        DB::update('alter table ratings AUTO_INCREMENT = 10000');
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
