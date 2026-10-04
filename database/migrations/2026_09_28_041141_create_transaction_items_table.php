5<?php

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
        Schema::create('transaction_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->constrained()->restrictOnDelete();
            $table->foreignId('product_id')->constrained()->restrictOnDelete();
            $table->string('product_name', 150);
            $table->unsignedInteger('price');
            $table->unsignedInteger('qty');
            $table->unsignedInteger('subtotal');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_items');
    }
};
