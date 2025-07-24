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
        Schema::table('digital_wardrobe_items', function (Blueprint $table) {
            $table->string('color')->nullable()->after('clothing_type');
            $table->string('brand')->nullable()->after('color');
            $table->string('size')->nullable()->after('brand');
            $table->string('material')->nullable()->after('size');
            $table->date('purchase_date')->nullable()->after('material');
            $table->decimal('price', 10, 2)->nullable()->after('purchase_date');
            $table->text('notes')->nullable()->after('price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('digital_wardrobe_items', function (Blueprint $table) {
            $table->dropColumn([
                'color',
                'brand',
                'size',
                'material',
                'purchase_date',
                'price',
                'notes'
            ]);
        });
    }
};
