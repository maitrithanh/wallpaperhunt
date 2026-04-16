<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('albums', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->integer('like_count')->default(0);
            $table->integer('view_count')->default(0);
            $table->string('thumbnail')->nullable(); // Hình đại diện của album mặc định là hình của wallpaper đầu tiên trong album
            $table->tinyInteger('status')->default(1); // 1: active, 0: inactive, -1: deleted, 2: blocked
            $table->unsignedBigInteger('wallpaper_count')->default(0); // Số lượng wallpaper trong album
            $table->unsignedBigInteger('partner_id'); // Quan hệ với bảng partners
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('albums');
    }
};
