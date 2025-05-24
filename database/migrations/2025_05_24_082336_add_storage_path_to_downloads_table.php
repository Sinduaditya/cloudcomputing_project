<?php
// filepath: f:\UGM\cloudcomputing\cloudcomputing_project\database\migrations\2025_05_24_add_storage_path_to_downloads_table.php

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
        Schema::table('downloads', function (Blueprint $table) {
            // Add storage_path column if it doesn't exist
            if (!Schema::hasColumn('downloads', 'storage_path')) {
                $table->string('storage_path')->nullable()->after('file_path');
            }

            // Make sure storage_provider column exists too
            if (!Schema::hasColumn('downloads', 'storage_provider')) {
                $table->string('storage_provider')->nullable()->after('storage_url');
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
        Schema::table('downloads', function (Blueprint $table) {
            if (Schema::hasColumn('downloads', 'storage_path')) {
                $table->dropColumn('storage_path');
            }

            if (Schema::hasColumn('downloads', 'storage_provider')) {
                $table->dropColumn('storage_provider');
            }
        });
    }
};
