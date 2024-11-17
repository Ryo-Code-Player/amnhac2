<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('singers', function (Blueprint $table) {
            $table->id();
            $table->string('alias');
            $table->string('slug')->unique();
            $table->string('photo')->nullable();
            $table->unsignedBigInteger('type_id');
            $table->text('summary')->nullable();
            $table->text('content')->nullable();
            $table->integer('start_year')->nullable();
            $table->string('tags')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active'); // Sửa đổi kiểu dữ liệu thành ENUM
            $table->unsignedBigInteger('user_id')->nullable(); // Khóa ngoại cho user
            $table->unsignedBigInteger('company_id'); // Đã sửa lỗi chính tả từ 'comany_Id'
        
            $table->timestamps();
        });
        
    }

    public function down()
    {
        Schema::dropIfExists('singers');
    }
};
