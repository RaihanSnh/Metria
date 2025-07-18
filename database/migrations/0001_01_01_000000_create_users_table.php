<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique();
            $table->string('full_name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('profile_picture_url')->nullable();

            $table->integer('height_cm')->comment('Tinggi badan dalam cm');
            $table->decimal('weight_kg', 5, 2)->comment('Berat badan dalam kg');
            $table->integer('bust_circumference_cm')->nullable()->comment('Lingkar Dada (LD) dalam cm');
            $table->integer('waist_circumference_cm')->nullable()->comment('Lingkar Pinggang (LP) dalam cm');
            $table->integer('hip_circumference_cm')->nullable()->comment('Lingkar Pinggul dalam cm');

            $table->boolean('is_affiliate')->default(false);
            $table->string('affiliate_code')->unique()->nullable();
            
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
};