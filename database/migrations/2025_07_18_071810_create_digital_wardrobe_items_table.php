<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('digital_wardrobe_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('item_name');
            $table->string('item_image_url')->nullable();

            //Outfit Constructor
            $table->enum('clothing_type', [
                'top', 'outerwear', 'bottom', 'full_body', 'shoes', 'accessory', 'hat'
            ])->comment('Kategori untuk penempatan di virtual try-on');
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('digital_wardrobe_items');
    }
};