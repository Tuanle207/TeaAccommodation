<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateApartmentServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('apartment_services', function (Blueprint $table) {
            $table->unsignedBigInteger('idApartment');
            $table->unsignedBigInteger('idService');
            $table->primary(['idApartment', 'idService' ]);
            $table->foreign('idApartment')->references('id')->on('apartments');
            $table->foreign('idService')->references('id')->on('services');
        });
        DB::update('alter table apartment_services AUTO_INCREMENT= 1000');
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
