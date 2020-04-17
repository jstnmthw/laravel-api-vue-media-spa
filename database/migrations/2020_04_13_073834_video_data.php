<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class VideoData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('video_data', function (Blueprint $table) {

            $table->id();
            $table->text('embed');
            $table->text('thumbnail');
            $table->text('album');
            $table->text('title');
            $table->text('tags');
            $table->text('categories');
            $table->text('author');
            $table->integer('duration');
            $table->integer('views');
            $table->integer('likes');
            $table->integer('dislikes');
            $table->timestamps();

        });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('video_data');
    }
}
