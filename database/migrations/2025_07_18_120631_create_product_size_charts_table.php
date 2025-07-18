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
        Schema::create('product_size_charts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('size_label')->comment('Label ukuran (S, M, L, XL, 28, 30, dll)');
            $table->integer('chest_cm')->nullable()->comment('Lingkar dada dalam cm');
            $table->integer('waist_cm')->nullable()->comment('Lingkar pinggang dalam cm');
            $table->integer('hip_cm')->nullable()->comment('Lingkar pinggul dalam cm');
            $table->integer('length_cm')->nullable()->comment('Panjang pakaian dalam cm');
            $table->integer('shoulder_cm')->nullable()->comment('Lebar bahu dalam cm');
            $table->integer('sleeve_cm')->nullable()->comment('Panjang lengan dalam cm');
            $table->decimal('weight_recommendation_min', 5, 2)->nullable()->comment('Berat badan minimum yang disarankan');
            $table->decimal('weight_recommendation_max', 5, 2)->nullable()->comment('Berat badan maksimum yang disarankan');
            $table->integer('height_recommendation_min')->nullable()->comment('Tinggi badan minimum yang disarankan');
            $table->integer('height_recommendation_max')->nullable()->comment('Tinggi badan maksimum yang disarankan');
            $table->timestamps();
            
            // Index untuk optimasi query
            $table->index(['product_id', 'size_label']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_size_charts');
    }
};
