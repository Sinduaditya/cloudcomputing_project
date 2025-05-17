<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
        Schema::create('system_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Insert default settings
        DB::table('system_settings')->insert([
            ['key' => 'token_to_mb_ratio', 'value' => '0.2', 'description' => 'MB per token', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'initial_token_balance', 'value' => '1000', 'description' => 'Initial token for new users', 'created_at' => now(), 'updated_at' => now()]
        ]);
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('system_settings');
    }
};
