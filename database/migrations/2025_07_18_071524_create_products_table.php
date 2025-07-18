<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->decimal('price', 10, 2);
            $table->enum('condition', ['new', 'pre-loved'])->default('new');
            
            // Outfit Constructor
            $table->enum('clothing_type', [
                'top', 'outerwear', 'bottom', 'full_body', 'shoes', 'accessory', 'hat'
            ])->comment('Kategori untuk penempatan di virtual try-on');
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
};