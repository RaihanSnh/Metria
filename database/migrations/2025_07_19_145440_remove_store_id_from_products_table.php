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
        Schema::table('products', function (Blueprint $table) {
            // Ensure the foreign key exists before trying to drop it.
            if (Schema::hasColumn('products', 'store_id')) {
                // The foreign key constraint name is usually `tablename_columnname_foreign`
                $table->dropForeign(['store_id']);
                $table->dropColumn('store_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->foreignId('store_id')->nullable()->constrained()->onDelete('cascade');
        });
    }
};
