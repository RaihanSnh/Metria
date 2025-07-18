<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('materials', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique()->comment('Contoh: Katun, Polyester, Airism');
            $table->text('description')->nullable()->comment('Penjelasan tentang bahan, cocok cuaca apa, dll');
            $table->text('care_instructions')->nullable()->comment('Instruksi perawatan bahan');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('materials');
    }
};