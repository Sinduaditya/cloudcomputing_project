<?php
// filepath: f:\UGM\cloudcomputing\cloudcomputing_project\database\migrations\2025_06_01_191842_add_cloudinary_field.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('downloads', function (Blueprint $table) {
            // Add cloudinary_public_id if it doesn't exist
            if (!Schema::hasColumn('downloads', 'cloudinary_public_id')) {
                $table->string('cloudinary_public_id')->nullable()->after('storage_url');
            }

            // Add cloudinary_url if it doesn't exist
            if (!Schema::hasColumn('downloads', 'cloudinary_url')) {
                $table->text('cloudinary_url')->nullable()->after('cloudinary_public_id');
            }

            // Modify storage_provider if it exists as string, change to enum
            if (Schema::hasColumn('downloads', 'storage_provider')) {
                // Drop and recreate as enum
                $table->dropColumn('storage_provider');
            }
        });

        // Add storage_provider as enum in separate schema call
        Schema::table('downloads', function (Blueprint $table) {
            $table->enum('storage_provider', ['local', 'cloudinary'])->default('local')->after('cloudinary_url');
        });
    }

    public function down()
    {
        Schema::table('downloads', function (Blueprint $table) {
            $columns = ['cloudinary_public_id', 'cloudinary_url', 'storage_provider'];

            foreach ($columns as $column) {
                if (Schema::hasColumn('downloads', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
