<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('downloads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('platform'); // youtube, tiktok, instagram
            $table->string('url');
            $table->string('title');
            $table->string('format'); // mp4, mp3
            $table->string('quality')->nullable(); // 1080p, 720p, etc.
            $table->integer('duration')->default(0);
            $table->integer('token_cost');
            $table->string('status')->default('pending');
            $table->float('progress')->default(0);
            $table->integer('file_size')->nullable();
            $table->string('file_path')->nullable();
            $table->string('storage_url')->nullable();
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
        Schema::dropIfExists('downloads');
    }
};
