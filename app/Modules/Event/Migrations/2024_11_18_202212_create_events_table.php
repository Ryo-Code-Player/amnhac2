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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->mediumText('photo');
            $table->string('slug')->unique();
            $table->text('summary')->nullable();
            $table->text('description')->nullable();
            $table->text('resources')->nullable();
            $table->timestamp('timestart')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('timeend')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->text('diadiem');
            $table->string('tags')->nullable();
            $table->foreignId('event_type_id')->constrained('event_types')->onDelete('cascade');
            $table->timestamps();
            $table->string('price')->nullable();
            $table->string('quantity')->nullable();
            $table->string('fanclub_id')->nullable();


        });
    }


 
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
