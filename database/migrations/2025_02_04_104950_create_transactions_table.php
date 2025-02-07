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
            $table->foreignId('tenant_id')->constrained(table: 'tenants');
            $table->foreignId('room_id')->constrained(table: 'rooms');
            $table->timestamp('start_date');
            $table->timestamp('end_date');
            $table->enum('status', ['pending', 'confirmed', 'canceled'])->default('pending');
            $table->string('token')->nullable();
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
