<?php
// filepath: f:\UGM\cloudcomputing\cloudcomputing_project\database\migrations\create_token_purchase_requests_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('token_purchase_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('package_id'); // basic, standard, premium
            $table->string('package_name');
            $table->integer('token_amount');
            $table->decimal('price', 10, 2);
            $table->integer('discount')->default(0);
            $table->enum('payment_method', ['bank_transfer', 'e_wallet', 'credit_card'])->default('bank_transfer');
            $table->enum('status', ['pending', 'approved', 'rejected', 'cancelled'])->default('pending');
            $table->text('user_notes')->nullable();
            $table->text('admin_notes')->nullable();
            $table->string('payment_proof')->nullable(); // File path for payment proof
            $table->foreignId('processed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();

            $table->index(['status', 'created_at']);
            $table->index('user_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('token_purchase_requests');
    }
};
