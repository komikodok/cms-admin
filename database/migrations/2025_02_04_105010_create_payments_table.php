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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->constrained(table: 'transactions')->onDelete('cascade');
            $table->timestamp('payment_date');
            $table->decimal('amount', 10, 2)->nullable();
            $table->enum('status', ['pending', 'success', 'failed'])->default('pending');
            $table->enum('payment_method', ['transfer', 'cash'])->nullable()->default(null);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
