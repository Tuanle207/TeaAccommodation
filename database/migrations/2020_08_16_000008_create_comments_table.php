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
            $table->unsignedBigInteger('commentedBy');
            $table->string('photo')->nullable();
            $table->dateTime('commentedAt')->default(Carbon::now());

            $table->index('idApartment');

            $table->foreign('idApartment')->references('id')->on('apartments');
            $table->foreign('commentedBy')->references('id')->on('users');
        });
        DB::update('alter table comments AUTO_INCREMENT = 10000');
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
