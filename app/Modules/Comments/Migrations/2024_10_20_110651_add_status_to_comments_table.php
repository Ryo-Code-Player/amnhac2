<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('comments', function (Blueprint $table) {
            // Đầu tiên, xóa trường status nếu nó đã tồn tại
            $table->dropColumn('status'); 

            // Thêm trường status với kiểu ENUM
            $table->enum('status', ['active', 'inactive'])->default('active')->after('comment_resources');
        });
    }

    public function down()
    {
        Schema::table('comments', function (Blueprint $table) {
            // Quay lại kiểu string nếu cần
            $table->dropColumn('status'); 
            $table->string('status')->default('active')->after('comment_resources'); // Thêm lại trường string nếu cần
        });
    }
};
