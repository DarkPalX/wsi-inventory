<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->integer('album_id');
            $table->string('title', 150)->nullable();
            $table->text('description')->nullable();
            $table->string('alt', 150)->nullable();
            $table->text('image_path');
            $table->string('button_text', 30)->nullable();
            $table->text('url')->nullable();
            $table->integer('order');
            $table->integer('user_id');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('banners');
    }
};
