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
        Schema::table('scheduled_tasks', function (Blueprint $table) {
            // Tambahkan kolom error_message jika belum ada
            if (!Schema::hasColumn('scheduled_tasks', 'error_message')) {
                $table->text('error_message')->nullable()->after('download_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('scheduled_tasks', function (Blueprint $table) {
            if (Schema::hasColumn('scheduled_tasks', 'error_message')) {
                $table->dropColumn('error_message');
            }
        });
    }
};
