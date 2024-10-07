<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->dateTime('transaction_date'); // Alterado para datetime
            $table->decimal('amount', 10, 2);
            $table->enum('transaction_type', ['entry', 'exit']);
            $table->enum('payment_method', ['Pix', 'Cash', 'Debit', 'Credit']);
            $table->integer('installments')->default(1);
            $table->decimal('payment_fee', 5, 2)->default(0);
            $table->foreignId('cart_id')->constrained()->onDelete('cascade')->nullable();
            $table->foreignId('expense_id')->constrained()->onDelete('cascade')->nullable();
            $table->timestamps();
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
