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
        Schema::create('size_recommendations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('recommended_size')->comment('Ukuran yang direkomendasikan');
            $table->decimal('confidence_score', 3, 2)->comment('Skor kepercayaan rekomendasi (0-1)');
            $table->json('calculation_data')->comment('Data perhitungan untuk audit trail');
            $table->text('recommendation_notes')->nullable()->comment('Catatan tambahan untuk rekomendasi');
            $table->timestamps();
            
            // Index untuk optimasi query
            $table->index(['user_id', 'product_id']);
            $table->index(['product_id', 'recommended_size']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('size_recommendations');
    }
};
