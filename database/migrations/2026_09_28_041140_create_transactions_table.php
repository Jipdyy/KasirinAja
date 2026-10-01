<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_code', 30)->unique();
            $table->foreignId('user_id')->constrained()->restrictOnDelete();
            $table->unsignedInteger('total_items');
            $table->unsignedInteger('total_amount');
            $table->string('payment_method', 30);
            $table->unsignedInteger('cash_amount')->nullable();
            $table->unsignedInteger('change_amount')->default(0);
            $table->string('status', 20)->default('completed');
            $table->timestamps();

            $table->index('created_at');
            $table->index(['user_id', 'created_at']);   
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
