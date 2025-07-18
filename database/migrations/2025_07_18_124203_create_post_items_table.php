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
        Schema::create('post_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->constrained()->onDelete('cascade');
            $table->string('item_type')->comment('product, digital_wardrobe_item, atau wishlist_item');
            $table->unsignedBigInteger('item_id')->comment('ID dari item yang ditandai');
            $table->decimal('position_x', 5, 2)->comment('Posisi X dalam persentase (0-100)');
            $table->decimal('position_y', 5, 2)->comment('Posisi Y dalam persentase (0-100)');
            $table->string('affiliate_code')->nullable()->comment('Kode afiliasi jika ada');
            $table->timestamps();
            
            // Index untuk polymorphic relationship
            $table->index(['item_type', 'item_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('post_items');
    }
};
