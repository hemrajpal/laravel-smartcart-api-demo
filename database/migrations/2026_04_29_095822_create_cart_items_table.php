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
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();

             // User
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // Product
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();

            // Quantity
            $table->integer('quantity')->default(1);

            $table->timestamps();

            // Prevent duplicate product per user
            $table->unique(['user_id', 'product_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_items');
    }
};
