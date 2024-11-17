<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('listeners', function (Blueprint $table) {
            $table->id(); // Tự động tạo khóa chính
            $table->string('favorite_type')->nullable(); // Loại yêu thích
            $table->string('favorite_song')->nullable(); // Bài hát yêu thích
            $table->string('favorite_singer')->nullable(); // Ca sĩ yêu thích
            $table->string('favorite_composer')->nullable(); // Nhạc sĩ yêu thích
            $table->enum('status',['active','inactive'])->default('active');
            $table->timestamps(); // Tự động thêm cột created_at và updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('listeners'); // Xóa bảng nếu rollback
    }
};
