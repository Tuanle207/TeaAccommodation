<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateApartmentFacilitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('apartment_facilities', function (Blueprint $table) {
            $table->unsignedBigInteger('idApartment');
            $table->unsignedBigInteger('idFacility');
            $table->primary(['idApartment', 'idFacility' ]);
            $table->foreign('idApartment')->references('id')->on('apartments');
            $table->foreign('idFacility')->references('id')->on('facilities');
        });
        DB::update('alter table apartment_facilities AUTO_INCREMENT= 10000');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('apartment_services');
    }
}
