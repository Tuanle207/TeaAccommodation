<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApartmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('apartments', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('description');
            $table->unsignedBigInteger('postedBy');
            $table->unsignedBigInteger('location');
            $table->unsignedBigInteger('rent');
            $table->float('area');
            $table->string('phoneContact');
            $table->float('rating');
            $table->unsignedBigInteger('views');
            $table->string('status');
            $table->foreign('postedBy')->references('id')->on('users');
            $table->foreign('location')->references('id')->on('locations');
        });
        DB::update('alter table apartments AUTO_INCREMENT= 1000');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('apartments');
    }
}
