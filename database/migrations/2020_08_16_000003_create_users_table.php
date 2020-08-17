<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('passwordConfirm');
            $table->date('passwordChangeAt');
            $table->string('name');
            $table->unsignedBigInteger('address');
            $table->string('phoneNumber');
            $table->string('photo');
            $table->string('role');
            $table->foreign('address')->references('id')->on('locations');
        });
        DB::update('alter table users AUTO_INCREMENT= 1000');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
