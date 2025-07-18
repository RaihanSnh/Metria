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
        Schema::create('order_allocations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('store_id')->constrained()->onDelete('cascade');
            $table->decimal('distance_km', 8, 2)->comment('Jarak antara user dan toko dalam km');
            $table->integer('allocation_priority')->comment('Prioritas alokasi (1 = tertinggi)');
            $table->enum('allocation_method', ['distance', 'round_robin', 'manual'])->default('distance');
            $table->timestamp('allocated_at');
            $table->text('allocation_notes')->nullable();
            $table->timestamps();
            
            // Index untuk optimasi query
            $table->index(['order_id', 'allocation_priority']);
            $table->index(['store_id', 'allocated_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_allocations');
    }
};
