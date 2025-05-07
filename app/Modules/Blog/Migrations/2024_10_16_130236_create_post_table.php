<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('post', function (Blueprint $table) {
            $table->id();
            $table->string('description')->nullable(); 
            $table->string('link')->nullable(); 
            $table->string('image')->nullable(); 
            $table->string('user_id')->nullable(); 
            $table->string('like')->nullable(); 
            $table->string('dislike')->nullable(); 
            $table->string('comment')->nullable(); 
            $table->string('share')->nullable(); 
            $table->string('status')->nullable(); 
            $table->string('post_form')->nullable(); 
            $table->string('post_singer')->nullable(); 

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('post');
    }
};