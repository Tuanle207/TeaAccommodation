<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->string('text');
            $table->unsignedBigInteger('idApartment');
            $table->unsignedBigInteger('idUser');
            $table->dateTime('commentedAt')->default(Carbon::now());
            $table->foreign('idApartment')->references('id')->on('apartments');
            $table->foreign('idUser')->references('id')->on('users');
        });
        DB::update('alter table comments AUTO_INCREMENT= 1000');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comments');
    }
}
