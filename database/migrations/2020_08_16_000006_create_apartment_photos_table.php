<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateApartmentPhotosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('apartment_photos', function (Blueprint $table) {
            $table->unsignedBigInteger('idApartment');
            $table->string('source');
            $table->foreign('idApartment')->references('id')->on('apartments');
        });
        DB::update('alter table apartment_photos AUTO_INCREMENT= 10000');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('apartment_photos');
    }
}
