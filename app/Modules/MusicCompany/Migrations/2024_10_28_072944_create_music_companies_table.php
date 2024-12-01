<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('music_companies', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique()->nullable();
            $table->string('address')->nullable();
            $table->mediumText('photo')->nullable();
            $table->text('summary')->nullable();
            $table->text('content')->nullable();
             $table->json('resources')->nullable(); // Thêm trường resources kiểu JSON
             $table->string('tags')->nullable(); // Lưu tags dưới dạng chuỗi
            $table->enum('status',['active','inactive'])->default('active');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->unsignedBigInteger('user_id')->nullable(); // Khóa ngoại cho user
           
            $table->timestamps();

            // Thiết lập khóa ngoại cho user_id
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('music_companies', function (Blueprint $table) {
            $table->dropForeign(['user_id']); // Xóa khóa ngoại
        });

        Schema::dropIfExists('music_companies'); // Xóa bảng nếu tồn tại
    }
};
