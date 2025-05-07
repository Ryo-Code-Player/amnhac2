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
        Schema::create('songs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('summary')->nullable();
            $table->text('content')->nullable();
            $table->json('resources')->nullable(); // Trường JSON cho các tài nguyên đa phương tiện
            $table->string('tags')->nullable(); // Lưu tags dưới dạng chuỗi
            $table->enum('status', ['active', 'inactive']);
            $table->unsignedBigInteger('composer_id');
            $table->unsignedBigInteger('singer_id');
            $table->unsignedBigInteger('musictype_id');
            $table->timestamps();
            $table->integer('view')->nullable()->default(0);
            // Thiết lập khóa ngoại
            $table->foreign('composer_id')->references('id')->on('composers')->onDelete('cascade');
            $table->foreign('singer_id')->references('id')->on('singers')->onDelete('cascade');
            $table->foreign('musictype_id')->references('id')->on('music_types')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('songs');
    }
};
