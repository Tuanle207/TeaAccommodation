<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

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
            $table->unsignedBigInteger('address');
            $table->unsignedBigInteger('rent');
            $table->float('area');
            $table->string('phoneContact');
            $table->float('rating')->nullable();
            $table->unsignedBigInteger('views')->default(0);
            $table->string('status')->default('còn phòng'); // còn phòng hoặc hết phòng
            $table->boolean('active')->default(true);

            $table->foreign('postedBy')->references('id')->on('users');
            $table->foreign('address')->references('id')->on('addresses');
        });
        DB::update('alter table apartments AUTO_INCREMENT= 10000');
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
