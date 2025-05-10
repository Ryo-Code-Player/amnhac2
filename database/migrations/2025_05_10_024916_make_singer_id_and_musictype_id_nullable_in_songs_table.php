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
    
        Schema::table('songs', function (Blueprint $table) {
            $table->dropForeign(['singer_id']);
        });

        Schema::table('songs', function (Blueprint $table) {
            $table->unsignedBigInteger('singer_id')->nullable()->change();
        });

        Schema::table('songs', function (Blueprint $table) {
            $table->foreign('singer_id')->references('id')->on('singers')->onDelete('set null');
        });

        Schema::table('songs', function (Blueprint $table) {
            $table->dropForeign(['musictype_id']);
        });

        Schema::table('songs', function (Blueprint $table) {
            $table->unsignedBigInteger('musictype_id')->nullable()->change();
        });

        Schema::table('songs', function (Blueprint $table) {
            $table->foreign('musictype_id')->references('id')->on('music_types')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('songs', function (Blueprint $table) {
            $table->dropForeign(['singer_id']);
        });

        Schema::table('songs', function (Blueprint $table) {
            $table->unsignedBigInteger('singer_id')->nullable(false)->change();
        });

        Schema::table('songs', function (Blueprint $table) {
            $table->foreign('singer_id')->references('id')->on('singers')->onDelete('cascade');
        });

        Schema::table('songs', function (Blueprint $table) {
            $table->dropForeign(['musictype_id']);
        });

        Schema::table('songs', function (Blueprint $table) {
            $table->unsignedBigInteger('musictype_id')->nullable(false)->change();
        });

        Schema::table('songs', function (Blueprint $table) {
            $table->foreign('musictype_id')->references('id')->on('music_types')->onDelete('cascade');
        });
    }
};
