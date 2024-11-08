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
        
        Schema::create('motion_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_motion')->constrained('motions')->onDelete('cascade');
            $table->foreignId('id_item')->constrained('item_types')->onDelete('cascade');
            $table->string('item_code');
            $table->integer('count');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('motions');
    }
};
