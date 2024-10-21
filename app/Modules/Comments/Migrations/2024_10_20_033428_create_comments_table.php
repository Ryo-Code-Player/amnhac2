<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id(); // Tạo cột id (PK)
            $table->foreignId('item_id')->constrained(); // FK item_id
            $table->string('item_code'); // Cột item_code
            $table->foreignId('user_id')->constrained(); // FK user_id
            $table->text('content'); // Cột content
            $table->foreignId('parent_id')->nullable()->constrained('comments'); // FK parent_id
            $table->json('comment_resources'); // Cột comment_resources
            $table->timestamps(); // Timestamps created_at và updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('comments');
    }
};
