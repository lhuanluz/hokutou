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
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // User who owns the cart
            $table->json('products'); // JSON containing product ID, quantity, and price
            $table->decimal('total_value', 8, 2); // Total value of the cart
            $table->string('discount_coupon')->nullable(); // Discount coupon applied
            $table->decimal('discount_value', 8, 2)->nullable(); // Value of the discount
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};
