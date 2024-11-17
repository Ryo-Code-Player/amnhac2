<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('composers', function (Blueprint $table) {
            $table->id();
            $table->string('fullname');
            $table->string('slug')->unique();
            $table->enum('status', ['active', 'inactive']);
            $table->text('summary')->nullable();
            $table->longText('content')->nullable();
            $table->string('photo')->nullable();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('composers');
    }
};
