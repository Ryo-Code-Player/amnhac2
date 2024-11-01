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
        Schema::create('group_members', function (Blueprint $table) {
            $table->id();
            if (Schema::hasTable('groups')) {
                $table->foreignId('group_id')->constrained('groups')->onDelete('cascade');
            }
            if (Schema::hasTable('users')) {
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            }
            if (Schema::hasTable('roles')) {
                $table->foreignId('role_id')->constrained('roles')->onDelete('cascade');
            }
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('group_members');
    }
};
