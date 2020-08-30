<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateCommentPhotosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comment_photos', function (Blueprint $table) {
            $table->unsignedBigInteger('idComment');
            $table->string('source');
            $table->foreign('idComment')->references('id')->on('comments');
        });
        DB::update('alter table comment_photos AUTO_INCREMENT= 10000');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comment_photos');
    }
}
