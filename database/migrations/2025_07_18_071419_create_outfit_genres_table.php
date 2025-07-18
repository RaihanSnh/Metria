<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('outfit_genres', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique()->comment('Contoh: VKEI, Old Money, Casual');
            $table->text('description')->nullable()->comment('Penjelasan detail mengenai genre ini');
            $table->string('cover_image_url')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('outfit_genres');
    }
};