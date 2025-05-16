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
        Schema::create('billing_logs', function (Blueprint $table) {
            $table->id();
            $table->integer("user_id");
            $table->date("period_start");
            $table->date("period_end");
            $table->integer("total_token");
            $table->integer("total_mb");
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
        Schema::dropIfExists('billing_logs');
    }
};
