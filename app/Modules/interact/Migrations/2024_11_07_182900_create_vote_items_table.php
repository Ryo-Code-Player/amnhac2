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
        Schema::create('vote_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained('item_types')->onDelete('cascade');
            $table->string('item_code');
            $table->integer('total');
            $table->integer('count');
            $table->json('user_list');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vote_items');
    }
};
