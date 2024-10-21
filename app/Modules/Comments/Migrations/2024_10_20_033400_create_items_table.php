<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id(); // Tạo cột id (PK)
            $table->string('name'); // Cột tên item
            $table->text('description')->nullable(); // Mô tả (tuỳ chọn)
            $table->decimal('price', 8, 2); // Giá của item
            $table->timestamps(); // Timestamps created_at và updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('items');
    }
};

