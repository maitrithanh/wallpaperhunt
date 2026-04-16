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
        Schema::create('photos', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->integer('like_count')->default(0);
            $table->integer('view_count')->default(0);
            $table->string('category_id')->nullable();
            $table->string('src'); // Đường dẫn đến file ảnh
            $table->tinyInteger('status')->default(1); // 1: active, 0: inactive, -1: deleted, 2: blocked
            $table->unsignedBigInteger('album_id'); // Quan hệ với bảng albums
            $table->unsignedBigInteger('partner_id'); // Quan hệ với bảng partners
            $table->decimal('price', 12, 2)->nullable(); // Giá bán của ảnh, có thể null nếu ảnh miễn phí
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('photos');
    }
};
