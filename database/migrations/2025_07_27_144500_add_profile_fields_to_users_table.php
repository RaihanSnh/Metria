<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('bio')->nullable()->after('email');
            $table->string('profile_image')->nullable()->after('bio');
            $table->string('cover_image')->nullable()->after('profile_image');
            $table->string('location')->nullable()->after('cover_image');
            $table->string('website')->nullable()->after('location');
            $table->date('birth_date')->nullable()->after('website');
            $table->enum('gender', ['male', 'female', 'other'])->nullable()->after('birth_date');
            $table->boolean('is_private')->default(false)->after('gender');
            $table->integer('followers_count')->default(0)->after('is_private');
            $table->integer('following_count')->default(0)->after('followers_count');
            $table->integer('posts_count')->default(0)->after('following_count');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'bio', 'profile_image', 'cover_image', 'location', 
                'website', 'birth_date', 'gender', 'is_private',
                'followers_count', 'following_count', 'posts_count'
            ]);
        });
    }
};
