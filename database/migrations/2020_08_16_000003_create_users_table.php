<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

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
            $table->string('passwordConfirm')->nullable();
            $table->dateTime('passwordChangedAt')->default(Carbon::now());
            $table->string('name');
            $table->unsignedBigInteger('address')->nullable();
            $table->string('phoneNumber');
            $table->string('photo')->default('/photos/user/default.png');
            $table->string('role')->default('user');
            $table->foreign('address')->references('id')->on('locations');
        });
        DB::update('alter table users AUTO_INCREMENT= 10000');
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
