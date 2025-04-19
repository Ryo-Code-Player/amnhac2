<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('music_types', function (Blueprint $table) {
            $table->id(); // Tạo cột id
            $table->string('title'); // Tạo cột title
            $table->mediumText('photo');
            $table->string('slug')->unique(); // Tạo cột slug và đánh chỉ mục
            $table->enum('status',['active','inactive'])->default('active');
            $table->timestamps(); // Tạo cột created_at và updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('music_types');
    }
};
