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
        Schema::create('group_resources', function (Blueprint $table) {
            $table->id();
            if (Schema::hasTable('groups')) {
                $table->foreignId('group_id')->constrained('groups')->onDelete('cascade');
            }
            if (Schema::hasTable('resource')) {
                $table->foreignId('resource_id')->constrained('resources')->onDelete('cascade');
            }
            $table->string('resource_code');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('group_resources');
    }
};
